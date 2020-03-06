<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePricesSections extends Table
{
    var $id = null;
    var $priceID = null;
    var $title = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_price_sections', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}