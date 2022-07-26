var adOffset, adSize, winH;
$(window).on('load resize', function () {
  adOffset = $('#blockWork').offset().top;
  winH = $(window).height();
});

var sortable_option = {
  items: 'tr.first-dir',
  placeholder: 'ui-state-highlight',
  opacity: 0.7,
  handle: 'td div.sort_handle',
  connectWith: '.list_table_sub',
  update: function (e, obj) {

  }
};

var sortable_option_sub = {
  items: 'tr.first-dir',
  placeholder: 'ui-state-highlight',
  opacity: 0.7,
  handle: 'td div.sort_handle',
  connectWith: '.list_table, .list_table_sub',
  receive: function (e, obj) {
    var waku_block_type = $(this).data('wakuBlockType');
    var section_no = $(this).closest('table').data('sectionNo');
    Object.keys(out_waku_list).forEach(function (k) {
      if (waku_block_type == k) {
        for (let i in out_waku_list[k]) {
          if ($(obj.item['0']).find('input.block_type').val() == out_waku_list[k][i]) {
            if (obj.sender !== null) {
              $(obj.sender[0]).sortable('cancel');
            }
            return false;
          }
        }
      }
    });
  },
  update: function (e, obj) {
    var section_no = $(obj.item['0'].closest('table')).data('sectionNo');
    if (typeof section_no !== 'undefined') {
      var waku_block_type = $(obj.item['0'].closest('table')).data('blockType');
      $(obj.item['0']).find('input.section_no').val(section_no);
    } else if (obj.sender == null) {
      $(obj.item['0']).find('input.section_no').val(0);
    }
  }
};

var sortable_option_relation = {
  items: 'tr.relation-dir',
  placeholder: 'ui-state-highlight',
  opacity: 0.7,
  handle: 'td div.sort_handle',
  receive: function (e, obj) {
    var waku_block_type = $(this).data('wakuBlockType');
    var section_no = $(this).closest('table').data('sectionNo');
    Object.keys(out_waku_list).forEach(function (k) {
      if (waku_block_type == k) {
        for (let i in out_waku_list[k]) {
          if ($(obj.item['0']).find('input.block_type').val() == out_waku_list[k][i]) {
            if (obj.sender !== null) {
              $(obj.sender[0]).sortable('cancel');
            }
            return false;
          }
        }
      }
    });
  },
  update: function (e, obj) {

  }
};

function addTag(tag) {
  var url = '/user_admin/infos/add_tag';
  var params = {
    'num': tag_num,
    'tag': tag
  };

  $.post(url, params, function (a) {
    $("#tagArea").append(a);
    tag_num++;
  });
}

function addBlock(type) {
  var url = '/user_admin/infos/add_row';
  var params = {
    'rownum': rownum,
    'block_type': type
  };

  if (rownum >= max_row) {
    alert_dlg(`追加できるブロックは${max_row}件までです。`);
    return;
  }
  $.post(url, params, function (a) {
    $("#blockArea").append(a);
    if (type == 2 || type == 11) {
      var elm = `#block_no_${rownum} textarea.editor`;
      setWysiwyg(elm);
    }

    if (type == 15) {
      setWysiwygOLD();
    }

    if (type == 13) {
      $(`#block_no_${rownum} .list_table_relation`).sortable(sortable_option_relation);
    }
    else if (type in block_type_waku_list !== false) {
      $(`#block_no_${rownum} .list_table_sub`).sortable(sortable_option_sub);
    }

    adOffset = $('#blockWork').offset().top;
    winH = $(window).height();

    rownum++;
  });
}

// 関連記事枠の関連記事　専用
function addBlockRelation(waku_no, section_no) {
  var type = block_type_relation;
  var url = '/user_admin/infos/add_row';
  var params = {
    'rownum': rownum,
    'block_type': type,
    'section_no': section_no
  };

  if (rownum >= max_row) {
    alert_dlg(`追加できるブロックは${max_row}件までです。`);
    return;
  }
  $.post(url, params, function (a) {
    $(`#block_no_${waku_no} .list_table_relation`).append(a);

    rownum++;
  });
}

function setWysiwygOLD() {
  $R('.old_editor', {
    focus: true,
    minHeight: '300px',
    // imageFigure: false,
    imageUpload: '/v1/redactor-images/upload/' + "infos" + '.json',
    // imageManagerJson: '/admin/news_images/image_list.json',
    imagePosition: true,
    imageResizable: true,
    multipleUpload: false,
    plugins: ['format', 'italic', 'lists', 'fontsize', 'fontcolor', 'counter', 'alignment', 'imagemanager'],
    formatting: ['p', 'h1', 'h2', 'h3', 'h4', 'h5'],
    buttonsHide: [],
    lang: 'ja',
    pastePlainText: true,
    //breakline: true,
    //markup: 'br' 
    // toolbarExternal: '#external-toolbar',
  });
}

