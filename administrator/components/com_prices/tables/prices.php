<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePricesPrices extends Table
{
    var $id = null;
    var $title = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_prices', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}