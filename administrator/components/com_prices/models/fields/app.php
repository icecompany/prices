<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldApp extends JFormFieldList
{
    protected $type = 'App';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("a.id, a.title")
            ->from("`#__mkv_price_apps` a")
            ->order("a.weight");
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