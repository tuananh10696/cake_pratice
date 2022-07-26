<?php
//項目名
$slug_name = 'スラッグ';
$slug_name = $_page_config_item['slug'] ? $_page_config_item['slug'] : 'スラッグ';
?>

<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>
    <?= $slug_name ?>
  </td>
  <td>
    <?= $this->Form->input('slug', array('type' => 'text', 'maxlength' => 100, 'style' => 'width:100%;'));?>
    <br><span>※100文字以内で入力してください</span>
  </td>
</tr>
<?php else: ?>
<tr>
  <td>
    <?= $slug_name ?>
    <span class="attent">※必須</span>
  </td>
  <td>
    <?php
    $value = $this->request->data['slug'] ?? '_';
    echo $this->Form->hidden('slug', array('value' => $value));
    echo nl2br(h($value));
     ?>
  </td>
</tr>
<?php endif;
