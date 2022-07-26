<?php
//項目名
$title_name = $_page_config_item['title'] ? $_page_config_item['title'] : '概要';
$sub_title_name = $_page_config_item['sub_title'] ? $_page_config_item['sub_title'] : '';
?>

<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>
    <?= $title_name ?>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <?= $this->Form->input('notes', ['type' => 'textarea', 'maxlength' => '1000', 'style' => '']); ?>
    <br><span>※1000文字まで</span>
  </td>
</tr>
<?php else: ?>
<tr>
  <td>
    <?= $title_name ?>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <?php
    $value = $this->request->data['notes'] ?? '';
    echo $this->Form->hidden('notes', array('value' => $value));
    echo nl2br(h($value));
     ?>
  </td>
</tr>
<?php endif;
