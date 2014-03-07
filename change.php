<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="/dtclib/css/buttons.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="/dtclib/js/jquery-1.8.2.js"></script>
    <script src="/dtclib/js/dtc.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        var muid = <?php echo $_POST['editableSurveys']; ?>;
        var popupOptions = 'height=600,width=800,directories=no,location=no,menubar=no,status=no,'
                         + 'titlebar=no,toolbar=no,resizable=yes,scrollbars=yes';
        $('#changeSurvey').click(function () { window.open('popup.php?muid='+muid,'_blank',popupOptions); });
        wvmmsurvey.make.questions(muid);
        $(':input').prop('disabled', true);
        // Swapping CSS buttons for images in browsers that do not support advanced features
        (!$.support.opacity) && $('.btnimg').css('display','block');
        (!$.support.opacity) && $('.btntext').css('display','none');
        (!$.support.opacity) && $('a').removeClass('large button wvorange');
      });
      function tempForm(muid) {
        // A temp form to hold original muid
        // Adapted from: http://www.webdeveloper.com/forum/showthread.php?54961-submit-form-data-to-parent-window
        var form = $(document.createElement('form'))
            .attr({'method': 'post', 'action': 'change.php', 'target': '_self'});
        $(document.createElement('input'))
          .attr({'type': 'hidden', 'name': 'editableSurveys', 'value': muid})
          .appendTo(form);
        form.appendTo(document.body).submit(); 
      }
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headercenter"><h1>Market Manager Survey</h1></div>
      </div>
      <div id="staticContent">
        <div class="survey-heading">Change Survey</div>
        <hr>
        <p class="survey-question">Click the Change Survey button below to open a pop up window used to change the survey 
          questions and layout.</p>
        <p><a id="changeSurvey" href="#" class="large button wvorange"><img src="img/changesurvey.jpg" class="btnimg" style="display:none;margin-left: 0px;"><span class="btntext">Change Survey</span></a></p>
        <div id="surveyName" class="survey-heading"><!-- Dynamically populated --></div>
        <hr>
        <table style="padding-left: 10px;">
          <tr>
            <td class="survey-question" style="padding-left: 0px;">Store:</div></td>
            <td class="survey-question">Surveyor:</td>
            <td class="survey-question">Last Saved:</td>
          </tr>
          <tr>
            <td class="survey-question">nnn - Store Description</td>
            <td class="survey-question">
              <div>user@wirelessvision.com</div>
            </td>
            <td class="survey-question">
              <div>mm/dd/yyyy hh:mm:ss</div>
            </td>
          </tr>
        </table>
        <br><br><div class="survey-heading">Store Rating</div>
        <hr>
        <div style="font-size:2em;font-weight:bold;">0%</div>
        <br><br>
      </div>
      <div id="dynamicContent">
        <!-- Dynamically populated -->
      </div>
      <div id="close">
        <p class="center"><a href="https://wirelessvision.sharepoint.com/sales/mmsurvey/default.aspx" class="large button wvorange"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a></p>
      </div>
    </div>
  </body>
</html>
