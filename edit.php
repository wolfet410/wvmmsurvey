<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="../lib/css/buttons.css">
    <link rel="stylesheet" href="../lib/css/chosen.css">
    <link rel="stylesheet" href="../lib/css/anytime.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/dtc.js"></script>
    <script src="../lib/js/jquery.csv-0.71.min.js"></script>
    <script src="../lib/js/chosen.jquery.js"></script>
    <script src="../lib/js/anytime.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.make.edit(<?php echo $_GET['suid']; ?>);
        wvmmsurvey.make.questions(<?php echo $_GET['suid']; ?>);
        var altText = "Last Saved: " + $('#modifiedDate').text();
        $('#saveStatus').attr({'src':"/img/saved.png",'alt':altText,'title':altText});
        $('#saveStatus').css('display', 'block');
        $(document).find(':input').each(function() {
          switch(this.type) {
            case 'textarea':
              $('textarea#'+this.id).change (function () { 
                wvmmsurvey.act.save(this.id.match(/[0-9]+/g),'textarea',$('textarea#'+this.id).val(),<?php echo $_GET['suid']; ?>); 
              });
              break;
            case 'radio':
              $('input:radio[name='+this.id+']').click(function() { 
                wvmmsurvey.act.save(this.id.match(/[0-9]+/g),'radio',$('input:radio[name='+this.id+']:checked').val(),<?php echo $_GET['suid']; ?>); 
              });
              break;
          }
        });
        // Swapping CSS buttons for images in browsers that do not support advanced features
        (!$.support.opacity) && $('.btnimg').css('display','block');
        (!$.support.opacity) && $('.btntext').css('display','none');
        (!$.support.opacity) && $('a').removeClass('large button wvorange');
      });
    </script>
  </head>
  <body class="body">
    <div>
      <?php include 'header.html'; ?>
      <div id="staticContent">
        <div class="survey-heading">Survey Information</div>
        <hr>
        <table style="padding-left: 10px;">
          <tr>
            <td class="survey-question" style="padding-left: 0px;"><div id="storeVisited"><!-- Dynamically populated --></div></td>
            <td class="survey-question">Visit Date:</td>
            <td class="survey-question">Last Saved:</td>
          </tr>
          <tr>
            <td></td>
            <td class="survey-question">
              <div id="createdDate"><!-- Dynamically populated --></div>
            </td>
            <td class="survey-question">
              <div id="modifiedDate"><!-- Dynamically populated --></div>
            </td>
          </tr>
        </table>
      </div>
      <div id="dynamicContent">
        <!-- Dynamically populated -->
      </div>
      <div id="close">
        <p class="center"><a href="/" class="large button wvorange"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a></p>
      </div>
    </div>
  </body>
</html>
