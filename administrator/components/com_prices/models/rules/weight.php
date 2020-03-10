<?php
use Joomla\CMS\Form\FormRule;
defined('_JEXEC') or die;

class JFormRuleWeight extends FormRule
{
    protected $regex = '^[0-9]{1,4}$';

    public function test(\SimpleXMLElement $element, $value, $group = null, \Joomla\Registry\Registry $input = null, \Joomla\CMS\Form\Form $form = null)
    {
        if ((int) $value < 0 || (int) $value > 4095) return false;
        return parent::test($element, $value, $group, $input, $form);
    }
}