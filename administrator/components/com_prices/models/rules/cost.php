<?php
use Joomla\CMS\Form\FormRule;
defined('_JEXEC') or die;

class JFormRuleCost extends FormRule
{
    protected $regex = '^[0-9\.]{0,12}$';
}