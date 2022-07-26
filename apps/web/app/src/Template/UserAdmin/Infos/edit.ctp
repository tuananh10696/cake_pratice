<?php use App\Model\Entity\PageConfigItem;

?>

<?php $this->start('beforeHeaderClose'); ?>
<link
  href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Noto+Sans+JP:wght@300&family=Noto+Serif+JP&display=swap"
  rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="/user/common/css/info.css">

<style>
  .table__meta td {
    min-width: 300px;
  }
</style>
<?php $this->end(); ?>

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

      <li><a
          href="<?= $this->Url->build(array('action' => 'index', '?' => $query)); ?>"><?= h($page_title); ?></a></li>
      <li><span><?= ($data['id'] > 0) ? '編集' : '新規登録'; ?></span>
      </li>
    </ul>
  </div>
</div>

<?= $this->element('error_message'); ?>

<?php
//ハッシュタグ用
$info_tag_count = $entity->info_tags ?? [];
$info_tag_count = count($info_tag_count);

$hidden_link_button = (($entity->notes ?? '') == 'middle_school');
?>

<div class="content_inr">
  <div class="box">
    <h3><?= ($data['id'] > 0) ? '編集' : '新規登録'; ?>
    </h3>
    <div class="table_area form_area">

      <?php if ($data['id'] && !$hidden_link_button): ?>
      <?php if ($detail_extent_buttons): ?>
      <div class="btn_area btn_area--center" style="padding-bottom: 30px;">
        <?php foreach ($detail_extent_buttons as $k => $ex): ?>
        <?php if ($ex->option_value): ?>
        <a href="<?= $this->Html->exUrl($ex->link, ['school_id' => $data['id']]);?>"
          class="btn_confirm submitButton" style="background-color:#28a745"><?= $ex->name ?></a>
        <?php else: ?>
        <a href="<?= $this->Html->exUrl($ex->link, ['relation_info_id' => $data['id']]);?>"
          class="btn_confirm submitButton" style="background-color:#28a745"><?= $ex->name ?></a>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>


      <?= $this->Form->create($entity, array('type' => 'file', 'context' => ['validator' => 'default'], 'name' => 'fm'));?>
      <?= $this->Form->hidden('position'); ?>
      <?= $this->Form->input('id', array('type' => 'hidden', 'value' => h($entity->id)));?>
      <?= $this->Form->input('page_config_id', ['type' => 'hidden']); ?>
      <?= $this->Form->input('meta_keywords', ['type' => 'hidden']); ?>
      <?= $this->Form->input('notes', ['type' => 'hidden', 'value' => '']); ?>
      <?= $this->Form->input('postMode', ['type' => 'hidden', 'value' => 'save', 'id' => 'idPostMode']); ?>
      <?= $this->Form->hidden('relation_info_id', ['value' => $query['relation_info_id'] ?? 0]); ?>

      <input type="hidden" name="MAX_FILE_SIZE"
        value="<?= (1024 * 1024 * 5); ?>">
      <table class="vertical_table table__meta">

        <tr>
          <td>記事番号</td>
          <td><?= ($data['id']) ? sprintf('No. %04d', h($data['id'])) : '新規' ?>
          </td>
        </tr>

        <?php
        //mainのコンテンツを出力する。
        //page_config_itemsの値を参照して表示切替
        foreach (($page_config_items[PageConfigItem::TYPE_MAIN] ?? []) as $cnt => $_page_config_item) {
            $key = $_page_config_item['item_key'] ?? '';

            //順序を手動で変えるため除外
            $exclude_view_elements = ['status'];
            if (in_array($key, $exclude_view_elements)) {
                continue;
            }
            $is_viewable = $_page_config_item && $this->Common->isUserRole($_page_config_item->viewable_role);
            if ($is_viewable) {
                echo $this->element("UserInfos/items/{$key}", ['num' => $cnt, '_page_config_item' => $_page_config_item, 'page_config' => $page_config]);
            } else {
            }
        }
        ?>

        <?php
        //追加項目の出力
        $n = 0;
        foreach (($append_list ?? []) as $_ => $ap) {
            $is_viewable = $this->Common->isUserRole($ap->viewable_role);
            $ap_list = [];
            if (!empty($ap['use_option_list']) && isset($append_item_list[$ap['use_option_list']])) {
                $ap_list = $append_item_list[$ap['use_option_list']];
            }
            if ($is_viewable) {
                echo $this->element("UserInfos/append_items/value_type_{$ap['value_type']}", ['num' => $n, 'append' => $ap, 'list' => $ap_list, 'slug' => $page_config->slug, 'placeholder_list' => $placeholder_list, 'notes_list' => $notes_list]);
            } else {
            }

            $n++;
        }
        ?>


        <?php
        //page_config_itemsの値を参照して表示切替
        $_page_config_item = $page_config_items[PageConfigItem::TYPE_MAIN]['status'] ?? [];
        $is_viewable = $_page_config_item && $this->Common->isUserRole($_page_config_item->viewable_role);
        $list = array('draft' => '下書き', 'publish' => '掲載する');
        if ($is_viewable):
          $is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
        ?>
        <tr>
          <td>記事表示</td>
          <td>
            <?php
            if ($is_editable) {
                echo $this->Form->input('status', array('type' => 'select', 'options' => $list));
            } else {
                $value = $this->request->data['status'] ?? 'draft';
                echo $this->Form->hidden('status', array('value' => $value));
                echo $list[$value] ?? '';
            }
             ?>
          </td>
        </tr>
        <?php endif; ?>


      </table>

      <table id="blockTable" class="vertical_table block_area table__edit" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 70px;">
          <col style="width: 150px;">
          <col>
          <col style="width: 90px;">
        </colgroup>
        <tbody id="blockArea" class="list_table">
          <?php if (!empty($contents) && array_key_exists('contents', $contents)): ?>
          <?php foreach ($contents['contents'] as $k => $v): ?>
          <?php if ($v['block_type'] != 13): ?>
          <?= $this->element("UserInfos/block_type_{$v['block_type']}", ['rownum' => h($v['_block_no']), 'content' => h($v)]); ?>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>

        <tbody id="recommendBlock">
          <?php if (!empty($contents) && array_key_exists('contents', $contents)): ?>
          <?php foreach ($contents['contents'] as $k => $v): ?>
          <?php if ($v['block_type'] == 13): ?>
          <?= $this->element("UserInfos/block_type_{$v['block_type']}", ['rownum' => h($v['_block_no']), 'content' => h($v)]); ?>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <table class="vertical_table table__block" style="min-width: 960px;">

        <?php $_page_config_item = $page_config_items[PageConfigItem::TYPE_BLOCK]['all'] ?? []; ?>
        <?php if ($_page_config_item): ?>
        <tr>
          <td class="head">
            ブロック追加
            <?= $this->Form->input('content_count', ['type' => 'hidden', 'value' => h($content_count), 'id' => 'idContentCount']); ?>
          </td>
          <td>
            <?php foreach ($block_type_list as $no => $block_list): ?>
            <div class="btn_area"
              style="text-align: left;<?= ($no ? 'padding-top: 5px;' : '')?>">
              <?php foreach ($block_list as $k => $v): ?>
              <a href="#" class="btn_confirm btn_orange small_menu_btn"
                onClick="addBlock(<?= h($k); ?>); return false;"><?= h($v); ?></a>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
          </td>
        </tr>

        <?php endif;?>

        <?php $is_view_all_block = ($page_config_items[PageConfigItem::TYPE_BLOCK]['all'] ?? []) && ($page_config_items[PageConfigItem::TYPE_SECTION]['all'] ?? []); ?>
        <?php if ($is_view_all_block): ?>
        <tr>
          <td class="head">
            枠ブロック追加
          </td>
          <td>
            <div class="btn_area" style="text-align: left;">
              <?php foreach ($block_type_waku_list as $k => $v): ?>
              <a href="#" class="btn_confirm btn_orange small_menu_btn"
                onClick="addBlock(<?= h($k); ?>); return false;"><?= h($v); ?></a>
              <?php endforeach; ?>
            </div>
          </td>
        </tr>
        <?php endif;?>

      </table>

      <div id="blockWork"></div>

      <div class="btn_area btn_area--center" id="editBtnBlock">
        <?php if (!empty($data['id']) && $data['id'] > 0) { ?>
        <a href="#" class="btn_confirm submitButton" id="btnSave">変更する</a>

        <?php if ($this->Common->isUserRole($page_config['deletable_role'])): ?>

        <?php
        $newQuery = [];
        if (isset($query['relation_info_id']) && $query['relation_info_id']) {
            $newQuery['relation_info_id'] = $query['relation_info_id'];
        }
          $url = $this->Url->build(array('action' => 'delete', $data['id'], 'content', '?' => $newQuery));
           ?>
        <a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $url ?>')"
          class="btn_delete">削除する</a>
        <?php endif; ?>

        <?php } else { ?>
        <a href="#" class="btn_confirm submitButton" id="btnSave">登録する</a>
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

<!-- redactor -->
<link rel="stylesheet" href="/user/common/css/redactor/redactor.min.css">
<!-- <link rel="stylesheet" href="/user/common/css/redactor/inlinestyle.css"> -->
<script src="/user/common/js/redactor/redactor-custom-min.js"></script>
<!-- redactor plugins -->
<script src="/user/common/js/redactor/ja.js"></script>
<script src="/user/common/js/redactor/alignment.js"></script>
<script src="/user/common/js/redactor/counter.js"></script>
<script src="/user/common/js/redactor/fontcolor.js"></script>
<script src="/user/common/js/redactor/fontsize.js"></script>
<!-- <script src="/user/common/js/redactor/inlinestyle-ja.js"></script> -->
<!-- <script src="/user/common/js/ckeditor/ckeditor.js"></script> -->
<script src="/user/common/js/ckeditor/crt_ckeditor.js"></script>
<script src="/user/common/js/ckeditor/translations/ja.js"></script>


<?= $this->Html->script('/user/common/js/system/pop_box'); ?>

<!-- redactor -->
<link rel="stylesheet" href="/user/common/css/redactor/redactor.min.css">
<script src="/user/common/js/redactor/redactor.min.js"></script>
<!-- redactor plugins -->
<script src="/user/common/js/redactor/ja.js"></script>
<script src="/user/common/js/redactor/alignment.js"></script>
<script src="/user/common/js/redactor/counter.js"></script>
<script src="/user/common/js/redactor/fontcolor.js"></script>
<script src="/user/common/js/redactor/imagemanager.js"></script>

<!-- 郵便局自動入力 -->
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMjwGC-7WpCqlp5frhz-9F6BKoGRHck7s"></script>
<script>
  $('.zip').keyup(function(e) {
    if ($(this).val().length >= 7) {
      getAddress($(this));

    }
  });

  //googlemapのaのurlを変更する。
  // $('.get_map_url').click(function(e) {
  //   var area = $(this).parent().parent();
  //   var urlElement = area.find(".map_url");
  //   var address = area.find(".address").val();

  //   if (address) {
  //     var url = "https://maps.google.co.jp/maps?output=embed&q=" + address;
  //     urlElement.val(url);
  //   } else {
  //     urlElement.val("");
  //   }
  // });

  function getAddress(zipElement) {
    var zip = zipElement.val();
    var addressElement = zipElement.parent().parent().find(".address");

    new google.maps.Geocoder().geocode({
        address: zip
      },
      function(result, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          var components = result[0].address_components;
          if (components.length == 5) {
            addressElement.val(components[3].long_name + components[2].long_name + components[1].long_name);
          } else if (components.length == 6) {
            addressElement.val(components[4].long_name + components[3].long_name + components[2].long_name +
              components[1].long_name);
          }
        }
      }
    );
  }
