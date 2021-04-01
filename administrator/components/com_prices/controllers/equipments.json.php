<?php
defined('_JEXEC') or die;
header("Access-Control-Allow-Origin: https://{$_SERVER['HTTP_HOST']}");
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\FormController;

class PricesControllerEquipments extends FormController {
    public function getModel($name = 'Equipments', $prefix = 'PricesModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function execute($task)
    {
        $item = $this->getModel()->getItems();
        echo new JsonResponse($item);
    }
}
