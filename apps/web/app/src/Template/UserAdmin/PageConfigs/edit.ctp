<?php $this->start('beforeHeaderClose'); ?>

<?php $this->end(); ?>

<div class="title_area">
  <h1>コンテンツ設定</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['action' => 'index']); ?>">コンテンツ設定</a>
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
      <?= $this->Form->input('header', array('type' => 'hidden', 'value' => ""));?>
      <?= $this->Form->input('footer', array('type' => 'hidden', 'value' => ""));?>
      <?= $this->Form->input('id', array('type' => 'hidden', 'value' => $entity->id));?>
      <?= $this->Form->input('site_config_id', array('type' => 'hidden', 'value' => $site_config->id));?>
      <table class="vertical_table table__meta">

        <tr>
          <td>ページタイトル<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('page_title', array('type' => 'text', 'maxlength' => 100, ));?>
            <br><span>※100文字以内で入力してください</span>
          </td>
        </tr>

        <tr>
          <td>スラッグ<span class="attent">※必須</span></td>
          <td>
            <div class="home_block">
              <?= $this->Form->input('slug', array('type' => 'text', 'maxlength' => 40, 'style' => 'width:300px;', 'id' => '_idSlug'));?>
              <br><span>※40文字以内で入力してください</span>
            </div>
          </td>
        </tr>

        <tr>
          <td>表示場所</td>
          <td>
            <?php if ($this->Common->isUserRole($user_roles['develop'])): ?>
            <?= $this->Form->input('root_dir_type', ['type' => 'checkbox', 'value' => 1, 'label' => 'トップページ', 'id' => 'idIsHome', 'hiddenField' => true]); ?>
            <?php else: ?>
            <?= $this->Form->input('root_dir_type', ['type' => 'hidden']); ?>
            <?php endif; ?>

            <br>
            一覧画面
            <div class="home_block">
              /<?= $this->Form->input('index_url', array('type' => 'text', 'maxlength' => 100, 'style' => 'width:300px;', 'placeholder' => 'info'));?>/
            </div>

            <br>
            詳細画面
            <div class="home_block">
              /<?= $this->Form->input('detail_url', array('type' => 'text', 'maxlength' => 100, 'style' => 'width:300px;', 'placeholder' => 'info/{id}'));?>/?〇〇=〇〇
            </div>

            <br>
            <span>※入力されていない場合はスラッグがURLになります。</span>
          </td>
        </tr>

        <tr>
          <td>新規追加権限</td>
          <td>
            <?= $this->Form->input('addable_role', ['type' => 'select',
                'options' => $editable_role_list
            ]); ?>
          </td>
        </tr>

        <tr>
          <td>編集権限</td>
          <td>
            <?= $this->Form->input('editable_role', ['type' => 'select',
                'options' => $editable_role_list
            ]); ?>
          </td>
        </tr>

        <tr>
          <td>削除権限</td>
          <td>
            <?= $this->Form->input('deletable_role', ['type' => 'select',
                'options' => $editable_role_list
            ]); ?>
          </td>
        </tr>

        <?php if (false): ?>
        <tr>
          <td>デザインテンプレート</td>
          <td>
            <?= $this->Form->input('page_template_id', ['type' => 'select',
                'options' => $template_list,
            ]); ?>
          </td>
        </tr>
        <?php endif; ?>

        <tr>
          <td>一覧の表示タイプ</td>
          <td>
            <?= $this->Form->input('list_style', ['type' => 'select',
                'options' => $list_style_list,
            ]); ?>
          </td>
        </tr>

        <?php if (true): ?>
        <tr>
          <td>自動公開機能</td>
          <td>
            <?= $this->Form->input('is_public_date', ['type' => 'radio',
                'options' => [
                    '0' => '使わない',
                    '1' => '使う'
                ]]); ?>
          </td>
        </tr>
        <?php endif; ?>

        <tr>
          <td>meta 概要</td>
          <td>
            <?= $this->Form->input('description', ['type' => 'text', 'style' => 'width:100%;height:80px;']); ?><br>
            <div>
              ※200文字以内
            </div>
          </td>
        </tr>

        <tr>
          <td>meta キーワード</td>
          <td>
            <?= $this->Form->input('keywords', ['type' => 'text', 'style' => 'width:100%;height:80px;']); ?><br>
            <div>
              ※200文字以内 単語をカンマ区切りで入力。10単語以内が最適。
            </div>
          </td>
        </tr>

        <tr>
          <td>トップ表示機能(別テーブルに保存)</td>
          <td>
            <?= $this->Form->input('need_infotops', ['type' => 'radio', 'options' => ['0' => '使用しない', '1' => '使用する']]); ?>
          </td>
        </tr>

        <?php if ($this->Common->getCategoryEnabled()): ?>
        <tr>
          <td>カテゴリ機能</td>
          <td>
            <?php if ($this->Common->isUserRole($user_roles['develop'])): ?>
            <?= $this->Form->input('is_category', ['type' => 'radio',
                'options' => ['N' => '使用しない', 'Y' => '使用する']
            ]); ?>
            <?php else: ?>
            <?= ($data['is_category'] == 'Y' ? '使用する' : '使用しない'); ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>

        <?php if ($this->Common->getCategoryEnabled()): ?>

        <tr>
          <td>カテゴリ名</td>
          <td>
            1階層目: <?= $this->Form->input('category_name_1', ['type' => 'text', 'maxlength' => '20', 'style' => 'width: 400px;', 'default' => 'カテゴリー']); ?>
            <?= $this->Form->input('category_is_need_identifier_1', [
                'type' => 'checkbox',
                'value' => '1',
                'label' => '識別子を設定する',
                'hiddenField' => true
            ]);?>
            <br>
            2階層目: <?= $this->Form->input('category_name_2', ['type' => 'text', 'maxlength' => '20', 'style' => 'width: 400px;', 'default' => 'カテゴリー']); ?>
            <?= $this->Form->input('category_is_need_identifier_2', [
                'type' => 'checkbox',
                'value' => '1',
                'label' => '識別子を設定する',
                'hiddenField' => true
            ]);?>
            <br>
            3階層目: <?= $this->Form->input('category_name_3', ['type' => 'text', 'maxlength' => '20', 'style' => 'width: 400px;', 'default' => 'カテゴリー']); ?>
            <?= $this->Form->input('category_is_need_identifier_3', [
                'type' => 'checkbox',
                'value' => '1',
                'label' => '識別子を設定する',
                'hiddenField' => true
            ]);?>
          </td>
        </tr>
        <?php endif; ?>

        <?php if ($this->Common->getCategoryEnabled()): ?>
        <tr>
          <td>カテゴリ複数選択機能</td>
          <td>
            <?php if ($this->Common->isUserRole($user_roles['develop'])): ?>
            <?= $this->Form->input('is_category_multiple', ['type' => 'radio',
                'options' => ['0' => '使用しない', '1' => '使用する']
            ]); ?>
            <?php else: ?>
            <?= ($data['is_category_multiple'] == '1' ? '使用する' : '使用しない'); ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>

        <?php if ($this->Common->getCategoryEnabled()): ?>
        <tr>
          <td>カテゴリの多階層</td>
          <td>
            <?php if ($this->Common->isUserRole($user_roles['develop'])): ?>
            <?= $this->Form->input('is_category_multilevel', ['type' => 'radio',
                'options' => ['0' => '使用しない', '1' => '使用する']
            ]); ?>
            最大階層：<?= $this->Form->input('max_multilevel', ['type' => 'number', 'style' => 'width: 60px;']); ?>
            0:制限なし
            <?php else: ?>
            <?= ($data['is_category_multilevel'] == '1' ? '使用する' : '使用しない'); ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>

        <?php if ($this->Common->getCategorySortEnabled()): ?>

        <tr>
          <td>管理画面上カテゴリに「すべて」の追加</td>
          <td>
            <?= $this->Form->input('need_all_category_select', ['type' => 'radio',
                'options' => ['0' => '使用しない', '1' => '使用する']
            ]); ?>
          </td>
        </tr>

        <tr>
          <td>カテゴリごとに並び替える</td>
          <td>
            <?php if ($this->Common->isUserRole($user_roles['develop'])): ?>
            <?= $this->Form->input('is_category_sort', ['type' => 'radio',
                'options' => ['N' => '使用しない', 'Y' => '使用する']
            ]); ?>
            <?php else: ?>
            <?= ($data['is_category_sort'] == 'Y' ? '使用する' : '使用しない'); ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>

        <?php if ($this->Common->getCategoryEnabled()): ?>
        <tr>
          <td>カテゴリの編集権限</td>
          <td>
            <?= $this->Form->input('category_editable_role', ['type' => 'select',
                'options' => $editable_role_list
            ]); ?>
          </td>
        </tr>
        <?php endif; ?>

        <tr>
          <td>リンクカラー</td>
          <td>
            <?= $this->Form->input('link_color', ['type' => 'color', 'style' => 'height: 30px;']); ?>
          </td>
        </tr>

        <tr>
          <td class="head m-0 p-2 text-center" colspan="2">
            <div class="w-100 btn-light">
              <span>▼▼▼ callback ▼▼▼</span>
            </div>
          </td>
        </tr>

        <tr>
          <td>保存前</td>
          <td>
            <?= $this->Form->input('before_save_callback', ['type' => 'text']); ?>
          </td>
        </tr>

        <tr>
          <td>保存後</td>
          <td>
            <?= $this->Form->input('after_save_callback', ['type' => 'text']); ?>
          </td>
        </tr>

        <tr>
          <td>ステータス変更後</td>
          <td>
            <?= $this->Form->input('after_enable_callback', ['type' => 'text']); ?>
          </td>
        </tr>

        <tr>
          <td>並び順</td>
          <td>
            <?= $this->Form->input('ad_find_order_callback', ['type' => 'text']); ?>
          </td>
        </tr>

        <tr>
          <td class="head m-0 p-2 text-center" colspan="2">
            <div class="w-100 btn-light">
              <span>▼▼▼ 管理画面一覧の機能 ▼▼▼</span>
            </div>
          </td>
        </tr>

        <tr>
          <td>並び順</td>
          <td>
            <?= $this->Form->input('disable_position_order', ['type' => 'checkbox', 'value' => 1, 'label' => '並び替の表示をしない', 'hiddenField' => true]); ?>
          </td>
        </tr>

        <tr>
          <td>プレビューボタン</td>
          <td>
            <?= $this->Form->input('disable_preview', ['type' => 'checkbox', 'value' => 1, 'label' => 'プレビューボタンを非表示にする', 'hiddenField' => true]); ?>
          </td>
        </tr>


      </table>

      <div class="btn_area">
        <?php if (!empty($data['id']) && $data['id'] > 0) { ?>
        <a href="#" class="btn_confirm submitButton submitButtonPost">変更する</a>
        <?php if ($this->Common->isUserRole('admin')): ?>
        <a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $data['id'], 'content'))?>')"
          class="btn_delete">削除する</a>
        <?php endif; ?>
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
