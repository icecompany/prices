<?php
defined('_JEXEC') or die;
header("Access-Control-Allow-Origin: https://{$_SERVER['HTTP_HOST']}");
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\FormController;

class PricesControllerUnit extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function getModel($name = 'Unit', $prefix = 'PricesModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function execute($task)
    {
        $item = $this->getModel()->getItem();
        echo new JsonResponse($item);
    }
}