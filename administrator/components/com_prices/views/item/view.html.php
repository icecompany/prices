<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class PricesViewItem extends HtmlView {
    protected $item, $form, $script;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tmp);
    }

    protected function addToolbar() {
	    JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('item.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
        JFactory::getApplication()->input->set('hidemainmenu', true);
    }

    protected function setDocument() {
        $title = ($this->item->id !== null) ? JText::sprintf('COM_PRICES_TITLE_ITEM_EDIT', $this->item->title) : JText::sprintf('COM_PRICES_TITLE_ITEM_ADD');
        JToolbarHelper::title($title, 'list');
        JHtml::_('bootstrap.framework');
    }
}