<div class="title_area">
  <h1>タグ登録リスト</h1>
</div>

<?= $this->element('error_message'); ?>

<div class="content_inr">

  <div class="box">
    <h3 class="box__caption--count"><span>登録一覧</span><span class="count"><?= $data_query->count(); ?>件の登録</span></h3>

    <?= $this->element('pagination')?>

    <div class="table_area">
      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 90px;">
          <col style="width: 100px;">
          <col>
          <col style="width: 100px;">

        </colgroup>

        <tr>
          <th>選択</th>
          <th>表示番号</th>
          <th style="text-align:left;">タグ</th>
          <th>使用数</th>
        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):

$id = $data->id;

$preview_url = '/' . $this->Common->session_read('data.username') . "/{$data->id}?preview=on";
?>
        <a name="m_<?= $id ?>"></a>
        <tr class="visible" id="content-<?= $data->id ?>">

          <td style="padding: 0;padding-left: 10px; text-align: center;">
            <div class="btn_area">
              <a href="#" class="btn_confirm small_menu_btn btn_red"
                onClick="parent.pop_box.select('<?= $data->tag;?>');">選択</a>
            </div>
          </td>

          <td style="padding: 0;padding-left: 10px;">
            <?= $data->position; ?>
          </td>

          <td style="padding: 0;padding-left: 10px;">
            <?= $data->tag; ?>
          </td>

          <td style="padding: 0;padding-right: 10px; text-align: right;">
            <?= $data->cnt; ?>件
          </td>

        </tr>

        <?php endforeach; ?>

      </table>

    </div>


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
