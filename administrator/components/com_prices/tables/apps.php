<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePricesApps extends Table
{
    var $id = null;
    var $title = null;
    var $weight = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_price_apps', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}