<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="../lib/css/buttons.css">
    <link rel="stylesheet" href="../lib/css/anytime.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/anytime.js"></script>
    <script src="../lib/js/dtc.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        AnyTime.picker("newMonth",
          { format: "%M %z" }
        );
      });
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headerleft"><img id="saveStatus" src="" style="display: none;"></img></div>
        <div class="headercenter"><h1>Market Manager Survey</h1></div>
      </div>
      <div id="staticContent">
        <p>Choose the month and year to copy to:</p>
        <input type="text" id="newMonth" size="30" />
        <a href="javascript:;" class="large button wvorange" 
          onclick="wvmmsurvey.act.copySurvey('<?php echo $_POST["existingSurveys"]; ?>',$('#newMonth').val());">
          <img src="img/copysurvey.jpg" class="btnimg" style="display:none;"><span class="btntext">Copy Survey</span>
        </a>
        <p class="center">
          <a href="javascript:;" class="large button wvorange" onclick="window.close();"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a>
        </p>
      </div>
    </div>
  </body>
</html>
