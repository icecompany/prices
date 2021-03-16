<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
require_once JPATH_ADMINISTRATOR . "/components/com_prj/helpers/prj.php";

class JFormFieldStandItem extends JFormFieldList
{
    protected $type = 'StandItem';
    protected $loadExternally = 0;

    protected function getOptions()
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
            ->where("i.square_type in (1, 2, 3, 4, 5, 6, 9)")
            ->order("i.weight");

        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);

        //Фильтруем - выбираем только пункты из прайса, который указан в настройках проекта, к которому привязана сделка
        JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_prj/tables");
        $table = JTable::getInstance('Projects', 'TablePrj');
        $project = PrjHelper::getActiveProject(MkvHelper::getConfig('default_project'));
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
                $title = $p->item;
                $options[] = JHtml::_('select.option', $p->id, $title);
            }
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getGroups();
    }
}