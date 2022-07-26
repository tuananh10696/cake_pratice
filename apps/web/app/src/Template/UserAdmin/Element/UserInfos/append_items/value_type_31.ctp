<tr id="append_block-<?= $append['slug'] ?>">
  <td>
    <?= h($append['name']);?>
    <?= ($append['is_required'] == 1) ? '<span class="attent">※必須</span>' : '';?>
  </td>
  <td>

    <?php if ($this->Common->isUserRole($append->editable_role)): ?>

    <?= $this->Form->input("info_append_items.{$num}.id", ['type' => 'hidden', 'value' => empty($entity['info_append_items'][$num]['id']) ? '' : $entity['info_append_items'][$num]['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.append_item_id", ['type' => 'hidden', 'value' => $append['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.is_required", ['type' => 'hidden', 'value' => $append['is_required']]);?>
    <?= $this->Form->input("info_append_items.{$num}.value_text", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_textarea", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_date", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_datetime", ['type' => 'hidden', 'value' => '0000-00-00']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_time", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_int", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_decimal", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.image", ['type' => 'hidden', 'value' => '']);?>

    <?php $_column = 'file'; ?>
    <div class="manu">
      <ul>

        <li>
          <?= $this->Form->input("info_append_items.{$num}.file", array('type' => 'file', 'class' => 'attaches'));?>
          <div class="remark">※PDF、Office(.doc, .docx, .xls, .xlsx)ファイルのみ</div>
          <div>※ファイルサイズ５MB以内</div>
          <?= $this->Html->view($append['attention'], ['before' => '<br><span>', 'after' => '</span>']); ?>
        </li>

        <?php if (!empty($entity['info_append_items'][$num]['attaches'][$_column]['0'])) :?>

        <br>

        <?php
        $file_data = $entity['info_append_items'][$num]['attaches'][$_column];
        ?>
        <li
          class="<?= h($file_data['extention']); ?>">
          <?= $this->Form->input("info_append_items.{$num}.file_name", ['type' => 'hidden']); ?>
          <?= h($entity['info_append_items'][$num]['file_name']); ?>.<?= h($entity['info_append_items'][$num]['file_extension']); ?>
          <?= $this->Form->input("info_append_items.{$num}.file_size", ['type' => 'hidden', 'value' => h($entity['info_append_items'][$num]['file_size'])]); ?>
          <div><?= $this->Html->link('ダウンロード', $file_data['0'], array('target' => '_blank'))?>
          </div>
        </li>
        <?= $this->Form->input("info_append_items.{$num}._old_{$_column}", array('type' => 'hidden', 'value' => h($entity['info_append_items'][$num][$_column]))); ?>
        <?= $this->Form->input("info_append_items.{$num}.file_extension", ['type' => 'hidden']); ?>

        <div class="btn_area" style="width: 300px;">
          <a href="javascript:kakunin('ファイルを削除します。よろしいですか？','<?= $this->Url->build(array('controller' => 'InfoAppendItems', 'action' => 'delete', $data['id'], $entity['info_append_items'][$num]['id'] ?? 0, 'file', $_column, '?' => $query)) ?>')"
            class="btn_delete">ファイルの削除</a>
        </div>
        <?php endif;?>


      </ul>
      <?= $this->Form->error("{$slug}.{$append['slug']}") ?>
    </div>

    <?php else: ?>

    <li
      class="<?= h($file_data['extention']); ?>">
      <?= h($entity['info_append_items'][$num]['file_name']); ?>.<?= h($entity['info_append_items'][$num]['file_extension']); ?>
      <div><?= $this->Html->link('ダウンロード', $file_data['0'], array('target' => '_blank'))?>
      </div>
    </li>

    <?php endif; ?>

  </td>

</tr>