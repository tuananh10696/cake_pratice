<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>

<?php if ($page_config->is_public_date): ?>
<tr>
  <td>掲載期間<span class="attent">※必須</span></td>
  <td>
    <?= $this->Form->input('start_datetime', array('type' => 'text', 'class' => 'datetimepicker', 'data-auto-date' => '1', 'style' => 'width: 180px;', 'default' => date('Y-m-d'), 'error' => false));?>
    ～
    <?= $this->Form->input('end_datetime', array('type' => 'text', 'class' => 'datetimepicker', 'data-auto-date' => '1', 'style' => 'width: 180px;', 'error' => false));?>
    <div>※開始日のみ必須。終了日を省略した場合は下書きにするまで掲載されます。</div>

    <?= $this->Form->error('start_datetime') ?>
  </td>
</tr>
<?php else: ?>
<tr>
  <td>掲載日<span class="attent">※必須</span></td>
  <td>
    <?= $this->Form->input('start_datetime', array('type' => 'text', 'class' => 'datepicker', 'data-auto-date' => '1', 'default' => date('Y-m-d'), 'style' => 'width: 120px;','readonly'=>'readonly' ));?>

    <?= $this->Form->error('start_datetime') ?>
  </td>
</tr>
<?php endif; ?>

<?php else: //編集不可?>
<tr>
  <td>掲載日<span class="attent">※必須</span></td>
  <td>

    <?php
    $value = $this->request->data['start_datetime'] ?? date('Y-m-d');
    $value2 = $this->request->data['end_datetime'] ?? '';

    if (isset($this->request->data['start_datetime'])) {
        $value = $value->format('Y-m-d');
    }
    if (isset($this->request->data['end_datetime'])) {
        $value2 = $value2->format('Y-m-d');
    }

    echo $this->Form->hidden('start_datetime', array('value' => $value));
    if ($page_config->is_public_date) {
        echo $this->Form->hidden('end_datetime', array('value' => $value2));
        echo nl2br(h($value)) . ' ~ ' . nl2br(h($value2));
    } else {
        echo nl2br(h($value));
    }
     ?>

  </td>
</tr>
<?php endif;
