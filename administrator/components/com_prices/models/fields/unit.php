<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldUnit extends JFormFieldList
{
    protected $type = 'Unit';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("u.id, u.title")
            ->from("`#__mkv_price_units` u")
            ->order("u.weight");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}