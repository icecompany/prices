<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');
require_once JPATH_ADMINISTRATOR . "/components/com_prj/helpers/prj.php";

class JFormFieldPriceItem extends JFormFieldGroupedList
{
    protected $type = 'PriceItem';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("i.id, i.title as item, i.available")
            ->select("un.title as unit")
            ->select("s.title as section")
            ->select("p.title as price")
            ->from("`#__mkv_price_items` i")
            ->leftJoin("`#__mkv_price_sections` s on s.id = i.sectionID")
            ->leftJoin("#__mkv_price_units un on un.id = i.unit_1_ID")
            ->leftJoin("`#__mkv_prices` p on p.id = s.priceID")
            ->order("i.weight");

        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);

        //Добавляем текущий пункт прайса, даже если доступно к заказу 0
        if ($input->getString('option') == 'com_contracts' && $input->getString('view') == 'item') {
            JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_contracts/tables");
            $t = JTable::getInstance('Items', 'TableContracts');
            $t->load($id);
            if ($id > 0) {
                $query->where("(i.id = {$db->q($t->itemID)} or i.disabled != 1)");
            }
            else {
                $query->where("i.disabled != 1");
            }
        }

        //Фильтруем - выбираем только пункты из прайса, который указан в настройках проекта, к которому привязана сделка
        $contractID = JFactory::getApplication()->getUserState('com_contracts.item.contractID', 0);
        JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_contracts/tables");
        $ct = JTable::getInstance('Contracts', 'TableContracts');
        $ct->load($contractID);
        JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_prj/tables");
        $table = JTable::getInstance('Projects', 'TablePrj');
        $project = $ct->projectID;
        if ($input->getString('option') === 'com_reports' && $input->getString('view') === 'price') {
            $project = PrjHelper::getActiveProject(null) ?? MkvHelper::getConfig('default_project');
        }
        if (is_numeric($project)) {
            $table->load($project);
            if (is_numeric($table->priceID)) {
                $query->where("p.id = {$table->priceID}");
            }
        }

        $result = $db->setQuery($query)->loadObjectList();

        $options = [];

        if ($result) {
            foreach ($result as $p) {
                if (!isset($options[$p->section])) $options[$p->section] = [];
                $title = $p->item;
                if ($p->available > 0) $title .= " (" . JText::sprintf('COM_PRICES_TEXT_AVAILABLE_N_ITEMS', $p->available, $p->unit) . ")";
                $options[$p->section][] = JHtml::_('select.option', $p->id, $title);
            }
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getGroups(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getGroups();
    }
}