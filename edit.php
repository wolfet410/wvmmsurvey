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
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="/dtclib/js/jquery-1.8.2.js"></script>
    <script src="/dtclib/js/dtc.js"></script>
    <script src="/dtclib/js/jquery.csv-0.71.min.js"></script>
    <script src="/dtclib/js/chosen.jquery.js"></script>
    <script src="/dtclib/js/anytime.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.make.edit('<?php echo $_GET['email']; ?>',<?php echo $_GET['muid']; ?>,<?php echo $_GET['store']; ?>);
        wvmmsurvey.make.questions(<?php echo $_GET['muid']; ?>,$('#suid').val());
        var altText = "Last Saved: " + $('#modifiedDate').text();
        $('#saveStatus').attr({'src':"img/saved.png",'alt':altText,'title':altText});
        var radios = [];
        var d = new Date();
        var thisMonth = dtc.lib.getFullMonth(d) + " " + d.getFullYear();
        $(document).find(':input').each(function() {
          switch(this.type) {
            case 'textarea':
              if (thisMonth == $('#surveyInfo').text()) {
                $('textarea#'+this.id).change(function () { 
                  wvmmsurvey.act.save(this.id.match(/[0-9]+/g),'textarea',$('textarea#'+this.id).val(),$('#suid').val()); 
                });
              } else {
                $('textarea#'+this.id).attr('readonly','readonly');
              }
              break;
            case 'radio':
              if (dtc.lib.findStrInArray(this.id,radios) == -1) {
                if (thisMonth == $('#surveyInfo').text()) {
                  $('input:radio[name='+this.id+']').click(function() {
                    wvmmsurvey.act.save(this.id.match(/[0-9]+/g),'radio',$('input:radio[name='+this.id+']:checked').val(),$('#suid').val()); 
                  });
                  radios.push(this.id);
                } else {
                  $('input:radio[name='+this.id+']').attr('disabled', 'disabled');
                }
              }
              break;
          }
        });
        // Swapping CSS buttons for images in browsers that do not support advanced features
        (!$.support.opacity) && $('.btnimg').css('display','block');
        (!$.support.opacity) && $('.btntext').css('display','none');
        (!$.support.opacity) && $('a').removeClass('large button wvorange');
        // Print button
        var popupOptions = 'height=600,width=800,directories=no,location=no,menubar=no,status=no,'
                 + 'titlebar=no,toolbar=no,resizable=yes,scrollbars=yes';
        $('#print').click(function () { window.open('print.php?email=<?php echo $_GET['email']; ?>&muid=<?php echo $_GET['muid']; ?>&store=<?php echo $_GET['store']; ?>&suid='+$('#suid').val(),'_blank',popupOptions); });
      });
    </script>
  </head>
  <body class="body">
    <div>
      <div class="header">
        <div class="headerleft"><img id="saveStatus" src=""></img></div>
        <div class="headercenter"><h1>Market Manager Survey</h1></div>
        <div class="headerright"><a href="javascript:;" id="print"><img id="printButton" src="img/print.png" alt="Print Survey" title="Print Survey" border=0></img></a></div>
      </div>
      <div id="staticContent">
        <div id="surveyInfo" class="survey-heading"><!--Dynamically populated --></div>
        <input type="hidden" id="muid" value="<?php echo $_GET['muid']; ?>">
        <input type="hidden" id="suid" value="">
        <hr>
        <table style="padding-left: 10px;">
          <tr>
            <td class="survey-question" style="padding-left: 0px;">Store:</td>
            <td class="survey-question">Surveyor:</td>
            <td class="survey-question">Last Saved:</td>
          </tr>
          <tr>
            <td class="survey-question" style="padding-left: 0px;">
              <div id="storeVisited"><!-- Dynamically populated --></div>
            </td>
            <td class="survey-question">
              <div id="surveyor"><!-- Dynamically populated --></div>
            </td>
            <td class="survey-question">
              <div id="modifiedDate"><!-- Dynamically populated --></div>
            </td>
          </tr>
        </table>
        <br><div class="survey-heading">Store Rating</div>
        <hr>
        <div id="ratingDiv"><!-- Dynamically populated --></div>
      </div>
      <div id="dynamicContent">
        <!-- Dynamically populated -->
      </div>
      <div id="close">
        <p class="center"><a href="https://wirelessvision.sharepoint.com/sales/mmsurvey/default.aspx" class="large button wvorange"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a></p>
      </div>
    </div>
  </body>
</html>
