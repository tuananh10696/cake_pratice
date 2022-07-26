<?php $this->start('beforeHeaderClose'); ?>

<?php $this->end(); ?>

<div class="title_area">
  <h1>追加項目</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['action' => 'index', '?' => ['page_id' => $page_config->id]]); ?>">追加項目</a>
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
      <?= $this->Form->input('id', array('type' => 'hidden', 'value' => $entity->id));?>
      <?= $this->Form->input('page_config_id', array('type' => 'hidden', 'value' => $page_config->id));?>
      <?= $this->Form->input('position', array('type' => 'hidden'));?>
      <table class="vertical_table table__meta">

        <tr>
          <td>追加項目名(name)<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('name', array('type' => 'text', 'maxlength' => 100, ));?>
            <br><span>※入力してください</span>
          </td>
        </tr>

        <tr>
          <td>追加項目名(slug)<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('slug', array('type' => 'text', 'maxlength' => 100, ));?>
            <br><span>※英数字で入力してください</span>
          </td>
        </tr>

        <tr>
          <td>追加項目の注意事項</td>
          <td>
            <?= $this->Form->input('attention', ['type' => 'text']); ?>
          </td>
        </tr>

        <tr>
          <td>データ型<span class="attent">※必須</span></td>
          <td>

            <?= $this->Form->input('is_required', ['type' => 'checkbox', 'value' => '1', 'hiddenField' => true, 'label' => '必須']); ?>

            <br>
            形式：
            <?= $this->Form->select('value_type', $value_type_list); ?>
            <br>
            <span>※関連記事はslugで指定します。</span>

          </td>
        </tr>

        <tr>
          <td>オプション</td>
          <td>
            文字数制限：
            <?= $this->Form->input('max_length', ['type' => 'text', 'maxlength' => 5, 'style' => 'width:100px;']); ?>
            <br>

            <br>
            プレースホルダー：
            <?= $this->Form->input('value_placeholder', ['type' => 'textarea', 'maxlength' => 100, 'style' => 'width:300px;height:36px;']); ?>
            <br>

            <br>
            初期値：
            <?= $this->Form->input('value_default', ['type' => 'textarea', 'maxlength' => 100, 'style' => 'width:300px;height:36px;']); ?>
            <br>

            <br>
            使用リスト番号：
            <?= $this->Form->select('use_option_list', $target_list, ['empty' => ['0' => '--']]) ?>
            <br>

            <br>
            閲覧権限：
            <?= $this->Form->input('viewable_role', ['type' => 'select', 'options' => $editable_role_list, 'default' => 'staff']); ?>
            <br>

            <br>
            編集権限：
            <?= $this->Form->input('editable_role', ['type' => 'select', 'options' => $editable_role_list, 'default' => 'staff']); ?>

          </td>
        </tr>



      </table>

      <div class="btn_area btn_area--center">
        <?php if (!empty($data['id']) && $data['id'] > 0) { ?>
        <a href="#" class="btn_confirm submitButton submitButtonPost btn_post">変更する</a>
        <?php if ($this->Common->isUserRole('admin')): ?>
        <a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $data['id'], 'content', '?' => $query))?>')"
          class="btn_delete">削除する</a>
        <?php endif; ?>
        <?php } else { ?>
        <a href="#" class="btn_confirm  submitButtonPost btn_post">登録する</a>
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

<script>
  function changeHome() {
    var is_home = $("#idIsHome").prop('checked');
    if (is_home) {
      $("#idSlug").val('');
      $("#idSlug").prop('readonly', true);
      $("#idSlug").css('backgroundColor', "#e9e9e9");
    } else {
      $("#idSlug").prop('readonly', false);
      $("#idSlug").css('backgroundColor', "#ffffff");
    }
  }

  function changeSlug() {
    var slug = $("#idSlug").val();
    if (slug != "") {
      $("#idIsHome").prop('checked', false);
    }
  }

  $(function() {

    changeHome();

    $("#idIsHome").on('change', function() {
      changeHome();
    });

    $("#idSlug").on('change', function() {
      changeSlug();
    });
  })
</script>
<?php $this->end();
