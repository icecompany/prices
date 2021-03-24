<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');

class JFormFieldPriceEquipment extends JFormFieldGroupedList
{
    protected $type = 'PriceEquipment';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("i.id, i.type, i.title")
            ->from("`#__mkv_price_equipments` i")
            ->order("i.title");

        $result = $db->setQuery($query)->loadObjectList();

        $options = [];

        if ($result) {
            foreach ($result as $p) {
                $type = mb_strtoupper($p->type);
                $type = JText::sprintf("COM_PRICES_FORM_EQUIPMENT_TYPE_{$type}");
                if (!isset($options[$type])) $options[$type] = [];
                $options[$type][] = JHtml::_('select.option', $p->id, $p->title);
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