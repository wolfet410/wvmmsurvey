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
        // Update output table
        new Messi('There may be a delay while updating the reporting table with the latest data', {
          title: 'Updating...',
          buttons: [{id: 0, label: 'OK', val: ''}],
        });
        $.ajax({
          url: "wvmmsurvey.php", 
          type: 'POST',
          data: { 
            todo: "updateOutput"
          },
          cache: false,
          async: false,
          dataType: 'json',
          success: function(r) {
            wvmmsurvey.make.select();
            wvmmsurvey.make.create("http://wvmmsurvey.buzzspace.datatechcafe.com/phonelist/2013 - Wireless Vision Phone Directory.csv");
          },
          error: function(a,b,c) {
            alert(a+","+b+","+c);
          }
        });
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
      <div class="survey-heading">Reports</div>
      <hr>
      <p class="report-text">Select the appropriate Run Report button below</p>
      <div class="survey-heading">CSV Export</div>
      <hr>
      <form id="formCsvBySurvey" target="_blank" action="http://wvmmsurvey.buzzspace.datatechcafe.com/wvmmsurvey.php" method="get">
        <input type="hidden" name="report" value="csvBySurvey">
        <p class="report-text">By Survey:</p>
        <p><span id="selectSurvey" style="padding-left: 10px;padding-right: 10px;"><!-- Dynamically populated --></span>
        <a href="#" class="large button wvorange" onclick="wvmmsurvey.report.csvBySurvey();"><img src="img/savereport.jpg" class="btnimg" style="display:none;"><span class="btntext">Save Report</span></a></p>
      </form>
      <hr style="border: 1px dashed grey;">
      <p class="report-text">By Store:</p>
      <p><span id="storeVisited" style="padding-left: 10px;padding-right: 10px;"><!-- Dynamically populated --></span>
      <a href="#" class="large button wvorange"><img src="img/runreport.jpg" class="btnimg" style="display:none;"><span class="btntext">Run Report</span></a></p>
      <hr style="border: 1px dashed grey;">
      <p class="report-text">By Date Range:</p>
      <p><a href="#" class="large button wvorange"><img src="img/runreport.jpg" class="btnimg" style="display:none;"><span class="btntext">Run Report</span></a></p>
      <div class="survey-heading">Close</div>
      <hr>
      <p class="center">
        <a href="index.php" class="large button wvorange"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a>
      </p>
    </div>
  </body>
</html>