function setWysiwyg(elm) {

  ClassicEditor
    .create(document.querySelector(elm), {
      'alignment': {
        options: [
          { name: 'left', className: 'text-left' },
          { name: 'center', className: 'text-center' },

          { name: 'right', className: 'text-right' },
        ]
      },
      // removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed', "HtmlEmbed"],
      toolbar: {
        items: [
          // 'heading',
          // 'italic',
          // 'numberedList',
          // 'imageUpload',
          // '|',
          'bold', 'underline', 'strikethrough', '|',
          'fontColor', 'fontBackgroundColor', '|',

          'link', 'bulletedList', 'alignment', '|',
          'insertTable', 'MediaEmbed', "HtmlEmbed", '|',
          'undo', 'redo',
        ]
      },
      mediaEmbed: {
        previewsInData: true
      },
      language: "jp"

      // fontColor: {
      //   colors: [
      //     {
      //       color:'rgb(255,0,0)',
      //       label: '赤'
      //     }
      //   ]
      // }
      // toolbar: ['headings', 'bold', 'link']

      // toolbar: {items: ['fontSize']}
      // heading: {
      //   options: [
      //     { modelElement: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
      //     { modelElement: 'heading1', viewElement: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
      //     { modelElement: 'heading2', viewElement: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
      //   ]
      // }
    })
    .catch(error => {
      // console.log(elm);
      console.error(error);
    });

  return;
}

function changeStyle(elm, rownum, target_class, target_name) {
  var class_name = $(elm).val();

  $(`#block_no_${rownum} .${target_class}`).removeClass(function (index, className) {
    var match = new RegExp("\\b" + target_name + "\\S+", "g");
    return (className.match(match) || []).join(' ');
  });

  if (class_name != "") {
    if (class_name.match(/^[0-9]+$/)) {
      class_name = target_name + class_name;
    }
    $(`#block_no_${rownum} .${target_class}`).addClass(class_name);
  }

  var type = $(elm).closest('tbody.list_table_sub').data('wakuBlockType');
  if (type in block_type_waku_list !== false) {
    if (class_name == 'waku_style_6') {
      $(`#block_no_${rownum} .optionValue3`).attr('disabled', true);
      $(`#block_no_${rownum} .optionValue3`).val('');
    } else {
      // $(`#block_no_${rownum} .optionValue2`).attr('disabled', false);
      $(`#block_no_${rownum} .optionValue3`).attr('disabled', false);
    }
  }
}

function changeSelectStyle(elm, rownum) {
  var style = $(elm).val();
  // console.log(style);
  if (style == 'waku_style_6') {
    $("#idWakuColorCol_" + rownum).hide();
    $("#idWakuColorCol_" + rownum + ' select').attr("disabled", true);
    $("#idWakuBgColorCol_" + rownum).show();
    $("#idWakuBgColorCol_" + rownum + ' select').attr("disabled", false);
  } else {
    $("#idWakuBgColorCol_" + rownum).hide();
    $("#idWakuBgColorCol_" + rownum + ' select').attr("disabled", true);
    $("#idWakuColorCol_" + rownum).show();
    $("#idWakuColorCol_" + rownum + ' select').attr("disabled", false);
  }
}

