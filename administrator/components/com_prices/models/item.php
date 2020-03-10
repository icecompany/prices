<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\ListModel;

class PricesModelItem extends AdminModel {

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        if ($item->id !== null) {
            $item->watchers = $this->getWatchers($item->id);
        }
        return $item;
    }

    public function save($data)
    {
        $s1 = parent::save($data);
        $itemID = $data['id'] ?? JFactory::getDbo()->insertid();
        $s2 = $this->saveWatchers((int) $itemID, (array) $data['watchers'] ?? []);
        return $s1 && $s2;
    }

    private function getWatchers(int $id): array
    {
        $model = ListModel::getInstance('Watchers', 'PricesModel', array('itemID' => $id));
        return $model->getItems();
    }

    private function saveWatchers(int $itemID, array $watchers = array()): bool
    {
        $current = $this->getWatchers($itemID);
        if (empty($current)) {
            if (empty($watchers)) return true;
            foreach ($watchers as $watcher)
                if (!$this->addWatcher($itemID, $watcher)) return false;
        }
        else {
            foreach ($watchers as $item)
                if (($key = array_search($item, $current)) === false)
                    if (!$this->addWatcher($itemID, $item)) return false;
            foreach ($current as $item)
                if (($key = array_search($item, $watchers)) === false)
                    if (!$this->deleteWatcher($itemID, $item)) return false;
        }
        return true;
    }

    private function addWatcher(int $itemID, int $userID): bool
    {
        $table = $this->getTable('Watchers', 'TablePrices');
        $data = array('id' => null, 'itemID' => $itemID, 'userID' => $userID);
        return $table->save($data);
    }

    private function deleteWatcher(int $itemID, int $userID): bool
    {
        $table = $this->getTable('Watchers', 'TablePrices');
        $table->load(array('itemID' => $itemID, 'userID' => $userID));
        return $table->delete($table->id);
    }

    public function getTable($name = 'Items', $prefix = 'TablePrices', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.item', 'item', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.item.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
        $all = get_class_vars($table);
        unset($all['_errors']);
        $nulls = ['type', 'unit_2_ID', 'title_en']; //Поля, которые NULL
        foreach ($all as $field => $v) {
            if (empty($field)) continue;
            if (in_array($field, $nulls)) {
                if (!strlen($table->$field)) {
                    $table->$field = NULL;
                    continue;
                }
            }
            if (!empty($field)) $table->$field = trim($table->$field);
        }

        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.item.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/item.js';
    }
}