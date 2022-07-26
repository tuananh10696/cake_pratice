<?php
//項目名
$title_name = $_page_config_item['title'] ? $_page_config_item['title'] : 'index_type';
$sub_title_name = $_page_config_item['sub_title'] ? $_page_config_item['sub_title'] : '';

$list = ['0' => '表示しない', '1' => '表示する'];
?>

<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>
    <?= $title_name ?>
    <?= $sub_title_name ?>
  </td>
  <td>
    <?= $this->Form->input('index_type', ['type' => 'radio', 'options' => $list]); ?>
  </td>
</tr>
<?php else: ?>

<tr>
  <td>
    <?= $title_name ?>
    <?= $sub_title_name ?>
  </td>
  <td>

    <?php
    $value = $this->request->data['index_type'] ?? '0';
    echo $this->Form->hidden('index_type', array('value' => $value));
    echo nl2br(h($list[$value] ?? ''));
     ?>

  </td>
</tr>

<?php endif;