function changeWidth(elm, rownum, target_class, name) {
  var num = $(elm).val();

  if (num > 0) {
    $(`#block_no_${rownum} .${target_class}`).css(name, `${num}px`);
  } else {
    $(`#block_no_${rownum} .${target_class}`).css(name, ``);
    num = '';
    $(elm).val(num);
  }
}
function getFileSize(e) {

  var file = e.files[0];

  if (typeof file === 'undefined') {
    return 0;
  }
  if (file.length == 0) {
    return 0;
  }

  var reader = new FileReader();

  if (file.size > max_file_size) {
    alert_dlg('１ファイルのアップロード出来る容量を超えています');
    $(e).val('');
    return 0;
  }

  return file.size;
}
$(function () {
  rownum = $("#idContentCount").val();

  // 保存、削除ボタンの固定化
  $(window).scroll(function () {
    if ($(this).scrollTop() > adOffset - winH) {
      $("#editBtnBlock").removeClass('fixed-bottom');
      $("#editBtnBlock").removeClass('pb-3');
    } else {

      $("#editBtnBlock").addClass('fixed-bottom');
      $("#editBtnBlock").addClass('pb-3');
    }
  });

  $("body").on('change', '.attaches', function () {
    //       var id = $(this).attr('id');
    // console.log(id);
    //       var ele = document.getElementById(id);
    // console.log(ele);
    //       var size = getFileSize(document.getElementById(id));
    //       if (size > max_file_size) {
    //         alert_dlg('１ファイルのアップロード出来る容量を超えています');
    //         $(this).val('');
    //       }

    var attaches = document.getElementsByClassName('attaches');
    form_file_size = 0;
    for (var i = 0; i < attaches.length; i++) {
      form_file_size += getFileSize(attaches[i]);
    }

    if (form_file_size > total_max_size) {
      $(this).val('');
      alert_dlg('一度にアップ出来る容量を超えました。一度保存してください');
      return;
    }
    // console.log(form_file_size);
  });



  // 並び替え
  $(".list_table").sortable(sortable_option);
  $(`.list_table_sub`).sortable(sortable_option_sub);
  $(`.list_table_relation`).sortable(sortable_option_relation);


  // ブロック削除
  $('body #blockArea').on('click', '.btn_list_delete', function () {
    var row = $(this).data("row");
    var block_id = $(`#idBlockId_${row}`).val();

    $(`#block_no_${row} input, #block_no_${row} textarea, #block_no_${row} select`).attr('disabled', true);
    $(`#block_no_${row}`).addClass('delete');
    $(this).html('元に戻す');
    $(this).removeClass('btn_list_delete');
    $(this).addClass('btn_list_undo');


    if (block_id > 0) {
      var html = `<input type="hidden" name="delete_ids[]" value="${block_id}" id="delBlock_${block_id}">`;
      $("#deleteArea").append(html);
    }
  });

  // 削除を元に戻す
  $('body #blockArea').on('click', '.btn_list_undo', function () {
    var row = $(this).data("row");
    var block_id = $(`#idBlockId_${row}`).val();

    $(`#block_no_${row} input, #block_no_${row} textarea, #block_no_${row} select`).attr('disabled', false);
    $(`#block_no_${row}`).removeClass('delete');
    $(this).html('削除');
    $(this).removeClass('btn_list_undo');
    $(this).addClass('btn_list_delete');


    if (block_id > 0) {
      $(`#deleteArea #delBlock_${block_id}`).remove();
    }
  });

  // タグ追加
  $('#btnAddTag').on('click', function () {
    var tag = $("#idAddTag").val();
    if (tag != "") {
      addTag(tag);
      $("#idAddTag").val('');
    } else {
      alert_dlg('タグを入力してください');
    }
    return false;
  });

  // タグ削除
  $("#tagArea").on('click', '.delete_tag', function () {
    var id = $(this).data('id');
    $("#tag_id_" + id).addClass('delete');
    $("#tag_id_" + id + ' input').attr('disabled', true);
    $("#tag_id_" + id + ' a').removeClass('delete_tag');
    $("#tag_id_" + id + ' a').addClass('delete_rollbak');
  });

  // タグ削除取消
  $("#tagArea").on('click', '.delete_rollbak', function () {
    var id = $(this).data('id');
    $("#tag_id_" + id).removeClass('delete');
    $("#tag_id_" + id + ' input').attr('disabled', false);
    $("#tag_id_" + id + ' a').removeClass('delete_rollbak');
    $("#tag_id_" + id + ' a').addClass('delete_tag');
  });

  // タグリスト
  $("#btnListTag").on('click', function () {
    pop_box.select = function (tag) {
      addTag(tag);
      pop_box.close();
    };

    pop_box.open({
      element: "#btnListTag",
      href: "/user_admin/infos/pop_taglist?page_config_id=" + page_config_id,
      open: true,
      onComplete: function () {
      },
      onClosed: function () {
        pop_box.remove();
      },
      opacity: 0.5,
      iframe: true,
      width: '900px',
      height: '750px'
    });

    return false;
  })

  $("body").on('click', '.pop_image_single', function () {
    pop_box.image_single();
  });

  $("#btnSave").on('click', function () {
    $("#idPostMode").val('save');
    $(this).closest('form').removeAttr('target');
    document.fm.submit();
    return false;
  });

  // $("#btnPreview").on('click', function() {
  //   $("#idPostMode").val('preview');
  //   $(this).closest('form').attr('target', "_blank");
  //   document.fm.submit();
  //   return false;
  // });

  // ckeditorは一度に複数の指定をできないためループで回す
  var elements = $('textarea.editor').closest('tr');
  $.each(elements, function (key, value) {
    var parent_id = $(value).attr('id');
    // ckeditor
    setWysiwyg('#' + parent_id + ' textarea.editor');
  });

  setWysiwygOLD();
});
