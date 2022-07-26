<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>画像注釈</td>
  <td>
    <?= $this->Form->input('image_title', ['type' => 'textarea', 'maxlength' => 200]) ?>
    <br><span>※200文字以内で入力してください</span>
    <br><span>※改行は反映されません</span>
  </td>
</tr>
<?php else: ?>
<tr>
  <td>画像注釈</td>
  <td>
    <?php
    $value = $this->request->data['image_title'] ?? '';
    echo $this->Form->hidden('image_title', array('value' => $value));
    echo nl2br(h($value));
     ?>
  </td>
</tr>
<?php endif;
