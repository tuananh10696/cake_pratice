<tr id="block_no_<?= h($rownum); ?>" data-sub-block-move="1"
  class="first-dir">
  <td>
    <div class="sort_handle"></div>
    <?= $this->Form->input("info_contents.{$rownum}.id", ['type' => 'hidden', 'value' => h($content['id']), 'id' => 'idBlockId_' . h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.position", ['type' => 'hidden', 'value' => h($content['position'])]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.block_type", ['type' => 'hidden', 'value' => h($content['block_type']), 'class' => 'block_type']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.title", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.content", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image_pos", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.section_sequence_id", ['type' => 'hidden', 'value' => h($content['section_sequence_id']), 'class' => 'section_no']); ?>
    <?= $this->Form->input("info_contents.{$rownum}._block_no", ['type' => 'hidden', 'value' => h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value2", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value3", ['type' => 'hidden', 'value' => '']); ?>
  </td>
  <td class="head"><?= (h($rownum) + 1); ?>.ファイル添付</td>
  <td class="field">
    <?php $_column = 'file'; ?>
    <div class="manu">
      <ul>
        <?php if (!empty($content['attaches'][$_column]['0'])) :?>
        <li
          class="<?= h($content['attaches'][$_column]['extention']); ?>">
          <?= $this->Form->input("info_contents.{$rownum}.file_name", ['type' => 'text', 'maxlength' => '50', 'style' => 'width:300px;', 'placeholder' => '添付ファイル']); ?>.<?= h($content['file_extension']); ?>
          <?= $this->Form->input("info_contents.{$rownum}.file_size", ['type' => 'hidden', 'value' => h($content['file_size'])]); ?>
          <div><?= $this->Html->link('ダウンロード', $content['attaches'][$_column]['0'], array('target' => '_blank'))?>
          </div>
        </li>
        <?= $this->Form->input("info_contents.{$rownum}._old_{$_column}", array('type' => 'hidden', 'value' => h($content[$_column]))); ?>
        <?= $this->Form->input("info_contents.{$rownum}.file_extension", ['type' => 'hidden']); ?>
        <?php endif;?>

        <li>
          <?= $this->Form->input("info_contents.{$rownum}.file", array('type' => 'file', 'class' => 'attaches'));?>
          <div class="remark">※PDF、Office(.doc, .docx, .xls, .xlsx)ファイルのみ</div>
          <div>※ファイルサイズ５MB以内</div>
        </li>


      </ul>

    </div>
  </td>

  <td>
    <div class='btn_area' style='float: right;'>
      <a href="javascript:void(0);" class="btn_confirm small_btn btn_list_delete size_min"
        data-row="<?= h($rownum);?>"
        style='text-align:center; width:auto;'>削除</a>
    </div>
  </td>

</tr>