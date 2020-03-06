<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PricesModelSections extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                's.id',
                's.title',
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
            ->select("s.id, s.title, p.title as price")
            ->from("#__mkv_price_sections s")
            ->leftJoin("`#__mkv_prices` p on p.id = s.priceID");
        $search = (!$this->export) ? $this->getState('filter.search') : JFactory::getApplication()->input->getString('search', '');
        if (!empty($search)) {
            if (stripos($search, 'id:') !== false) { //Поиск по ID
                $id = explode(':', $search);
                $id = $id[1];
                if (is_numeric($id)) {
                    $id = $db->q($id);
                    $query->where("s.id = {$id}");
                }
            }
            else {
                $text = $db->q("%{$search}%");
                $query->where("(s.title like {$text})");
            }
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
            $url = JRoute::_("index.php?option={$this->option}&amp;task=section.edit&amp;id={$item->id}");
            $arr['edit_link'] = JHtml::link($url, $item->title);
            $result['items'][] = $arr;
        }
        return $result;
    }

    protected function populateState($ordering = 's.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $price = $this->getUserStateFromRequest($this->context . '.filter.price', 'filter_price');
        $this->setState('filter.price', $price);
        parent::populateState($ordering, $direction);
        PricesHelper::check_refresh();
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.price');
        return parent::getStoreId($id);
    }

    private $export;
}
