<?php
//項目名
$title_name = $_page_config_item['title'] ? $_page_config_item['title'] : 'タイトル';
$sub_title_name = $_page_config_item['sub_title'] ? $_page_config_item['sub_title'] : '';
?>

<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>
    <?= $title_name ?>
    <span class="attent">※必須</span>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <?= $this->Form->input('title', array('type' => 'text', 'maxlength' => 100, 'style' => 'width:100%;'));?>
    <br><span>※100文字以内で入力してください</span>
  </td>
</tr>
<?php else: ?>
<tr>
  <td>
    <?= $title_name ?>
    <span class="attent">※必須</span>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <?php
    $value = $this->request->data['title'] ?? '_';
    echo $this->Form->hidden('title', array('value' => $value));
    echo nl2br(h($value));
     ?>
  </td>
</tr>
<?php endif;
