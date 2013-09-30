<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="../lib/css/buttons.css">
    <link rel="stylesheet" href="../lib/css/columns.css">
    <link rel="stylesheet" href="../lib/css/chosen.css">
    <link rel="stylesheet" href="../lib/css/anytime.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/chosen.jquery.js"></script>
    <?php echo '<script src="../lib/js/dtc.js?' . time() . '"></script>'; ?>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.make.select('all','region');
        wvmmsurvey.make.selectMonth();
        $('#dropdownMonth').change(function () { 
          wvmmsurvey.make.select('all','region'); 
          $('input:radio[name="filter"][value="region"]').attr('checked', 'checked');
          if ($('#dropdownMonth option:selected').text() == '<?php echo date("F Y"); ?>') {
            $('.edittext').empty();
            $("<span>Edit</span>").appendTo('.edittext');
            $(".editimg").attr("src","img/editsmall.jpg");
          } else {
            $('.edittext').empty();
            $("<span>View</span>").appendTo('.edittext');
            $(".editimg").attr("src","img/viewsmall.jpg");
          }
        }); 
      });
      function showCompletion() {
        // Parse the store elements on the page and show the completion percentage for each
        alert('Your window may hang while the completion percentages load, please wait ....');
        $('.store').each(function() {
          var store = $(this).text().split(' ')[0];
          var percent = wvmmsurvey.make.compPercent(store,$('#dropdownMonth').val());
          var color = percent < 1 ? 'red' : percent == 100 ? 'green' : 'yellow';
          $(this).append(", "+percent+"%").addClass(color);
        });
        // Disable the check box so it cannot be toggled
        $('#sc').attr('disabled', true);
      }
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headerleft"><img id="saveStatus" src="" style="display: none;"></img></div>
        <div class="headercenter"><h1>Market Manager Survey</h1></div>
        <div class="headerright"><img id="printButton" src="" style="display: none;"></img></div>
      </div>      
      <div id="selectMonth" class="survey-heading"><!-- Dynamically populated --></div>
      <hr>
      <div class="columns-two">
        <div class="column-two">
          <div class="survey-heading">
            Select Store<br>
            <label class="survey-question"><input id="sc" type="checkbox" onclick="showCompletion();">Show completion %</label>
          </div>
          <div id="dynamicStore" style="padding-left:10px;padding-bottom:15px;">
            <!-- Dynamically populated -->
          </div>
        </div>
        <div class="column-two">
          <div class="columns-two">
            <div class="survey-heading" style="padding-left:12px;padding-bottom:15px;">Filter By:</div>
            <div class="column-two">
              <label class="survey-question" style="font-size:large"><input type="radio" name="filter" value="region" checked="checked"
                onclick="wvmmsurvey.make.select('all','region');">
                Region
              </label>
              <div id="dynamicRegion" style="padding-left:10px;padding-top:12px;padding-bottom:15px;">
                <!-- Dynamically populated -->
              </div>
            </div>
            <div class="column-two">
              <label class="survey-question" style="font-size:large"><input type="radio" name="filter" value="market"
                onclick="wvmmsurvey.make.select('all','market');">
                Market
              </label>
              <div id="dynamicMarket" style="padding-left:10px;padding-top:12px;padding-bottom:15px;">
                <!-- Dynamically populated -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <p class="center">
        <a href="https://wirelessvision.sharepoint.com/sales/mmsurvey/default.aspx" class="large button wvorange">
          <img src="img/cancel.jpg" class="btnimg" style="display:none;">
          <span class="btntext">Cancel</span>
        </a>
      </p>
    </div>
  </body>
</html>
