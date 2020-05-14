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
            ->select("i.id, i.title as item")
            ->select("s.title as section")
            ->select("p.title as price")
            ->from("`#__mkv_price_items` i")
            ->leftJoin("`#__mkv_price_sections` s on s.id = i.sectionID")
            ->leftJoin("`#__mkv_prices` p on p.id = s.priceID")
            ->order("i.weight");

        JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_prj/tables");
        $table = JTable::getInstance('Projects', 'TablePrj');
        $project = PrjHelper::getActiveProject();
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
                $options[$p->section][] = JHtml::_('select.option', $p->id, $p->item);
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