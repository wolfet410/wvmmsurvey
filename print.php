<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="print.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/dtc.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.make.edit(<?php echo $_GET['suid']; ?>);
        wvmmsurvey.make.print(<?php echo $_GET['suid']; ?>);
        $(':input').prop('disabled', true);
        window.print();
      });
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headercenter"><h1>Market Manager Survey Tool</h1></div>
      </div>
      <div id="staticContent">
        <div class="survey-heading">Survey Information</div>
        <hr>
        <table style="padding-left: 10px;">
          <tr>
            <td class="survey-question" style="padding-left: 0px;"><div id="storeVisited"><!-- Dynamically populated --></div></td>
            <td class="survey-question">Surveyor:</td>
            <td class="survey-question">Visit Date:</td>
            <td class="survey-question">Last Saved:</td>
          </tr>
          <tr>
            <td></td>
            <td class="survey-question">
              <div id="surveyor"><!-- Dynamically populated --></div>
            </td>
            <td class="survey-question">
              <div id="createdDate"><!-- Dynamically populated --></div>
            </td>
            <td class="survey-question">
              <div id="modifiedDate"><!-- Dynamically populated --></div>
            </td>
          </tr>
        </table>
        <br><div class="survey-heading">Rating Information</div>
        <hr>
        <div id="ratingDiv"><!-- Dynamically populated --></div>
      </div>
      <div id="dynamicContent">
        <!-- Dynamically populated -->
      </div>
    </div>
  </body>
</html>
