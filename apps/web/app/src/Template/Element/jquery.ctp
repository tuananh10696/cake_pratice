<script src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<?= $this->Html->script('/assets/jquery_box/js/app_common'); ?>
<?= $this->Html->script('/assets/jquery_box/js/jquery.colorbox-min'); ?>
<?= $this->Html->script('/assets/jquery_box/js/pop_box'); ?>
<?= $this->Html->script('/assets/jquery_box/js/jquery'); ?>

<!-- email自動補完 -->
<script src="/assets/jquery_box/js/jquery.email-autocomplete.js"></script>

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

<script>
  $(document).ready(function() {
    $("#email .email").emailautocomplete({
      domains: ["example.com", "yahoo.co.jp"]
    });
  });
  $(document).ready(function() {
    $(".email").emailautocomplete({
      domains: ["example.com", "yahoo.co.jp"]
    });
  });
</script>

<!-- 郵便局自動入力 -->
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChSoSTkzIp6Fpt5EUexeJtZG_q3abeKSQ"></script>
<script>
  $(document).ready(function() {
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