<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class PricesViewSections extends HtmlView
{
    protected $sidebar = '';
    public $items, $pagination, $uid, $state, $filterForm, $activeFilters;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Show the toolbar
        $this->toolbar();

        // Show the sidebar
        PricesHelper::addSubmenu('sections');
        $this->sidebar = JHtmlSidebar::render();

        // Display it all
        return parent::display($tpl);
    }

    private function toolbar()
    {
        JToolBarHelper::title(JText::sprintf('COM_PRICES_MENU_SECTIONS'), 'list-2');

        if (PricesHelper::canDo('core.create'))
        {
            JToolbarHelper::addNew('section.add');
        }
        if (PricesHelper::canDo('core.edit'))
        {
            JToolbarHelper::editList('section.edit');
        }
        if (PricesHelper::canDo('core.delete'))
        {
            JToolbarHelper::deleteList('COM_PRICES_CONFIRM_REMOVE_SECTION', 'sections.delete');
        }
        if (PricesHelper::canDo('core.admin'))
        {
            JToolBarHelper::preferences('com_prices');
        }
    }
}
