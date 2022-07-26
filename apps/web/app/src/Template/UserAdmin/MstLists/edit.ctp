<?php $this->start('beforeHeaderClose'); ?>

<?php $this->end(); ?>

<div class="title_area">
  <h1>リスト登録</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['action' => 'index', '?' => $query]); ?>">一覧</a>
      </li>
      <li><span><?= ($data['id'] > 0) ? '編集' : '新規登録'; ?></span>
      </li>
    </ul>
  </div>
</div>

<?= $this->element('error_message'); ?>
<div class="content_inr">
  <div class="box">
    <h3><?= ($data['id'] > 0) ? '編集' : '新規登録'; ?>
    </h3>
    <div class="table_area form_area">
      <?= $this->Form->create($entity, array('type' => 'file', 'context' => ['validator' => 'default']));?>
      <?= $this->Form->hidden('position'); ?>
      <?= $this->Form->input('id', array('type' => 'hidden', 'value' => $entity->id));?>
      <?php
$l_cd = $query['list_code'];
if (!empty($data['sys_cd'])) {
    $l_cd = $data['sys_cd'];
}
?>
      <?= $this->Form->input('sys_cd', array('type' => 'hidden', 'value' => $l_cd));?>
      <table class="vertical_table table__meta">

        <tr>
          <td>リスト番号・リスト名</td>
          <td>

            <?php if ((!$this->Common->isUserRole('develop'))): ?>
            <?= h($data['list_name'] ?? '') ?>
            <?= $this->Form->input('use_target_id', ['type' => 'hidden', 'style' => 'width:100px;', 'error' => false]) ?>
            <?= $this->Form->input('list_name', ['type' => 'hidden', 'error' => false]) ?>
            <?= $this->Form->input('list_slug', ['type' => 'hidden', 'error' => false]) ?>
            <?php else:?>
            <?= h($data['use_target_id'] ?? '') ?>・
            <?= $this->Form->input('use_target_id', ['type' => 'hidden', 'style' => 'width:100px;', 'error' => false]) ?>
            <?= $this->Form->input('list_name', ['type' => 'text', 'maxlength' => 20, 'style' => 'width:250px;', 'error' => false]) ?>

            <br><br>
            リストスラッグ:
            <?= $this->Form->input('list_slug', ['type' => 'text', 'maxlength' => 20, 'style' => 'width:250px;', 'error' => false]) ?>
            <?php endif;?>

            <?= $this->Form->error('use_target_id') ?>
            <?= $this->Form->error('list_name') ?>
          </td>
        </tr>

        <?php if ($this->Common->isUserRole('develop')): ?>
        <tr>
          <td>名称<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('ltrl_nm', ['type' => 'text', 'maxlength' => 40]) ?>
            <br><span>※40文字以内で入力してください</span>
          </td>
        </tr>

        <tr>
          <td>値<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('ltrl_val', ['type' => 'text', 'maxlength' => 10]) ?>
            <br><span>※半角数字で入力してください</span>
          </td>
        </tr>

        <?php else:?>

        <tr>
          <td>予備キー</td>
          <td>
            <?= $this->Form->input('ltrl_slug', ['type' => 'text', 'maxlength' => 40]) ?>
            <br><span>※40文字以内で入力してください</span>
            <br><span>※半角英字で入力してください</span>
          </td>
        </tr>
        <?= $this->Form->input('ltrl_val', ['type' => 'hidden']) ?>
        <?php endif;?>

        <tr>
          <td>識別子</td>
          <td>
            <?= $this->Form->input('ltrl_slug', ['type' => 'text', 'maxlength' => 40]) ?>
            <br><span>※半角数字で入力してください</span>
          </td>
        </tr>

        <tr>
          <td>
            オプション
          </td>

          <td>
            <?= $this->Form->input('option_value1', ['type' => 'text', 'maxlength' => 40, 'placeholder' => '']); ?>
            <br><span>例) 追加項目の場合はtype指定</span><br>

            <?= $this->Form->input('option_value2', ['type' => 'text', 'maxlength' => 40, 'placeholder' => '']); ?>
            <br><span>例) 追加項目の場合はplaceholder指定</span><br>

            <?= $this->Form->input('option_value3', ['type' => 'text', 'maxlength' => 40, 'placeholder' => '']); ?>
            <br><span>例) 追加項目の場合はmaxlenght指定</span><br>

            <?= $this->Form->input('option_value4', ['type' => 'text', 'maxlength' => 40, 'placeholder' => '']); ?>
            <br><span>例) 追加項目の場合はdefault指定</span>
          </td>

        </tr>


      </table>

      <div class="btn_area btn_area--center">
        <?php if (!empty($data['id']) && $data['id'] > 0) { ?>
        <a href="#" class="btn_confirm submitButton submitButtonPost">変更する</a>
        <a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $data['id'], 'content', '?' => $query))?>')"
          class="btn_delete">削除する</a>
        <?php } else { ?>
        <a href="#" class="btn_confirm submitButton submitButtonPost">登録する</a>
        <?php } ?>
      </div>

      <div id="deleteArea" style="display: hide;"></div>

      <?= $this->Form->end();?>

    </div>
  </div>
</div>


<?php $this->start('beforeBodyClose');?>
<link rel="stylesheet" href="/user/common/css/cms.css">
<script src="/user/common/js/jquery.ui.datepicker-ja.js"></script>
<script src="/user/common/js/cms.js"></script>

<?php $this->end();
