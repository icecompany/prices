<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class PricesModelWatcher extends AdminModel {

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function save($data)
    {
        return parent::save($data);
    }

    public function getTable($name = 'Watchers', $prefix = 'TablePrices', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        return false;
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }
}