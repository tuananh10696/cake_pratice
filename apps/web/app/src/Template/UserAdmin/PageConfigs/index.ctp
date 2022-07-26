<div class="title_area">
  <h1>コンテンツ設定</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><span>コンテンツ設定 </span></li>
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
    <?php if ($this->Common->isUserRole('admin')): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit')); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif; ?>

    <?= $this->element('pagination')?>

    <div class="table_area">
      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 70px;">
          <col>
          <col style="width: 200px">
          <!-- <col style="width: 150px"> -->
          <?php if ($this->Common->getCategoryEnabled()): ?>
          <col style="width: 90px;">
          <?php endif; ?>

          <?php if ($this->Common->isUserRole('develop')): ?>
          <col style="width: 90px;">
          <col style="width: 90px;">
          <col style="width: 90px;">
          <?php endif; ?>

          <?php if ($this->Common->isUserRole('admin')): ?>
          <col style="width: 150px">
          <?php endif; ?>
        </colgroup>

        <tr>
          <th>#</th>
          <th style="text-align:left;">ページ名</th>
          <th>表示場所</th>
          <!-- <th>一覧表示タイプ</th> -->
          <?php if ($this->Common->getCategoryEnabled()): ?>
          <th>カテゴリ</th>
          <?php endif; ?>
          <?php if ($this->Common->isUserRole('develop')): ?>
          <th>項目設定</th>
          <th>追加項目</th>
          <th>拡張リンク</th>
          <?php endif; ?>

          <?php if ($this->Common->isUserRole('admin')): ?>
          <th>順序の変更</th>
          <?php endif; ?>
        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;
$scripturl = '';
$status = true;

$preview_url = '/' . $this->Common->session_read('data.username') . "/{$data->id}?preview=on";
?>
        <a name="m_<?= $id ?>"></a>
        <tr
          class="<?= $status ? 'visible' : 'unvisible' ?>"
          id="content-<?= $data->id ?>">

          <td title="">
            <?= $data->id?>
          </td>

          <td>
            <?= $this->Html->link($data->page_title, ['action' => 'edit', $data->id], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <td>
            <?php if ((int)$data->root_dir_type === 1): ?>
            <a href="/<?= $site_config->slug; ?>/"
              target="_blank"><?= $site_config->slug; ?>/</a>
            <?php else: ?>
            <a href="/<?= $data->slug; ?>/" target="_blank">/<?= $data->slug; ?>/</a>
            <?php endif; ?>
          </td>

          <!-- <td>
                <?= $list_style_list[$data->list_style]; ?>
          </td> -->

          <?php if ($this->Common->getCategoryEnabled()): ?>
          <td>
            <?php if ($data->is_category == 'Y'): ?>
            <div class="btn_area">
              <a href="<?= $this->Url->build(array('controller' => 'categories', '?' => ['sch_page_id' => $data->id])); ?>"
                class="btn btn-warning btn-sm">カテゴリ</a>
            </div>
            <?php else: ?>
            ---
            <?php endif; ?>
          </td>
          <?php endif; ?>

          <?php if ($this->Common->isUserRole('develop')): ?>
          <td>
            <div class="btn_area">
              <a href="<?= $this->Url->build(array('controller' => 'page-config-items', 'action' => 'index', 'page_id' => $data->id));?>"
                class="btn btn-success btn-sm">項目設定</a>
            </div>
          </td>

          <td>
            <div class="btn_area">
              <a href="<?= $this->Url->build(array('controller' => 'append-items', 'action' => 'index', 'page_id' => $data->id));?>"
                class="btn btn-success btn-sm">追加項目</a>
            </div>
          </td>

          <td>
            <div class="btn_area">
              <a href="<?= $this->Url->build(array('controller' => 'page-config-extensions', 'action' => 'index', 'page_id' => $data->id));?>"
                class="btn btn-success btn-sm">拡張</a>
            </div>
          </td>
          <?php endif; ?>


          <?php if ($this->Common->isUserRole('admin')): ?>
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
          <?php endif; ?>

        </tr>

        <?php endforeach; ?>

      </table>

    </div>
    <?php if ($this->Common->isUserRole('admin')): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit')); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif; ?>

    <?= $this->element('pagination')?>

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
