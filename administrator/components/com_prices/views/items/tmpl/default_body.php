<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = $this->state->get('list.start', 0);
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['edit_link'];?>
        </td>
        <td>
            <?php echo $item['price_rub'];?>
        </td>
        <td>
            <?php echo $item['available'];?>
        </td>
        <td>
            <?php echo $item['section'];?>
        </td>
        <td>
            <?php echo $item['price'];?>
        </td>
        <td>
            <?php echo $item['disabled'];?>
        </td>
        <td>
            <?php echo $item['weight'];?>
        </td>
        <td>
            <?php echo $item['id'];?>
        </td>
    </tr>
<?php endforeach; ?>