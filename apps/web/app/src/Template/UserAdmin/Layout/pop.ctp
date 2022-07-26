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
  <link rel="stylesheet" href="/user/common/css/jquery.mCustomScrollbar.min.css">
  <link rel="stylesheet" href="/user/common/css/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css">
  <link rel="stylesheet" href="/user/common/css/colorbox.css">
  <link rel="stylesheet" href="/user/common/css/cms_theme.css">
  <style type="text/css">
    .scrollbar {
      height: 500px;
      overflow: hidden;
      padding: 10px;
    }
  </style>
  <script src="/user/common/js/jquery.js"></script>
  <script src="/user/common/js/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="/user/common/js/base.js"></script>
  <script src="/user/common/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="/user/common/js/jquery.colorbox-min.js"></script>
  <script src="/user/common/js/colorbox.js"></script>

  <!--[if lt IE 9]>
<script src='/common/js/html5shiv.js'></script>
<![endif]-->

  <?php echo $this->fetch('beforeHeaderClose');?>
</head>

<body class="user-layout">
  <?php echo $this->fetch('afterBodyStart');?>

  <div id="container">

    <?php echo $this->fetch('beforeContentStart');?>

    <div id="content" style="padding-left: 0px !important; padding-top: 0px !important;min-width: 0 !important;">

      <?php echo $this->fetch('content'); ?>

    </div>
    <?php echo $this->fetch('afterContentClose');?>
  </div>

  <?php echo $this->fetch('beforeBodyClose');?>
</body>

</html>