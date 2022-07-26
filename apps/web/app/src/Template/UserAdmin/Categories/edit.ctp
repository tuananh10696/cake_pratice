<?php $this->start('beforeHeaderClose'); ?>

<?php $this->end(); ?>

<?php
//カテゴリー名(エリア等)
$category_level = 'category_name_' . $category_multiple_level;
$category_name = $page_config->{$category_level} ?? '';
$is_opencampus = $page_config->slug == 'opencampus';
?>

<div class="title_area">
  <h1><?= $category_name ?>
  </h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <?php if ($this->Common->isUserRole($site_config['page_editable_role'])) : ?>
        <li><a href="<?= $this->Url->build(['controller' => 'page-configs']); ?>">コンテンツ設定</a>
        </li>
      <?php endif; ?>
      <li><a href="<?= $this->Url->build(['controller' => 'page-configs', 'action' => 'edit', $page_config->id]); ?>"><?= $page_config->page_title; ?></a></li>
      <li><a href="<?= $this->Url->build(['controller' => 'categories', '?' => ['sch_page_id' => $query['sch_page_id']]]); ?>">カテゴリ</a>
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
      <?= $this->Form->create($entity, array('type' => 'file', 'context' => ['validator' => 'default'])); ?>
      <?= $this->Form->input('id', array('type' => 'hidden', 'value' => $entity->id)); ?>
      <?= $this->Form->input('position', array('type' => 'hidden')); ?>
      <?= $this->Form->input('page_config_id', array('type' => 'hidden', 'value' => $query['sch_page_id'])); ?>
      <?= $this->Form->hidden('multiple_level', array('value' => $category_multiple_level)); ?>

      <?php if (empty($parent_category)) : ?>
        <?= $this->Form->input('parent_category_id', ['type' => 'hidden', 'value' => 0]); ?>
      <?php else : ?>
        <?= $this->Form->input('parent_category_id', ['type' => 'hidden', 'value' => $query['parent_id']]); ?>
      <?php endif; ?>

      <table class="vertical_table table__meta">
        <tr>
          <td><?= $category_name ?>名<span class="attent">※必須</span>
          </td>
          <td>
            <?= $this->Form->input('name', array('type' => 'text', 'maxlength' => 20,)); ?>
            <br><span>※20文字以内で入力してください</span>
          </td>
        </tr>
        <?php if ($is_opencampus) : ?>
          <tr>
            <td>Slug<span class="attent">※必須</span>
            </td>
            <td>
              <?= $this->Form->input('value_text', array('type' => 'text', 'maxlength' => 20)); ?>
              <br><span>※20文字以内で入力してください</span>
            </td>
          </tr>
        <?php endif ?>

        <tr>
          <td>有効/無効</td>
          <td>
            <?= $this->Form->input('status', array('type' => 'select', 'options' => array('draft' => '無効', 'publish' => '有効'))); ?>
          </td>
        </tr>

      </table>

      <div class="btn_area">
        <?php if (!empty($data['id']) && $data['id'] > 0) { ?>
          <a href="#" class="btn_confirm submitButton submitButtonPost">変更する</a>
          <a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $data['id'], 'content', '?' => $query)) ?>')" class="btn_delete">削除する</a>
        <?php } else { ?>
          <a href="#" class="btn_confirm submitButton submitButtonPost">登録する</a>
        <?php } ?>
      </div>

      <div id="deleteArea" style="display: hide;"></div>

      <?= $this->Form->end(); ?>

    </div>
  </div>
</div>


<?php $this->start('beforeBodyClose'); ?>
<link rel="stylesheet" href="/user/common/css/cms.css">
<script src="/user/common/js/jquery.ui.datepicker-ja.js"></script>
<script src="/user/common/js/cms.js"></script>

<?php $this->end();
