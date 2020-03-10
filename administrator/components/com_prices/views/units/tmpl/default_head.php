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
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_TITLE', 'u.title', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRICES_HEAD_WEIGHT', 'u.weight', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 1%;">
        <?php echo JHtml::_('searchtools.sort', 'ID', 'u.id', $listDirn, $listOrder); ?>
    </th>
</tr>
