<?php
$list = ['0' => '利用しない', '1' => '利用する'];

$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>

<?php if ($page_config->need_infotops): ?>
<tr>
  <td>トップ表示(別テーブル)</td>
  <td>
    <?= $this->Form->input('is_top', ['type' => 'radio', 'options' => $list]); ?>
  </td>
</tr>
<?php endif; ?>

<?php else: ?>

<tr>
  <td>トップ表示(別テーブル)</td>
  <td>

    <?php
    $value = $this->request->data['is_top'] ?? '0';
    echo $this->Form->hidden('is_top', array('value' => $value));
    echo nl2br(h($list[$value] ?? ''));
     ?>

  </td>
</tr>

<?php endif;
