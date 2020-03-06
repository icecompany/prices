<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePricesWatchers extends Table
{
    var $id = null;
    var $itemID = null;
    var $userID = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_price_watchers', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}