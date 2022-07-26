<?php
$this->layout = 'simple';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>プレビューエラー</title>
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="stylesheet" href="/cms_assets/css/common.css">
  <link rel="stylesheet" href="/cms_assets/css/error.css">
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
  <script src="https://kit.fontawesome.com/7a9d7e5bcd.js" crossorigin="anonymous"></script>
</head>

<body>
  <div class="root" id="root">
    <header class="header">
      <h1 class="header__caption">
        <a href="/">
          <img src="/image/logo.png" alt="">
        </a>
      </h1>
    </header>
    <main id="content" class="error_page">
      <div class="error_content">
        <h2>プレビュー出来ませんでした</h2>

        <div class="mb-3">
          <p class="not_found_title text-danger">※必須項目は入力をお願いします。</p>

          <div class="text-center">
            <?php if ($error_messages || $this->Common->session_check('Flash.flash.0.message')): ?>
            <div class="error text-danger">
              <?= $error_messages; ?>
              <div><?= $this->Flash->render(); ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="text-center">
          <div class="btn btn-secondary disabled">この画面を閉じてください</div>
        </div>

      </div>

    </main>
    <footer class="footer">
      <p class="footer__copyright">copyright © CATERS all rights reserved.</p>
    </footer>
  </div>

  <script>
    $.fn.bootstrapBtn = $.fn.button.noConflict();
  </script>
</body>

</html>