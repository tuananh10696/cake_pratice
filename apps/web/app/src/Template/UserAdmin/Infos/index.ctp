<style>
  .breadcrumb-item+.breadcrumb-item::before {
    /*区切り線の変更*/
    /* content:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E"); */
    font-size: 1.5rem;
    content: '>';
  }
</style>
<div class="title_area">
  <h1><?= h($page_title); ?>
  </h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>

      <?php if ($related_base): ?>
      <li><a
          href="<?= $related_base['url'] ?>"><?= $related_base['name'] ?>編集</a>
      </li>
      <?php endif; ?>

      <li><span><?= h($page_title); ?> </span></li>
    </ul>
  </div>
</div>

<?php
//データの位置まで走査

                    use App\Model\Entity\PageConfigExtension;
                    use App\Model\Entity\PageConfigItem;

$count = array('total' => 0,
    'enable' => 0,
    'disable' => 0);
$count['total'] = $data_query->count();
?>

<?= $this->element('error_message'); ?>

<div class="content_inr">

  <?php if ($this->Common->isCategoryEnabled($page_config)): ?>
  <div class="box">
    <h3>検索条件</h3>
    <div class="table_area form_area">

      <?php $is_editable_category = $this->Common->isUserRole($page_config['category_editable_role']); ?>
      <?php if ($is_editable_category): ?>
      <table class="vertical_table ">
        <a href="<?= $this->Url->build(array('controller' => 'categories', '?' => ['sch_page_id' => $page_config->id])); ?>"
          class="btn btn-warning btn-sm" style="text-decoration: none;">カテゴリ一覧</a>
      </table>
      <?php endif; ?>



      <?php
      $category_name = $page_config['category_name_1'] ?? '';
      $have_under_category = true;
       ?>
      <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
          <?php
          //多階層機能を利用する場合
          $is_category_multilevel = ($page_config->is_category_multilevel == 1);

          $category_level = 0;
          $prev_category_id = 0;

          if ($category_list):
           ?>
          <?php
          foreach ($category_list as $clist):
            $category_level++;

            $selected_category_id = $clist['category']->id ?? 0;

            //まだ下層がある場合
            $have_under_category = $is_category_multilevel && $selected_category_id && (!$page_config->max_multilevel || $category_level < $page_config->max_multilevel);
          //カテゴリー名(都道府県等)
          $category_name = $page_config['category_name_' . $category_level] ?? '';
          ?>
          <li class="breadcrumb-item">
            <?= $this->Form->create(false, ['type' => 'get', 'id' => 'fm_search_' . $selected_category_id, 'style' => 'display:inline-block;']); ?>
            <?= $this->Form->input('sch_page_id', ['type' => 'hidden', 'value' => $sch_page_id]); ?>
            <?php
            if (isset($query['relation_info_id']) && $query['relation_info_id']) {
                echo $this->Form->input('relation_info_id', ['type' => 'hidden', 'value' => $query['relation_info_id']]);
            }
            ?>
            <?= $this->Form->input('sch_category_id', ['type' => 'select',
                'options' => $clist['list'],
                'onChange' => 'change_category("fm_search_' . $selected_category_id . '");',
                'value' => $selected_category_id,
                'empty' => $clist['empty']
            ]); ?>
            <?= $this->Form->end(); ?>
            <span class="btn_area" style="display: inline-block">

              <!-- 編集ボタン -->
              <?php if ($selected_category_id && $is_editable_category): ?>
              <a href="<?= $this->Url->build(array(
                  'controller' => 'categories',
                  'action' => 'edit',
                  $selected_category_id,
                  '?' => ['sch_page_id' => $clist['category']->page_config_id, 'parent_id' => $clist['category']->parent_category_id, 'redirect' => 'infos', 'relation_info_id' => $query['relation_info_id'] ?? 0, ]
              ));?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                title="選択されている<?= $category_name ?>の編集">
                <i class="fas fa-edit"></i>
              </a>
              <?php endif; ?>

              <!-- 追加ボタン -->
              <?php if ($is_editable_category): ?>
              <a href="<?= $this->Url->build(array(
                  'controller' => 'categories',
                  'action' => 'edit',
                  0,
                  '?' => ['sch_page_id' => $page_config->id, 'parent_id' => $prev_category_id, 'redirect' => 'infos', 'relation_info_id' => $query['relation_info_id'] ?? 0, ]
              ));?>" class="btn btn-sm btn-danger" style="margin-left:1px;" data-toggle="tooltip"
                data-placement="top"
                title="<?= $category_name ?>を追加します">
                <i class="fas fa-plus"></i>
              </a>
              <?php endif; ?>

            </span>
          </li>
          <?php
          $prev_category_id = $selected_category_id;
          endforeach;
        endif;
          ?>

          <?php
          if ($have_under_category):

          //カテゴリー名(都道府県等)
          $category_name = $page_config['category_name_' . (intval($category_level) + 1)] ?? '';

          $sch_page_id = $clist['category']->page_config_id ?? ($page_config->id ?? 0);
          $selected_category_id = $selected_category_id ?? 0;
           ?>
          <!-- 下層追加ボタン -->
          <li class="breadcrumb-item">
            <span class="btn_area" style="display: inline-block">
              <a href="<?= $this->Url->build(array(
                  'controller' => 'categories',
                  'action' => 'edit',
                  0,
                  '?' => ['sch_page_id' => $sch_page_id, 'parent_id' => $selected_category_id, 'redirect' => 'infos', 'relation_info_id' => $query['relation_info_id'] ?? 0, ]
              ));?>" class="btn btn-sm btn-warning" style="margin-left:1px;" data-toggle="tooltip"
                data-placement="top"
                title="<?= $category_name ?>を追加します">
                <i class="fas fa-plus"></i> <?= $category_name ?>
              </a>
            </span>
          </li>
          <?php endif; ?>
        </ul>
      </nav>

    </div>
  </div>
  <?php endif; ?>

  <?php if ($allowed_view_data): ?>
  <div class="box">
    <h3 class="box__caption--count"><span><?= h($page_title); ?>
        登録一覧</span><span class="count"><?php echo $numrows; ?>件の登録</span></h3>

    <div class="btn_area" style="margin-top:10px;">
      <?php
      $add_button_query = ['sch_page_id' => $sch_page_id, 'sch_category_id' => $sch_category_id];
    if (isset($query['relation_info_id']) && $query['relation_info_id']) {
        $add_button_query['relation_info_id'] = $query['relation_info_id'];
    }

      ?>

      <?php if ($this->Common->isUserRole($page_config['addable_role'])): ?>
      <a href="<?= $this->Url->build(array('action' => 'edit', '?' => $add_button_query)); ?>"
        class="btn_confirm btn_post">新規登録
      </a>
      <?php endif; ?>

    </div>

    <?= $this->element('pagination')?>

    <div class="table_area">
      <?php if (!empty($page_buttons)): ?>
      <div style="display:flex; justify-content: space-between;">
        <div>
          <?php foreach ($page_buttons['left'] as $ex): ?>
          <a href="<?= $this->Url->build($ex->link); ?>"
            class="btn btn-warning rounded-pill mr-1"><?= $ex->name; ?></a>
          <?php endforeach; ?>
        </div>
        <div class="btn_area" style="margin-top:10px;justify-content:right;margin-bottom:10px !important;">

          <?php if ($page_config->need_infotops): ?>
          <a href="<?= $this->Url->build(['controller' => 'info_tops', 'action' => 'index', '?' => ['slug' => $page_config->slug]]); ?>"
            class="btn btn-primary rounded-pill mr-1"><i class="fas fa-edit"></i> <?= __('トップ(別テーブル)表示一覧'); ?></a>
          <?php endif; ?>

          <?php foreach ($page_buttons['right'] as $ex): ?>
          <a href="<?= $this->Url->build($ex->link); ?>"
            class="btn btn-warning rounded-pill mr-1"><?= $ex->name; ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      <table class="table__list">
        <colgroup>
          <col style="width: 75px;">
          <col style="width: 75px;">
          <col style="width: 135px">
          <col>

          <?php foreach ($list_buttons as $ex): ?>
          <col style="width: 100px">
          <?php endforeach; ?>


          <?php if ($this->Common->isViewPreviewBtn($page_config)): ?>
          <col style="width: 75px;">
          <?php endif; ?>

          <?php if ($this->Common->isViewSort($page_config, $sch_category_id)): ?>
          <col style="width: 150px">
          <?php endif; ?>

        </colgroup>

        <tr>
          <th>掲載</th>
          <th>記事ID</th>
          <th>掲載日</th>
          <th style="text-align:left;">
            <?php
          if ($this->Common->isCategoryEnabled($page_config)) {
              echo "{$category_name}/";
          }
            ?>

            <?php if ($this->Common->enabledInfoItem($page_config->id, PageConfigItem::TYPE_MAIN, 'title')): ?>
            <?= $this->Common->infoItemTitle($page_config->id, PageConfigItem::TYPE_MAIN, 'title', 'title', 'タイトル'); ?>
            <?= $this->Common->infoItemTitle($page_config->id, PageConfigItem::TYPE_MAIN, 'title', 'sub_title', ''); ?>
            <?php endif; ?>

          </th>
          <?php foreach ($list_buttons as $ex): ?>
          <th style="text-align:center;"><?= $ex->name; ?>
          </th>
          <?php endforeach; ?>

          <?php if ($this->Common->isViewPreviewBtn($page_config)): ?>
          <th style="text-align:left;">確認</th>
          <?php endif; ?>

          <?php if ($this->Common->isViewSort($page_config, $sch_category_id)): ?>
          <th>順序の変更</th>
          <?php endif; ?>

        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;
