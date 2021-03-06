<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

class PricesHelper
{
	public function addSubmenu($vName)
	{
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRICES_MENU_ITEMS'), 'index.php?option=com_prices&view=items', $vName === 'items');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRICES_MENU_PRICES'), 'index.php?option=com_prices&view=prices', $vName === 'prices');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRICES_MENU_WATCHERS'), 'index.php?option=com_prices&view=watchers', $vName === 'watchers');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRICES_MENU_SECTIONS'), 'index.php?option=com_prices&view=sections', $vName === 'sections');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRICES_MENU_UNITS'), 'index.php?option=com_prices&view=units', $vName === 'units');
        PrjHelper::addActiveProjectFilter();
	}

    /**
     * Проверяет необходимость перезагрузить страницу. Используется для возврата на предыдущую страницу при отправке формы в админке
     * @throws Exception
     * @since 1.0.4
     */
    public static function check_refresh(): void
    {
        $refresh = JFactory::getApplication()->input->getBool('refresh', false);
        if ($refresh) {
            $current = JUri::getInstance(self::getCurrentUrl());
            $current->delVar('refresh');
            JFactory::getApplication()->redirect($current);
        }
    }

    /**
     * Возвращает параметр ID из реферера
     * @since 1.0.1
     * @return int ID Элемента
     */
    public static function getItemID(): int
    {
        $uri = JUri::getInstance($_SERVER['HTTP_REFERER']);
        return (int) $uri->getVar('id') ?? 0;
    }

    /**
     * Возвращает URL для обработки формы
     * @return string
     * @since 1.0.0
     * @throws
     */
    public static function getActionUrl(): string
    {
        $uri = JUri::getInstance();
        $uri->setVar('refresh', '1');
        $query = $uri->getQuery();
        $client = (!JFactory::getApplication()->isClient('administrator')) ? 'site' : 'administrator';
        return JRoute::link($client, "index.php?{$query}");
    }

    /**
     * Возвращает текущий URL
     * @return string
     * @since 1.0.0
     * @throws
     */
    public static function getCurrentUrl(): string
    {
        $uri = JUri::getInstance();
        $query = $uri->getQuery();
        return "index.php?{$query}";
    }

    /**
     * Возвращает URL для возврата (текущий адрес страницы)
     * @return string
     * @since 1.0.0
     */
    public static function getReturnUrl(): string
    {
        $uri = JUri::getInstance();
        $query = $uri->getQuery();
        return base64_encode("index.php?{$query}");
    }

    /**
     * Возвращает URL для обработки формы левой панели
     * @return string
     * @since 1.0.0
     */
    public static function getSidebarAction():string
    {
        $return = self::getReturnUrl();
        return JRoute::_("index.php?return={$return}");
    }

    public static function canDo(string $action): bool
    {
        return JFactory::getUser()->authorise($action, 'com_companies');
    }

    public static function getConfig(string $param, $default = null)
    {
        $config = JComponentHelper::getParams("com_companies");
        return $config->get($param, $default);
    }
}
