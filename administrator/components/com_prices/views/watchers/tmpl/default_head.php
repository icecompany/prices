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
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_WATCHERS_ITEM', 'i.title', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 10%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_WATCHERS_PRICE', 'p.title', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 13%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_WATCHERS_MANAGER', 'u.name', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 1%;">
        <?php echo JHtml::_('searchtools.sort', 'ID', 'p.id', $listDirn, $listOrder); ?>
    </th>
</tr>
