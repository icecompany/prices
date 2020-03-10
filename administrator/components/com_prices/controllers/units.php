<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class PricesControllerUnits extends AdminController
{
    public function getModel($name = 'Unit', $prefix = 'PricesModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
