<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOMEPAGE MANAGER</title>
  <link rel="stylesheet" href="/user/common/css/normalize.css">
  <link rel="stylesheet" href="/user/common/css/login.css">
  <link rel="stylesheet" href="/user/common/css/bootstrap-custom.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <div id="container">
    <div id="content">
      <div class="title-area">
        <h1>HOMEPAGE MANAGER </h1>
      </div>
      <div id="error-message"> </div>
      <div class="content-inr">
        <div class="login-box">
          <h3 class="caption-img">ログイン</h3>
          <?= $this->Form->create('Admin', array('id' => 'AdminIndexForm'));?>

          <div class="table_area form_area login__table-area">
            <table class="vertical_table login__table">
              <tr>
                <td class="item-title">ユーザーID</td>
                <td><input name="username" type="text" id="id" placeholder="ユーザーID" style="width:100%;" /></td>
              </tr>
              <tr>
                <td class="item-title">パスワード</td>
                <td><input name="password" type="password" id="pw" placeholder="パスワード" style="width: 100%;" /></td>
              </tr>
            </table>
          </div>

          <?= $this->element('error_message'); ?>
          
          <div id="login-button"><a href="javascript:void(0);">ログイン</a></div>
          <?= $this->Form->end();?>
        </div>
      </div>
      <footer>
        <div class="copy">© CATERS Inc. All Rights Reserved.</div>
      </footer>
    </div>
  </div>
  <script src="/user/common/js/login.js"> </script>
</body>

</html>