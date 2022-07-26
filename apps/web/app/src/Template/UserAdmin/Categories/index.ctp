<div class="title_area">
  <h1>「<?= $page_config->page_title; ?>」のカテゴリ</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>

      <?php if ($this->Common->isUserRole($site_config['page_editable_role'])): ?>
      <li><a
          href="<?= $this->Url->build(['controller' => 'page-configs']); ?>">コンテンツ設定</a>
      </li>
      <?php endif; ?>


      <li>
        <a
          href="<?= $this->Url->build(['controller' => 'page-configs', 'action' => 'edit', $page_config->id]); ?>"><?= $page_config->page_title; ?></a>
      </li>
      <li><span>カテゴリ</span></li>
    </ul>
  </div>
</div>

<?php
//データの位置まで走査
$count = array('total' => 0,
    'enable' => 0,
    'disable' => 0);
$count['total'] = $data_query->count();
?>

<?= $this->element('error_message'); ?>

<div class="content_inr">

  <div class="box">
    <h3 class="box__caption--count"><span>登録一覧</span><span class="count"><?php echo $count['total']; ?>件の登録</span></h3>

    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => $query)); ?>"
        class="btn_confirm btn_post">新規登録</a></div>



    <div class="table_area">
      <?php if ($page_config->is_category_multilevel == 1 && $page_config->max_multilevel != 1): ?>
      <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a
              href="<?= $this->Url->build(['action' => 'index', '?' => ['sch_page_id' => $query['sch_page_id']]]); ?>">ルート</a>
          </li>
          <?php $parent_categories = $parent_category; ?>
          <?php while ($pcat = array_pop($parent_categories)): ?>
          <?php if ($query['parent_id'] == $pcat->id): ?>
          <li class="breadcrumb-item active"><?= $pcat->jp_name; ?>
          </li>
          <?php else: ?>
          <li class="breadcrumb-item"><a
              href="<?= $this->Url->build(['action' => 'index', '?' => ['sch_page_id' => $query['sch_page_id'], 'parent_id' => $pcat->id]]); ?>"><?= $pcat->name; ?></a></li>
          <?php endif; ?>
          <?php endwhile; ?>
        </ul>
      </nav>
      <?php endif; ?>

      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 135px;">
          <col style="width: 100px;">
          <col>

          <?php if ($page_config->is_category == 'Y' && $page_config->is_category_multilevel == 1 && $page_config->max_multilevel > $category_multiple_level): ?>
          <col style="width: 170px;">
          <?php endif; ?>
          <col style="width: 150px;">

        </colgroup>

        <tr>
          <th>掲載</th>
          <th>表示番号</th>
          <th style="text-align:left;">カテゴリ名</th>
          <?php if ($page_config->is_category == 'Y' && $page_config->is_category_multilevel == 1 && $page_config->max_multilevel > $category_multiple_level): ?>
          <th>操作</th>
          <?php endif; ?>
          <th>並び順</th>
        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;
$scripturl = '';
if ($data['status'] === 'publish') {
    $count['enable']++;
    $status = true;
} else {
    $count['disable']++;
    $status = false;
}

$preview_url = '/' . $this->Common->session_read('data.username') . "/{$data->id}?preview=on";
?>
        <a name="m_<?= $id ?>"></a>
        <tr
          class="<?= $status ? 'visible' : 'unvisible' ?>"
          id="content-<?= $data->id ?>">

          <td>
            <div
              class="<?= $status ? 'visi' : 'unvisi' ?>">
              <?= $this->Html->link(($status ? '掲載中' : '下書き'), array('action' => 'enable', $data->id))?>
            </div>
          </td>

          <td title="">
            <?= $data->position?>
          </td>

          <td>
            <?= $this->Html->link($data->jp_name, ['action' => 'edit', $data->id, '?' => $query], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <?php if ($page_config->is_category == 'Y' && $page_config->is_category_multilevel == 1 && $page_config->max_multilevel > $category_multiple_level): ?>
          <td>
            <a href="<?= $this->Url->build(array('controller' => 'categories', 'action' => 'index', 'sch_page_id' => $query['sch_page_id'], 'parent_id' => $data->id));?>"
              class="btn btn-success text-white">下層カテゴリ</a>
          </td>
          <?php endif; ?>

          <td>
            <ul class="ctrlis">
              <?php if (!$this->Paginator->hasPrev() && $key == 0): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="cttop"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'top'))?>
              </li>
              <li class="ctup"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'up'))?>
              </li>
              <?php endif; ?>

              <?php if (!$this->Paginator->hasNext() && $key == count($datas) - 1): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="ctdown"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'down'))?>
              </li>
              <li class="ctend"><?= $this->Html->link('bottom', array('action' => 'position', $data->id, 'bottom'))?>
              </li>
              <?php endif; ?>
            </ul>
          </td>


        </tr>

        <?php endforeach; ?>

      </table>

    </div>

    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => $query)); ?>"
        class="btn_confirm btn_post">新規登録</a></div>


  </div>
</div>
<?php $this->start('beforeBodyClose');?>
<link rel="stylesheet" href="/admin/common/css/cms.css">
<script>
  function change_category() {
    $("#fm_search").submit();

  }
  $(function() {



  })
</script>
<?php $this->end();
