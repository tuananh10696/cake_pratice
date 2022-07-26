<?php
$list = ['0' => '利用しない', '1' => '利用する'];

$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>目次機能</td>
  <td>
    <?= $this->Form->input('view_table_content', ['type' => 'radio', 'options' => $list, 'default' => 0]); ?>
  </td>
</tr>
<?php else: ?>

<tr>
  <td>目次機能</td>
  <td>

    <?php
    $value = $this->request->data['view_table_content'] ?? '0';
    echo $this->Form->hidden('view_table_content', array('value' => $value));
    echo nl2br(h($list[$value] ?? ''));
     ?>

  </td>
</tr>

<?php endif;
