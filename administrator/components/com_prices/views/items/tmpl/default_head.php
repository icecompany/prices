<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th style="width: 1%;">
        <?php echo JHtml::_('grid.checkall'); ?>
    </th>
    <th style="width: 1%;">
        №
    </th>
    <th style="width: 50%">
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_TITLE', 'i.title', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 7%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_ITEMS_COST', 'i.price_rub', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_ITEMS_SECTION_TITLE', 'section', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_ITEMS_PRICE_TITLE', 'price', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_ITEM_HEAD_STOP', 'i.disabled', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_ITEM_HEAD_WEIGHT', 'i.weight', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 1%;">
        <?php echo JHtml::_('searchtools.sort', 'ID', 'i.id', $listDirn, $listOrder); ?>
    </th>
</tr>
