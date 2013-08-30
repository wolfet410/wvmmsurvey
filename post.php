<!DOCTYPE html>
<html>
  <head>
    <?php 
      session_start();
      require "/var/www/lib/php/library.php";
      $_SESSION['admin'] = $_POST['admin'];
      $_SESSION['email'] = $_POST['email'];
    ?>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="format-detection" content="telephone=no">
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="../lib/css/buttons.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        // Adding admin buttons
        ('<?php echo $_SESSION["admin"]; ?>' == 'true') && $('#admin').css('display','block');
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
        <div class="headercenter"><h1>Market Manager Survey Tool</h1></div>
      </div>
      <p class="center"><a href="new.php" class="large button wvorange"><img src="img/createnewsurvey.jpg" class="btnimg" style="display:none;"><span class="btntext">Create New Survey</span></a></p>
      <p class="center"><a href="select.php" class="large button wvorange"><img src="img/editexistingsurvey.jpg" class="btnimg" style="display:none;"><span class="btntext">Edit Existing Survey</span></a></p>
      <div id="admin" style="display:none;">
        <p class="center"><a href="change.php" class="large button wvorange"><img src="img/changesurveyquestions.jpg" class="btnimg" style="display:none;"><span class="btntext">Change Survey Questions</span></a></p>
        <p class="center"><a href="report.php" class="large button wvorange"><img src="img/reports.jpg" class="btnimg" style="display:none;"><span class="btntext">Reports</span></a></p>
        <p class="center"><a href="sync.php" class="large button wvorange"><img src="img/sync.png" class="btnimg" style="display:none;"><span class="btntext">Sync</span></a></p>
      </div>
    </div>
  </body>
</html>