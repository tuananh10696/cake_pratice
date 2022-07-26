<!DOCTYPE html>
<html lang="ja">

<head>
  <?php echo $this->Html->charset(); ?>
  <meta name="viewport"
    content="<?php include WWW_ROOT . 'user/common/include/viewport.inc' ?>">
  <title>HOMEPAGE MANAGER</title>

  <link rel="stylesheet" href="/user/common/css/normalize.css">
  <link rel="stylesheet" href="/user/common/css/font.css">
  <?= $this->Html->css('/user/common/css/common'); ?>
  <!-- <link rel="stylesheet" href="/user/common/css/jquery.mCustomScrollbar.min.css"> -->
  <link rel="stylesheet" href="/user/common/css/jquery-ui-1.12.1/smoothness/jquery-ui.min.css">
  <link rel="stylesheet" href="/user/common/css/colorbox.css">
  <link rel="stylesheet" href="/user/common/css/cms_theme.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
    integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="/user/common/js/jquery-ui-1.12.1.min.js"></script>
  <script type="text/javascript">
    $.widget.bridge('uibutton', $.ui.button);
    $.widget.bridge('uitooltip', $.ui.tooltip);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
  </script>

  <script src="/user/common/js/base.js"></script>
  <!-- <script src="/user/common/js/jquery.mCustomScrollbar.concat.min.js"></script> -->
  <script src="/user/common/js/jquery.colorbox-min.js"></script>
  <script src="/user/common/js/colorbox.js"></script>
  <script src="https://kit.fontawesome.com/7a9d7e5bcd.js" crossorigin="anonymous"></script>
  <!--[if lt IE 9]>
<script src='/common/js/html5shiv.js'></script>
<![endif]-->

  <?php echo $this->fetch('beforeHeaderClose');?>
</head>

<body class="user-layout">
  <?php echo $this->fetch('afterBodyStart');?>

  <div id="container">
    <?php echo $this->element('header');?>
    <?php echo $this->element('side');?>

    <?php echo $this->fetch('beforeContentStart');?>

    <div id="content">

      <?php echo $this->fetch('content'); ?>

      <?php include WWW_ROOT . 'user/common/include/footer.inc' ?>
    </div>
    <?php echo $this->fetch('afterContentClose');?>
  </div>



  <div id="kakunin_dialog" title="確認">
    <p></p>
  </div>

  <script>
    $.fn.bootstrapBtn = $.fn.button.noConflict();
    $(function() {
      $("#selectSite").on('change', function() {
        var site = $(this).val();
        window.location.href = '/user/users/site-change?site=' + site;
        return;
      });
    })
  </script>
  <?php echo $this->fetch('beforeBodyClose');?>
</body>

</html>