<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePricesItems extends Table
{
    var $id = null;
    var $sectionID = null;
    var $appID = null;
    var $unit_1_ID = null;
    var $unit_2_ID = null;
    var $type = null;
    var $square_type = null;
    var $price_rub = null;
    var $price_usd = null;
    var $price_eur = null;
    var $column_1 = null;
    var $column_2 = null;
    var $column_3 = null;
    var $title = null;
    var $title_en = null;
    var $disabled = null;
    var $available = null;
    var $need_period = null;
    var $weight = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_price_items', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}