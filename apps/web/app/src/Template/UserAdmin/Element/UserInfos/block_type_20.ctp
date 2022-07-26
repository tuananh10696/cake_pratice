<tr id="block_no_<?= h($rownum); ?>" data-sub-block-move="1"
  class="first-dir">
  <td>
    <div class="sort_handle"></div>
    <?= $this->Form->input("info_contents.{$rownum}.id", ['type' => 'hidden', 'value' => h($content['id']), 'id' => 'idBlockId_' . h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.position", ['type' => 'hidden', 'value' => h($content['position'])]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.block_type", ['type' => 'hidden', 'value' => h($content['block_type']), 'class' => 'block_type']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image_pos", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_size", ['type' => 'hidden', 'value' => '0']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_name", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.section_sequence_id", ['type' => 'hidden', 'value' => h($content['section_sequence_id']), 'class' => 'section_no']); ?>
    <?= $this->Form->input("info_contents.{$rownum}._block_no", ['type' => 'hidden', 'value' => h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value3", ['type' => 'hidden', 'value' => '']); ?>
  </td>
  <td class="head"><?= (h($rownum) + 1); ?>.リンクボタン(丸)</td>
  <td>
    <dl>
      <dt>１．ボタン名</dt>
      <dd>
        <?= $this->Form->input("info_contents.{$rownum}.title", ['type' => 'text', 'style' => 'width: 100%;', 'maxlength' => 20, ]); ?>
        <div>※２０文字以内</div>
      </dd>

      <dt style="margin-top: 10px;">２．リンク先
        <?= $this->Form->input("info_contents.{$rownum}.option_value2", ['type' => 'select',
            'options' => $link_target_list,
            'value' => $content['option_value2']
        ]); ?>

      </dt>
      <dd>
        <?= $this->Form->input("info_contents.{$rownum}.content", ['type' => 'text', 'style' => 'width: 100%;', 'maxlength' => 255, 'placeholder' => 'http://']); ?>
      </dd>

      <?php if (false): ?>
      <dt>３．ボタンの背景色</dt>
      <dd>
        <?= $this->Form->input("info_contents.{$rownum}.option_value", ['type' => 'select',
            'options' => $button_color_list,
            'empty' => ['' => '指定なし'],
            'value' => $content['option_value'],
            'escape' => false,
        ]); ?>　
      </dd>
      <?php endif; ?>

    </dl>

  </td>
  <td>
    <div class='btn_area' style='float: right;'>
      <a href="javascript:void(0);" class="btn_confirm small_btn btn_list_delete size_min"
        data-row="<?= h($rownum);?>"
        style='text-align:center; width:auto;'>削除</a>
    </div>
  </td>
</tr>