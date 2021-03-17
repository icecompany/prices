<?php
defined('_JEXEC') or die;
header("Access-Control-Allow-Origin: https://{$_SERVER['HTTP_HOST']}");
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\FormController;

class PricesControllerItem extends FormController {
    public function getModel($name = 'Item', $prefix = 'PricesModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function execute($task)
    {
        $item = $this->getModel()->getItem();
        echo new JsonResponse($item);
    }
}
