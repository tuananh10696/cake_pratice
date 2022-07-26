<?php $is_required = ($append['is_required'] == 1); ?>

<tr id="append_block-<?= $append['slug'] ?>">
  <td>
    <?= h($append['name']);?>
    <?= $is_required ? '<span class="attent">※必須</span>' : '';?>
  </td>

  <td class="edit_image_area">

    <?php if ($this->Common->isUserRole($append->editable_role)): ?>

    <!-- <td> -->
    <?= $this->Form->input("info_append_items.{$num}.id", ['type' => 'hidden', 'value' => empty($entity['info_append_items'][$num]['id']) ? '' : $entity['info_append_items'][$num]['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.append_item_id", ['type' => 'hidden', 'value' => $append['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.is_required", ['type' => 'hidden', 'value' => $append['is_required']]);?>
    <?= $this->Form->input("info_append_items.{$num}.value_date", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_datetime", ['type' => 'hidden', 'value' => '0000-00-00']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_time", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_decimal", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.file", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.file_size", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.file_extension", ['type' => 'hidden', 'value' => '']);?>
    <?php $image_column = 'image'; ?>

    <dt>１．回り込み位置</dt>
    <dd>
      <?= $this->Form->input("info_append_items.{$num}.value_text", ['type' => 'radio',
          // 'value' => h($content['image_pos']),
          'options' => ['left' => '<img src="/user/common/images/cms/align_left.gif">', 'right' => '<img src="/user/common/images/cms/align_right.gif">'],
          'separator' => '　',
          'escape' => false,
          'default' => 'left'
      ]); ?>
    </dd>

    <dt>２．画像</dt>
    <ul>
      <?php if (!empty($entity['info_append_items'][$num]['attaches'][$image_column]['0'])) :?>
      <li>
        <a href="<?= $entity['info_append_items'][$num]['attaches'][$image_column]['0'];?>"
          class="pop_image_single">
          <img
            src="<?= $this->Url->build($entity['info_append_items'][$num]['attaches'][$image_column]['0'])?>"
            style="width: 300px;">
          <?= $this->Form->input("info_append_items.{$num}.attaches.{$image_column}.0", ['type' => 'hidden']); ?>
        </a><br>

        <?php $old = $entity['info_append_items'][$num]['_old_' . $image_column] ?? ($entity['info_append_items'][$num][$image_column] ?? ''); ?>
        <?= $this->Form->input("info_append_items.{$num}._old_{$image_column}", array('type' => 'hidden', 'value' => h($old))); ?>

        <?php if (!$is_required): ?>
        <div class="btn_area" style="width: 300px;">
          <a href="javascript:kakunin('画像を削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'append_delete', $data['id'], $entity['info_append_items'][$num]['id'] ?? 0, 'image', $image_column, '?' => $query)) ?>')"
            class="btn_delete">画像の削除</a>
        </div>
        <?php endif; ?>
      </li>
      <?php endif;?>

      <li>
        <?= $this->Form->input("info_append_items.{$num}.{$image_column}", array('type' => 'file', 'accept' => 'image/jpeg,image/png,image/gif', 'class' => 'attaches'));?>
        <div class="remark">※jpeg , jpg , gif , png ファイルのみ</div>
        <div>※ファイルサイズ５MB以内</div>
        <?php if ($append['attention']): ?>
        <div><?= $append['attention'] ?>
        </div>
        <?php endif; ?>

        <?= $this->Form->error("{$slug}.{$append['slug']}") ?>
        <br />
      </li>
    </ul>


    <dt>３．内容</dt>
    <dd>
      <?= $this->Form->input("info_append_items.{$num}.value_textarea", ['type' => 'textarea', 'maxlength' => '1000', 'placeholder' => '本文']); ?>
    </dd>

    <?php else: ?>

    <?php $data = $this->request->data['info_append_items'][$num] ?? []; ?>

    <dt>２．画像</dt>
    <ul>
      <?php if (!empty($entity['info_append_items'][$num]['attaches'][$image_column]['0'])) :?>
      <li>
        <a href="<?= $entity['info_append_items'][$num]['attaches'][$image_column]['0'];?>"
          class="pop_image_single">
          <img
            src="<?= $this->Url->build($entity['info_append_items'][$num]['attaches'][$image_column]['0'])?>"
            style="width: 300px;">
        </a><br>
      </li>
      <?php endif;?>
    </ul>


    <dt>３．内容</dt>
    <dd>
      <?= h($data['value_textarea'] ?? '') ?>
    </dd>

    <?php endif; ?>

  </td>
</tr>