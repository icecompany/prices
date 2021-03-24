<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePricesEquipments extends Table
{
    var $id = null;
    var $type = null;
    var $title = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_price_equipments', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}