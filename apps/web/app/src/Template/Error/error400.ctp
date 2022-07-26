<?php
$this->layout = 'simple';
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>お探しのページが見つかりません。</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="/cms_assets/css/common.css">
    <link rel="stylesheet" href="/cms_assets/css/error.css">
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
          <h2>お探しのページが見つかりません。</h2>

          <div>
              <p class="not_found_title">申し訳ありません。お探しのページは見つかりませんでした。</p>
              
              <ul class="not_found_description">
                <li>お探しのページが移動または削除されている場合があります。</li>
                <li>アドレス(URL)が間違っている可能性があります。<br>目的のアドレス(URL)をお確かめの上、もう一度アクセスし直してみてください。</li>
                <li>直前にご覧になっていたページのリンクが間違っている可能性があります。</li>
              </ul>
          </div>

          <div class="top_link"><a href="/"><span>トップページへ戻る</span></a></div>
        </div>

      </main>
      <footer class="footer">
        <p class="footer__copyright">copyright © CATERS all rights reserved.</p>
      </footer>
    </div>


  </body>
</html>