<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="/dtclib/css/buttons.css">
    <link rel="stylesheet" href="/dtclib/css/chosen.css">
    <link rel="stylesheet" href="/dtclib/css/anytime.css">
    <link rel="stylesheet" href="/dtclib/css/messi.css">
    <link rel="stylesheet" href="/dtclib/css/columns.css">
    <link rel="stylesheet" href="/dtclib/css/jquery-ui-1.10.3.smoothness.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="/dtclib/js/jquery-1.8.2.js"></script>
    <script src="/dtclib/js/jquery.csv-0.71.min.js"></script>
    <script src="/dtclib/js/chosen.jquery.min.js"></script>
    <script src="/dtclib/js/anytime.js"></script>
    <script src="/dtclib/js/messi.min.js"></script>
    <script src="/dtclib/js/jquery-ui-1.10.3.custom.min.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        // Update output table
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
            wvmmsurvey.make.csvSelection();
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
    <div class="header">
      <div class="headercenter"><h1>Market Manager Survey</h1></div>
    </div>
    <div class="survey-heading">CSV Export</div>
    <hr>
    <form id="formCsvBySurvey" target="_blank" action="wvmmsurvey.php" method="get">
      <div class="columns-three">
        <div class="column-three">
          <p>
            <div class="survey-heading">From:<br></div>
            <select name="fromYear">
              <option>2013</option>
              <option>2014</option>
              <option>2015</option>
              <option>2016</option>
            </select>
            <select name="fromMonth">
              <option>January</option>
              <option>February</option>
              <option>March</option>
              <option>April</option>
              <option>May</option>
              <option>June</option>
              <option>July</option>
              <option>August</option>
              <option>September</option>
              <option>October</option>
              <option>November</option>
              <option>December</option>
            </select>
          </p>
          <p>
            <div class="survey-heading">To:<br></div>
            <select name="toYear">
              <option>2013</option>
              <option>2014</option>
              <option>2015</option>
              <option>2016</option>
            </select>
            <select name="toMonth">
              <option>January</option>
              <option>February</option>
              <option>March</option>
              <option>April</option>
              <option>May</option>
              <option>June</option>
              <option>July</option>
              <option>August</option>
              <option>September</option>
              <option>October</option>
              <option>November</option>
              <option>December</option>
            </select>
          </p>
        </div>
        <div class="column-three">
          <p>
            <div class="survey-heading">Report:<br></div>
            <select name="report">
              <option value="csvBySurvey">All Answers</option>
              <option value="csvCompPerc">Completion Percentage</option>
              <option value="csvRatings">Store Ratings</option>
            </select>
          </p>
        </div>
        <div class="column-three">
          <div id="accordion">
            <h3>Region</h3>
            <div id="accordionRegion"><!-- Dyanmically populated --></div>
            <h3>Market</h3>
            <div id="accordionMarket"><!-- Dyanmically populated --></div>
            <h3>Store</h3>
            <div id="accordionStore"><!-- Dyanmically populated --></div>
          </div>
          <br>
        </div>
      </div>
    </form>
    <p class="center">
      <a href="javascript:;" class="large button wvorange" onclick="wvmmsurvey.report.csvBySurvey();"><img src="img/savereport.jpg" class="btnimg" style="display:none;"><span class="btntext">Save Report</span></a>
    </p>
    <hr> <!-- Nice dashed line: style="border: 1px dashed grey;" -->
    <p class="center">
      <a href="https://wirelessvision.sharepoint.com/sales/mmsurvey/default.aspx" class="large button wvorange"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a>
    </p>
  </body>
</html>
