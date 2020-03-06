<?php
use Joomla\CMS\Form\FormRule;
defined('_JEXEC') or die;

class JFormRulePrice extends FormRule
{
    protected $regex = '^[А-Яа-яA-Za-z\s-]{0,255}$';
}