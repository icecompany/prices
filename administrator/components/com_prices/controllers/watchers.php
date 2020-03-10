<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class PricesControllerWatchers extends AdminController
{
    public function getModel($name = 'Watcher', $prefix = 'PricesModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