</script>

<script>
  var rownum = 0;
  var tag_num = <?= $info_tag_count; ?> ;
  var max_row = 100;
  var pop_box = new PopBox();
  var out_waku_list = <?= json_encode($out_waku_list); ?> ;
  var block_type_waku_list = <?= json_encode($block_type_waku_list); ?> ;
  var block_type_relation = 14;
  var block_type_relation_count = 0;
  var max_file_size = <?= (1024 * 1024 * 5); ?> ;
  var total_max_size = <?= (1024 * 1024 * 30); ?> ;
  var form_file_size = 0;
  var page_config_id = <?= $page_config->id; ?> ;
</script>

<script>
  function changeTargetType() {
    var type = $('#append_block-target_type td [type="radio"]:checked').val();
    if (type == 1) { // PDF
      $("#append_block-link").hide();
      $("#append_block-file").show();
    } else {
      $("#append_block-link").show();
      $("#append_block-file").hide();
    }
  }
  $(function() {
    <?php if (false): //radioによる表示変更?>
    changeTargetType();
    $('#append_block-target_type td [type="radio"]').on('change', function() {
      changeTargetType();
    });
    <?php endif; ?>

    var custom_uploader = wp.media({
      title: 'Choose Image',
      library: {
        type: 'image'
      },
      button: {
        text: 'Choose Image'
      },
      multiple: false
    });

    var slug = null;

    $(".content_inr").on("click", " .media-upload", function(e) {
      slug = $(this).data('slug');
      e.preventDefault();
      custom_uploader.open();
    });

    custom_uploader.on("select", function() {
      var images = custom_uploader.state().get('selection');

      images.each(function(file) {
        $("#append_block_image_" + slug + " .image-url").val(file.toJSON().url);

        $("#append_block_image_" + slug + " .image-view-block").html(
          '<img class="image-view" src="" width="260">');

        $("#append_block_image_" + slug + " .image-view").attr("src", file.toJSON().url);
      });
    });
  });
</script>

<!-- detetimepicker admin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
  integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
  crossorigin="anonymous"></script>
<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
  integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
  crossorigin="anonymous" />

<script>
  // detetimepicker
  $(function() {
    $.datetimepicker.setLocale('ja');
    $('.datetimepicker').datetimepicker({
      lang: 'ja',
      scrollMonth: false,
      scrollInput: false
    });
  });

  $(document).ready(function() {
    $(document).keydown(function(event) {
      // クリックされたキーのコード
      var keyCode = event.keyCode;
      if (keyCode == 8) {
        if ($(':focus').hasClass("datetimepicker")) {
          //$(':focus').val("");
        }
      }

      if (keyCode == 13) {
        //return false;
      }
    });
  });
</script>

<?= $this->Html->script('/user/common/js/info/edit'); ?>

<?php $this->end();