$scripturl = '';
if ($data['status'] === 'publish') {
    $count['enable']++;
    $status = true;
    $status_text = '掲載中';
    $status_class = 'visible';
    $status_btn_class = 'visi';
} else {
    $count['disable']++;
    $status = false;
    $status_text = '下書き';
    $status_class = 'unvisible';
    $status_btn_class = 'unvisi';
}

if ($page_config->is_public_date && $data->status == 'publish') {
    $now = new \DateTime();

    //開始時間まち
    $is_wait = ($data->start_datetime > $now);
    if ($is_wait) {
        // 掲載待ち
        $status_class = 'unvisible';
        $status_text = '掲載待ち';
    }

    //終了時間超えた
    $setted_endtime = ($data->end_datetime && $data->end_datetime != '0000-00-00');
    $is_finished = ($setted_endtime && $data->end_datetime < $now);
    if ($is_finished) {
        // 掲載終了
        $status_class = 'unvisible';
        $status_text = '掲載終了';
    }
}
?>
        <a name="m_<?= $id ?>"></a>
        <tr class="<?= $status_class; ?>"
          id="content-<?= $data->id ?>">
          <td>
            <?= $this->element('status_button', ['status' => $status, 'id' => $data->id, 'class' => 'scroll_pos', 'enable_text' => $status_text, 'disable_text' => $status_text]); ?>
            <!-- <div class="<?= $status_btn_class;?>"> -->
            <?php // $this->Html->link($status_text, array('action' => 'enable', $data->id, '?' => $query), ['class' => 'scroll_pos'] )?>
            <!-- </div> -->
          </td>

          <td>
            <?= $data->id; ?>
          </td>

          <td style="text-align: center;">
            <?= !empty($data->start_datetime) ? $data->start_datetime->format('Y/m/d') : '&nbsp;' ?>
          </td>

          <td>
            <?php if ($this->Common->isCategoryEnabled($page_config)): ?>
            <?php if ($page_config->is_category_multiple == 1): ?>
            <?= $this->Html->view($this->Common->getInfoCategories($data->id, 'names'), ['before' => '【', 'after' => '】<br>']); ?>
            <?php else: ?>
            <?= $this->Html->view((!empty($data->category->jp_name) ? h($data->category->jp_name) : '未設定'), ['before' => '【', 'after' => '】<br>']); ?>
            <?php endif; ?>
            <?php endif; ?>
            <?= $this->Html->link(h($data->title), ['action' => 'edit', $data->id, '?' => $query], ['escape' => false, 'class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <?php foreach ($list_buttons as $ex): ?>
          <td style="text-align: center;">
            <a href="<?= $this->Html->exUrl($ex->link, ['info_id' => $data->id]);?>"
              class="btn btn-success text-white"><?= $ex->name; ?></a>
          </td>
          <?php endforeach; ?>

          <?php if ($this->Common->isViewPreviewBtn($page_config)): ?>
          <td>
            <div class="prev"><a
                href="<?= $data->detailUrl(['preview' => 'on']) ?>"
                target="_blank">プレビュー</a></div>
          </td>
          <?php endif; ?>

          <?php if ($this->Common->isViewSort($page_config, $sch_category_id)): ?>
          <td>
            <ul class="ctrlis">
              <?php if (!$this->Paginator->hasPrev() && $key == 0): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="cttop"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'top', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <li class="ctup"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'up', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <?php endif; ?>

              <?php if (!$this->Paginator->hasNext() && $key == count($datas) - 1): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="ctdown"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'down', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <li class="ctend"><?= $this->Html->link('bottom', array('action' => 'position', $data->id, 'bottom', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <?php endif; ?>
            </ul>
          </td>
          <?php endif; ?>

        </tr>

        <?php endforeach; ?>

      </table>

    </div>

    <div class="btn_area" style="margin-top:10px;">
      <?php
      $add_button_query = ['sch_page_id' => $sch_page_id, 'sch_category_id' => $sch_category_id];
    if (isset($query['relation_info_id']) && $query['relation_info_id']) {
        $add_button_query['relation_info_id'] = $query['relation_info_id'];
    }

      ?>
      <?php if ($this->Common->isUserRole($page_config['addable_role'])): ?>
      <a href="<?= $this->Url->build(array('action' => 'edit', '?' => $add_button_query)); ?>"
        class="btn_confirm btn_post">新規登録
      </a>
      <?php endif; ?>

    </div>

    <?= $this->element('pagination')?>
  </div>
  <?php endif; ?>
</div>
<?php $this->start('beforeBodyClose');?>
<link rel="stylesheet" href="/admin/common/css/cms.css">
<script>
  $(window).on('load', function() {
    $(window).scrollTop(
      "<?= empty($query['pos']) ? 0 : $query['pos'] ?>"
    );
  })

  function change_category(elm) {
    $("#" + elm).submit();

  }
  $(function() {

    $('.scroll_pos').on('click', function() {
      var sc = window.pageYOffset;
      var link = $(this).attr("href");

      window.location.href = link + "&pos=" + sc;


      return false;
    });

    $('[data-toggle="tooltip"]').tooltip();

  })
</script>
<?php $this->end();
