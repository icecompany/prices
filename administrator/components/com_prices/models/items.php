<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PricesModelItems extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'i.id',
                'i.title',
                'i.weight',
                'section',
                'price',
                'search',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
    }

    protected function _getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        //Ограничение длины списка
        $limit = (!$this->export) ? $this->getState('list.limit') : 0;

        $query
            ->select("i.id, i.title, i.type, i.price_rub, i.column_1, i.column_2, i.column_3, i.disabled, i.weight")
            ->select("s.title as section, p.title as price")
            ->from("#__mkv_price_items i")
            ->leftJoin("`#__mkv_price_sections` s on s.id = i.sectionID")
            ->leftJoin("`#__mkv_prices` p on p.id = s.priceID");
        $search = (!$this->export) ? $this->getState('filter.search') : JFactory::getApplication()->input->getString('search', '');
        if (!empty($search)) {
            if (stripos($search, 'id:') !== false) { //Поиск по ID
                $id = explode(':', $search);
                $id = $id[1];
                if (is_numeric($id)) {
                    $id = $db->q($id);
                    $query->where("i.id = {$id}");
                }
            }
            else {
                $text = $db->q("%{$search}%");
                $query->where("(i.title like {$text})");
            }
        }

        $section = $this->getState('filter.section');
        if (is_numeric($section)) {
            $query->where("i.sectionID = {$this->_db->q($section)}");
        }

        $price = $this->getState('filter.price');
        if (is_numeric($price)) {
            $query->where("s.priceID = {$this->_db->q($price)}");
        }

        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        $this->setState('list.limit', $limit);

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr = ['items' => []];
            $arr['id'] = $item->id;
            $arr['title'] = $item->title;
            $arr['price'] = $item->price;
            $arr['section'] = $item->section;
            $arr['weight'] = $item->weight;
            $arr['disabled'] = $item->disabled;
            $item_type = mb_strtoupper($item->type);
            $arr['type'] = JText::sprintf("COM_PRICES_ITEM_TYPE_{$item_type}");
            $arr['column_1'] = sprintf("%d%%", $this->convertToPercent($item->column_1));
            $arr['column_2'] = sprintf("%d%%", $this->convertToPercent($item->column_2));
            $arr['column_3'] = sprintf("%d%%", $this->convertToPercent($item->column_3));
            $url = JRoute::_("index.php?option={$this->option}&amp;task=item.edit&amp;id={$item->id}");
            $arr['edit_link'] = JHtml::link($url, $item->title);
            $result['items'][] = $arr;
        }
        return $result;
    }

    public function convertToPercent(float $value): int
    {
        return (int) $value * 100 - 100;
    }

    protected function populateState($ordering = 's.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $price = $this->getUserStateFromRequest($this->context . '.filter.price', 'filter_price');
        $this->setState('filter.price', $price);
        $section = $this->getUserStateFromRequest($this->context . '.filter.section', 'filter_section');
        $this->setState('filter.section', $section);
        parent::populateState($ordering, $direction);
        PricesHelper::check_refresh();
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.price');
        $id .= ':' . $this->getState('filter.section');
        return parent::getStoreId($id);
    }

    private $export;
}
