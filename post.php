<!DOCTYPE html>
<html>
  <head>
    <?php 
      require "/var/www/lib/php/library.php";
      session_start();
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
    <link rel="stylesheet" href="../lib/css/messi.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/jquery.SPServices-2013.01.min.js"></script>
    <script src="../lib/js/messi.min.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        // Adding admin buttons
        ('<?php echo $_SESSION["admin"]; ?>' == 'true') && $('#admin').css('display','block');
        // Swapping CSS buttons for images in browsers that do not support advanced features
        (!$.support.opacity) && $('.btnimg').css('display','block');
        (!$.support.opacity) && $('.btntext').css('display','none');
        (!$.support.opacity) && $('a').removeClass('large button wvorange');
        // Browsers that do not support scroll bars create problems when using iFrames, because they do
        // not scroll properly. To overcome this, pages that may require scrolling should be opened 
        // in a new browser window (thus no iframes).
        // Code below sets noscroll to true if the web browser doesn't use scroll bars.
        // Adapted from: http://lostmonocle.com/post/870842095/geeky-stuff-using-jquery-to-check-if-scrollbars-are
        var sbDiv, sbDivCSS;
        // Create a div and set overflow to scroll, position it off-screen
        sbDiv = $("<div id='sbTest'></div>");
        sbDivCSS = {'width':'150px','height':'150px','overflow':'scroll','position':'absolute','left':'-3000em'};
        $(sbDiv).css(sbDivCSS);
        $(sbDiv).appendTo("body");
        // clientWidth takes scrollbars into account, innerWidth() doesn't, so clientWidth should be smaller if there's a 
        // scrollbar.
        var noscroll = $(sbDiv)[0].clientWidth < $(sbDiv).innerWidth() ? false : true;
        $(sbDiv).remove();
        // Click handlers for links that may open in new window
        var blank = noscroll ? '_blank' : '_self';
        var specs = noscroll ? 'location=no,menubar=no,status=no,toolbar=no' : '';
        $('#btnEditSurvey').click(function () {
          window.open('select.php?email=<?php echo $_SESSION["email"]; ?>',blank,specs);
        });
      });
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headercenter"><h1>Market Manager Survey</h1></div>
      </div>
      <p class="center"><a id="btnEditSurvey" href="javascript:;" class="large button wvorange"><img src="img/surveys.png" class="btnimg" style="display:none;"><span class="btntext">Edit Survey</span></a></p>
      <p class="center"><a href="report.php" class="large button wvorange"><img src="img/reports.png" class="btnimg" style="display:none;"><span class="btntext">Reports</span></a></p>
      <div id="admin" style="display:none;">
        <p class="center"><a href="admin.php" class="large button wvorange"><img src="img/administersurveys.png" class="btnimg" style="display:none;"><span class="btntext">Administer Surveys</span></a></p>
      </div>
    </div>
  </body>
</html>
