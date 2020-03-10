<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');

class JFormFieldSection extends JFormFieldGroupedList
{
    protected $type = 'Section';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("s.id, s.title as section")
            ->select("p.title as price")
            ->from("`#__mkv_price_sections` s")
            ->leftJoin("`#__mkv_prices` p on p.id = s.priceID")
            ->order("s.id desc");
        $result = $db->setQuery($query)->loadObjectList();

        $options = [];

        if ($result) {
            foreach ($result as $p) {
                if (!isset($options[$p->price])) $options[$p->price] = [];
                $options[$p->price][] = JHtml::_('select.option', $p->id, $p->section);
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