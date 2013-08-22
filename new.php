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
    <link rel="stylesheet" href="../lib/css/messi.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/jquery.csv-0.71.min.js"></script>
    <script src="../lib/js/chosen.jquery.min.js"></script>
    <script src="../lib/js/anytime.js"></script>
    <script src="../lib/js/messi.min.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        $('#saveStatus').css('display', 'none');
        // CSV copy of the Wireless Vision phone list
        wvmmsurvey.make.create("http://wvmmsurvey.buzzspace.datatechcafe.com/phonelist/2013 - Wireless Vision Phone Directory.csv");
        // Swapping CSS buttons for images in browsers that do not support advanced features
        (!$.support.opacity) && $('.btnimg').css('display','inline-block');
        (!$.support.opacity) && $('.btntext').css('display','none');
        (!$.support.opacity) && $('a').removeClass('large button wvorange');
      });
    </script>
  </head>
  <body class="body">
    <div>
      <?php include 'header.html'; ?>
      <div id="staticContent">
        <div class="survey-heading">Create New Survey</div>
        <hr>
        <p>
          <div class="survey-question">Store Visited: </div>
          <div id="storeVisited" style="padding-left: 10px;"><!-- Dynamically populated --></div>      
        </p>
        <p>
          <div class="survey-question">Date and Time Of Visit:</div>
          <div style="padding-left: 10px;">
            <input type="text" id="visitDate" size="30" />
            <input type="text" id="visitTime" />
          </div>
        </p>
      </div>
      <div id="createSurvey">
        <p class="center">
          <a href="index.php" class="large button wvorange"><img src="img/cancel.jpg" class="btnimg" style="display:none;"><span class="btntext">Cancel</span></a>
          <a href="#" id="createSurvey" class="large button wvorange" 
             onclick="wvmmsurvey.act.create($('#store').val(),$('#visitDate').val(),$('#visitTime').val());">
             <img src="img/create.jpg" class="btnimg" style="display:none;"><span class="btntext">
             Create</span></a>
        </p>
      </div>
    </div>
  </body>
</html>
