<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="../lib/css/buttons.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/dtc.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        $('#saveStatus').css('display', 'none');
        var popupOptions = 'height=600,width=800,directories=no,location=no,menubar=no,status=no,'
                         + 'titlebar=no,toolbar=no,resizable=yes,scrollbars=yes';
        $('#changeSurvey').click(function () { window.open('popup.php','_blank',popupOptions); });
        wvmmsurvey.make.questions(0);
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
        <div class="survey-heading">Change Survey</div>
        <hr>
        <p class="survey-question">Click the Change Survey button below to open a pop up window used to change the survey 
          questions and layout.</p>
        <p class="survey-question">Changes made here will only effect new surveys. To preserve data integrity, existing survey
          questions cannot be changed.</p>
        <p><a id="changeSurvey" href="#" class="large button wvorange"><img src="img/changesurvey.jpg" class="btnimg" style="display:none;margin-left: 0px;"><span class="btntext">Change Survey</span></a></p>
        <div class="survey-heading">Survey Information</div>
        <hr>
        <table style="padding-left: 10px;">
          <tr>
            <td class="survey-question" style="padding-left: 0px;">SAP Number: nnn</div></td>
            <td class="survey-question">Visit Date:</td>
            <td class="survey-question">Last Saved:</td>
          </tr>
          <tr>
            <td></td>
            <td class="survey-question">
              <div>mm/dd/yyyy hh:mm:ss</div>
            </td>
            <td class="survey-question">
              <div>mm/dd/yyyy hh:mm:ss</div>
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