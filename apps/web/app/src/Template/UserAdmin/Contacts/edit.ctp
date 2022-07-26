<?php $this->start('beforeHeaderClose'); ?>
<style>
  input[type="checkbox"]:disabled+label {
    color: #a7a7a7;
  }
</style>
<?php $this->end(); ?>

<div class="title_area">
  <h1>お問い合わせ</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['action' => 'index']); ?>">お問い合わせ</a>
      </li>
      <li><span><?= ($data['id'] > 0) ? '編集' : '新規登録'; ?></span>
      </li>
    </ul>
  </div>
</div>

<?= $this->element('error_message'); ?>
<div class="content_inr">
  <div class="box">
    <h3><?= ($data['id'] > 0) ? '編集' : '新規登録'; ?>
    </h3>
    <div class="table_area form_area">
      <?= $this->Form->create($FormModel, array('type' => 'file', 'context' => ['validator' => 'default']));?>
      <?= $this->Form->input('id', array('type' => 'hidden', 'value' => $entity->id));?>
      <?= $this->Form->input('position', array('type' => 'hidden'));?>
      <table class="vertical_table table__meta">

        <tr style="list-style: none;">
          <td>お問い合わせ内容<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('contact_type_array', array('type' => 'select', 'multiple' => 'checkbox', 'options' => $c_type_list));?>
            <?= $this->Form->error('contact_type_ids') ?>
            <span>※複数選択可</span>
          </td>
        </tr>

        <tr style="list-style: none;">
          <td>生徒との続柄<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('relationship_id', array('type' => 'radio', 'options' => $c_relationship_list, ));?>
          </td>
        </tr>

        <tr>
          <td>ご希望の校舎名<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('desired_school_name', array('type' => 'text', 'maxlength' => 100, 'placeholder' => ''));?>
            <br><span>※100文字以内で入力してください</span>
          </td>
        </tr>

        <tr>
          <td>中学受験の希望有無</td>
          <td>
            <?= $this->Form->hidden('try_chugaku_exam', array('value' => 0)); ?>
            <?= $this->Form->input('try_chugaku_exam', array('type' => 'checkbox', 'value' => 1, 'label' => '中学受験を検討している', ));?>
            <?= $this->Form->error('try_chugaku_exam') ?>
          </td>
        </tr>

        <tr>
          <td>郵便番号<span class="attent">※必須</span></td>
          <td>
            <?= $this->Form->input('zip1', array('type' => 'text', 'maxlength' => 3, 'class' => 'zip1', 'placeholder' => '000', 'style' => 'width:100px;'));?>
            -
            <?= $this->Form->input('zip2', array('type' => 'text', 'maxlength' => 4, 'class' => 'zip2', 'placeholder' => '0000', 'style' => 'width:100px;'));?>

            <?= $this->Form->error('zip') ?>
            <!-- <br><span>※7文字以内で入力してください</span> -->
          </td>
        </tr>



        <?php if (false) : ?>
        <tr>
          <td>有効/無効</td>
          <td>
            <?= $this->Form->input('status', array('type' => 'select', 'options' => array('draft' => '無効', 'publish' => '有効')));?>
          </td>
        </tr>
        <?php endif; ?>

      </table>

      <div class="btn_area">
        <?php if (!empty($data['id']) && $data['id'] > 0) { ?>
        <a href="#" class="btn_confirm submitButton submitButtonPost">変更する</a>
        <a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $data['id'], 'content'))?>')"
          class="btn_delete">削除する</a>
        <?php } else { ?>
        <a href="#" class="btn_confirm submitButton submitButtonPost">登録する</a>
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

<!-- 郵便局自動入力 -->
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMjwGC-7WpCqlp5frhz-9F6BKoGRHck7s"></script>
<script>
  $('.zip').keyup(function(e) {
    if ($(this).val().length >= 7) {
      getAddress($(this).val());

    }
  });

  $('.zip1').keyup(function(e) {
    if ($(this).val().length == 3) {
      var zip = $(".zip1").val() + $(".zip2").val();
      getAddress(zip);
    }
  });
  $('.zip2').keyup(function(e) {
    if ($(this).val().length == 4) {
      var zip = $(".zip1").val() + $(".zip2").val();
      getAddress(zip);
    }
  });



  function getAddress(zip) {
    var addressElement = $(".address");

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

<!-- 年齢計算 -->
<script>
  updateAge();

  $('.birthdays').change(function(e) {
    updateAge();
  });

  function updateAge() {
    const birthday = {
      year: $(".birth_year").val(),
      month: $(".birth_month").val(),
      date: $(".birth_day").val(),
    };
    var age = getAge(birthday);
    $(".age").text(age);
  }

  function getAge(birthday) {
    //今日
    var today = new Date();
    //今年の誕生日
    var thisYearsBirthday = new Date(today.getFullYear(), birthday.month - 1, birthday.date);
    //年齢
    var age = today.getFullYear() - birthday.year;
    if (today < thisYearsBirthday) {
      //今年まだ誕生日が来ていない
      age--;
    }
    return age;
  }
</script>

<?php $this->end();
