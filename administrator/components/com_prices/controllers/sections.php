<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class PriceControllerSections extends AdminController
{
    public function getModel($name = 'Section', $prefix = 'PricesModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
