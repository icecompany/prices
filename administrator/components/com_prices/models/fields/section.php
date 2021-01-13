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
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("s.id, s.title as section")
            ->select("p.title as price")
            ->from("`#__mkv_price_sections` s")
            ->leftJoin("`#__mkv_prices` p on p.id = s.priceID")
            ->order("s.id desc");

        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);

        if ($input->getString('option') == 'com_prices' && $input->getString('view') == 'item') {
            if ($id === 0) {
                $project = PrjHelper::getActiveProject(null) ?? MkvHelper::getConfig('default_project');
                if (is_numeric($project)) {
                    JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_prj/tables");
                    $table = JTable::getInstance('Projects', 'TablePrj');
                    $table->load($project);
                    $priceID = $table->priceID;
                }
            }
            else {
                $table = JTable::getInstance('Items', 'TablePrices');
                $table->load($id);
                $sectionID = $table->sectionID;
                if (is_numeric($sectionID)) {
                    $table = JTable::getInstance('Sections', 'TablePrices');
                    $table->load($sectionID);
                    $priceID = $table->priceID;
                }
            }
            if (is_numeric($priceID)) $query->where("s.priceID = {$priceID}");
        }

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