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
    <script src="../lib/js/chosen.jquery.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.make.select();
        $('#surveyList').change(function() { window.location.href = "edit.php?suid=" + $(this).val(); });
        // Swapping CSS buttons for images in browsers that do not support advanced features
        (!$.support.opacity) && $('.btnimg').css('display','block');
        (!$.support.opacity) && $('.btntext').css('display','none');
        (!$.support.opacity) && $('a').removeClass('large button wvorange');
      });
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headerleft"><img id="saveStatus" src="" style="display: none;"></img></div>
        <div class="headercenter"><h1>Market Manager Survey Tool</h1></div>
        <div class="headerright"><img id="printButton" src="" style="display: none;"></img></div>
      </div>      
      <div class="survey-heading">Select Survey</div>
      <hr>
      <p class="survey-question">Select the survey below, by the SAP # and the date &amp; time of visit. Type the SAP # to filter by store.</p>
      <div id="selectSurvey"><!-- Dynamically populated --></div>
      <p class="center">
        <a href="index.php" class="large button wvorange"><img src="img/cancel.jpg" class="btnimg" style="display:none;"><span class="btntext">Cancel</span></a>
      </p>
    </div>
  </body>
</html>
