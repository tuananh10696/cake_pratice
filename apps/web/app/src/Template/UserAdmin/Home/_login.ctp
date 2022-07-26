<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
    content="<?php include WWW_ROOT . 'admin/common/include/viewport.inc' ?>">
  <link rel="stylesheet" href="/user/common/css/normalize.css">
  <link rel="stylesheet" href="/user/common/css/font.css">
  <link rel="stylesheet" href="/user/common/css/common.css">
  <link rel="stylesheet" href="/user/common/css/cms_theme.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
    integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
  </script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script> -->
  <script src="https://kit.fontawesome.com/7a9d7e5bcd.js" crossorigin="anonymous"></script>
  <!--[if lt IE 9]>
<script src='./common/js/html5shiv.js'></script>
<![endif]-->
</head>

<body class="login">

  <div id="container">
    <header class="login__header">
      <div class="status">
      </div>
    </header>

    <div id="content" class="login__content">
      <div class="title_area login__title-area">
        <h1>HOMEPAGE MANAGER</h1>
      </div>
      <!-- div class="error">error messeages error messeages error messeages error messeages error messeages</div -->
      <?= $this->element('error_message'); ?>
      <div class="content_inr login__inner">
        <div class="box login__box" style="max-width:600px;margin-left:auto;margin-right:auto;">
          <h3 class="login__caption">ログイン</h3>
          <?= $this->Form->create('Admin', array('id' => 'AdminIndexForm'));?>
          <div class="table_area form_area login__table-area">
            <table class="vertical_table login__table">
              <tr>
                <td>ユーザーID</td>
                <td><input name="username" type="text" id="id" placeholder="ユーザーID" style="width:100%;" /></td>
              </tr>
              <tr>
                <td>パスワード</td>
                <td><input name="password" type="password" id="pw" placeholder="パスワード" style="width: 100%;" /></td>
              </tr>
            </table>
            <div class="btn_area">
              <a href="javascript:void(0);" class="btn_confirm">ログイン<i class="icon-icn_arrow_rt"></i></a>
              <a href="javascript:void(0);" class="btn_back">リセット<i class="icon-icn_arrow_rt"></i></a>
            </div>
          </div>
          <?= $this->Form->end();?>
        </div>
      </div>
      <?php include WWW_ROOT . 'admin/common/include/footer.inc' ?>
    </div>
  </div>

  <script src="./common/js/jquery.js"></script>
  <script src="./common/js/base.js"></script>
  <script>
    $(function() {
      $('a.btn_confirm').on('click', function() {
        $('#AdminIndexForm').submit();
      });
      $('a.btn_back').on('click', function() {
        document.getElementById("AdminIndexForm").reset();
        //$('#AdminIndexForm').reset();
      });
    })
  </script>

</body>

</html>




</table>

<?php $this->start('afterContent');?>

<?php $this->end();
