<div class="title_area">
  <h1>お問い合わせ</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><span>お問い合わせ</span></li>
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
    <h3>検索条件</h3>
    <div class="table_area form_area">
      <?= $this->Form->create(false, ['type' => 'get', 'id' => 'fm_search', 'style' => 'display:inline-block;']); ?>
      <table class="vertical_table ">
        <tr>
          <td>期間</td>
          <td>
            <?= $this->Form->input('sch_start_date', array('type' => 'text', 'class' => 'datepicker', 'style' => 'width: 120px;border: 1px solid #aec5d9;', 'onChange' => 'change_category();', 'value' => $query['sch_start_date'], 'readOnly' => true));?>
            ~
            <?= $this->Form->input('sch_end_date', array('type' => 'text', 'class' => 'datepicker', 'style' => 'width: 120px;border: 1px solid #aec5d9;', 'onChange' => 'change_category();', 'value' => $query['sch_end_date'], 'readOnly' => true));?>
          </td>
        </tr>
      </table>
      <?= $this->Form->end(); ?>
    </div>

    <div class="btn_area">
      <a href="<?= $this->Url->build(['action' => 'export', '?' => $query]); ?>"
        class="btn btn-warning rounded-pill mr-1">CSVエクスポート</a>
    </div>

  </div>

  <div class="box">

    <h3 class="box__caption--count"><span>登録一覧</span><span class="count"><?php echo $count['total']; ?>件の登録</span></h3>

    <?php if (true): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => $query)); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif; ?>

    <?= $this->element('pagination')?>

    <div class="table_area">

      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 250px;">
          <col>
        </colgroup>

        <tr>
          <th>応募日</th>
          <th style="text-align:left;">名前</th>
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
?>
        <a name="m_<?= $id ?>"></a>
        <tr
          class="<?= $status ? 'visible' : 'unvisible' ?>"
          id="content-<?= $data->id ?>">

          <td>
            <?= $data->created->format('Y年m月d日 H:i:s') ?>
          </td>

          <td>
            <?= $this->Html->link($data->name, ['action' => 'edit', $data->id, '?' => $query], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>


        </tr>

        <?php endforeach; ?>

      </table>

    </div>

    <?php if (false): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => $query)); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif; ?>

    <?= $this->element('pagination')?>

  </div>
</div>

<?php $this->start('beforeBodyClose');?>
<script src="/user/common/js/jquery.ui.datepicker-ja.js"></script>
<script src="/user/common/js/cms.js"></script>

<link rel="stylesheet" href="/admin/common/css/cms.css">
<script>
  $(function() {
    $(document).keydown(function(event) {
      // クリックされたキーのコード
      var keyCode = event.keyCode;
      if (keyCode == 8) {
        if ($(':focus').hasClass("datepicker")) {
          $(':focus').val("");
          $("#fm_search").submit();
        }
      }

      if (keyCode == 13) {
        //return false;
      }
    });
  })

  function change_category() {
    $("#fm_search").submit();

  }
  $(function() {



  })
</script>
<?php $this->end();
