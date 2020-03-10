<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PricesModelWatchers extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'w.id',
                'u.name',
                'i.title',
                'p.title',
                'search',
                'manager',
                'price',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
        $this->itemID = (!empty($config['itemID'])) ? $config['itemID'] : 0;
    }

    protected function _getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        //Ограничение длины списка
        $limit = (!$this->export && $this->itemID === 0) ? $this->getState('list.limit') : 0;
        $query
            ->from("`#__mkv_price_watchers` w");

        if ($this->itemID > 0) {
            $query
                ->select("w.userID")
                ->where("w.itemID = {$this->_db->q($this->itemID)}");
            /* Сортировка */
            $orderCol = "w.id";
            $orderDirn = "asc";
        }
        else {
            $query
                ->select("w.id, u.name as manager, i.title as item, w.itemID, w.userID, p.title as price")
                ->leftJoin("#__mkv_price_items i on i.id = w.itemID")
                ->leftJoin("#__mkv_price_sections s on s.id = i.sectionID")
                ->leftJoin("#__mkv_prices p on p.id = s.priceID")
                ->leftJoin("#__users u on u.id = w.userID");
            $search = (!$this->export) ? $this->getState('filter.search') : JFactory::getApplication()->input->getString('search', '');
            if (!empty($search)) {
                $text = $db->q("%{$search}%");
                $query->where("i.title like {$text}");
            }
            $manager = $this->getState('filter.manager');
            if (is_numeric($manager)) {
                $query->where("w.userID = {$this->_db->q($manager)}");
            }
            $price = $this->getState("filter.price");
            if (is_numeric($price)) {
                $query->where("s.priceID = {$this->_db->q($price)}");
            }
            /* Сортировка */
            $orderCol = $this->state->get('list.ordering');
            $orderDirn = $this->state->get('list.direction');
        }

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        $this->setState('list.limit', $limit);

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $return = PricesHelper::getReturnUrl();
        if ($this->itemID > 0) {
            foreach ($items as $item) {
                $result[] = $item->userID;
            }
        }
        else {
            foreach ($items as $item) {
                $arr = ['items' => []];
                $arr['id'] = $item->id;
                $arr['manager'] = $item->manager;
                $arr['item'] = $item->item;
                $arr['price'] = $item->price;
                $url = JRoute::_("index.php?option={$this->option}&amp;task=item.edit&amp;id={$item->itemID}&amp;return={$return}");
                $arr['edit_link'] = JHtml::link($url, $item->item);
                $result['items'][] = $arr;
            }
        }
        return $result;
    }

    protected function populateState($ordering = 'w.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager');
        $this->setState('filter.manager', $manager);
        $price = $this->getUserStateFromRequest($this->context . '.filter.price', 'filter_price');
        $this->setState('filter.price', $price);
        parent::populateState($ordering, $direction);
        if (!$this->export && $this->itemID === 0) PricesHelper::check_refresh();
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.price');
        return parent::getStoreId($id);
    }

    private $export, $itemID;
}
