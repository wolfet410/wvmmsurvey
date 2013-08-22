<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="../lib/css/buttons.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="../lib/js/jquery-1.8.2.js"></script>
    <script src="../lib/js/dtc.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.make.popup();
        $(document).find(':input').each(function() {
          switch(this.type) {
            case 'select-one':
            case 'text':
              $('#'+this.id).change (function () { 
                wvmmsurvey.act.popupWork(this.id);
                wvmmsurvey.make.refresh(this.id.match(/[0-9]+/g).toString());
              });              
              break;
            case 'radio':
              $('input:radio[name='+this.name+']').click(function() {
                if (this.name.indexOf('notes') != -1) {
                  console.warn($('input:radio[name=' + this.name + ']:checked').val());
                  if ($('input:radio[name=' + this.name + ']:checked').val() == 'true') {
                    $('#notestextdiv' + this.name.match(/[0-9]+/g).toString()).show();
                  } else {
                    $('#notestextdiv' + this.name.match(/[0-9]+/g).toString()).hide();
                  }
                }
                wvmmsurvey.act.popupWork(this.name);
                wvmmsurvey.make.refresh(this.id.match(/[0-9]+/g).toString());
              });
              break;
          }
        });
      });
    </script>
  </head>
  <body class="body">
    <div>
      <?php include 'header.html'; ?>
      <div id="staticContent">
        <div class="survey-heading">Survey Information</div>
        <hr>
        <table style="padding-left: 10px;">
          <tr>
            <td class="survey-question" style="padding-left: 0px;">SAP Number: nnn</div></td>
            <td class="survey-question">Visit Date:</td>
            <td class="survey-question">Last Saved:</td>
          </tr>
          <tr>
            <td></td>
            <td class="survey-question">
              <div>mm/dd/yyyy hh:mm:ss</div>
            </td>
            <td class="survey-question">
              <div>mm/dd/yyyy hh:mm:ss</div>
            </td>
          </tr>
        </table>
      </div>
      <div id="popupContent"><!-- Dynamically populated --></div>
      <div id="close">
        <p class="center"><a href="#" class="large button wvorange" onclick="window.close();">Close</a></p>
      </div>
    </div>
  </body>
</html>