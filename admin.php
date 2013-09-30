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
        $.ajax({
          url: "wvmmsurvey.php",
          type: 'POST',
          data: { 
            todo: "makeAdminSurvey",
            list: "existing"
          },
          cache: false,
          async: false,
          dataType: 'json',
          success: function(arr) {
            var html = '<select id="existingSurveys" name="existingSurveys" class="chzn-select" data-placeholder="Select a survey..." style="width:200px;">';
            html += '<option value=""></option>';
            $.each(arr, function(k,v){
              if (v['suid'] !== '') {
                 html += '<option value="' + v['muid'] + '">' + v['monthdesc'] + '</option>';
              } 
            });
            html += '</select>';
            $('#existingDiv').empty();
            $(html).appendTo('#existingDiv');
            $(".chzn-select").chosen();
          }
        });
        $.ajax({
          url: "wvmmsurvey.php",
          type: 'POST',
          data: { 
            todo: "makeAdminSurvey",
            list: "editable"
          },
          cache: false,
          async: false,
          dataType: 'json',
          success: function(arr) {
            var html = '<select id="editableSurveys" name="editableSurveys" class="chzn-select" data-placeholder="Select a survey..." style="width:200px;">';
            html += '<option value=""></option>';
            $.each(arr, function(k,v){
              if (v['suid'] !== '') {
                 html += '<option value="' + v['muid'] + '">' + v['monthdesc'] + '</option>';
              } 
            });
            html += '</select>';
            $('#editableDiv').empty();
            $(html).appendTo('#editableDiv');
            $(".chzn-select").chosen();
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
      <div class="header">
        <div class="headerleft"><img id="saveStatus" src="" style="display: none;"></img></div>
        <div class="headercenter"><h1>Market Manager Survey</h1></div>
        <div class="headerright"><img id="printButton" src="" style="display: none;"></img></div>
      </div>
      <div class="survey-heading">Editable Surveys</div>
      <hr>
      <div class="survey-question">Select a future survey from the list below to edit</div>
      <form id="editableForm" action="change.php" method="post">
        <span id="editableDiv" style="padding-left: 10px;"><!-- Dynamically populated --></span>
        <a href="javascript:;" class="large button wvorange" 
          onclick="if ($('#editableSurveys').val() == '') {
                    new Messi('Please select a survey first!', {
                      title: 'Error',
                      buttons: [{id: 0, label: 'OK', val: ''}],
                    });
                   } else {
                    $('#editableForm').submit();
                   }">
          <img src="img/changesurvey.jpg" class="btnimg" style="display:none;"><span class="btntext">Change Survey</span>
        </a>
      </form>
      <br><br><br>
      <div class="survey-heading">Existing Surveys</div>
      <hr>
      <div class="survey-question">Use the list below to select a survey to use as a basis for a new survey:</div>
      <form id="existingForm" action="copypopup.php" method="post" target="copypopup" 
        onsubmit="window.open('','copypopup','height=600,width=800,directories=no,location=no,menubar=no,status=no,titlebar=no,toolbar=no,resizable=yes,scrollbars=yes');">
        <span id="existingDiv" style="padding-left: 10px;"><!-- Dynamically populated --></span>
        <a href="javascript:;" class="large button wvorange"
        onclick="if ($('#existingSurveys').val() == '') {
                    new Messi('Please select a survey first!', {
                      title: 'Error',
                      buttons: [{id: 0, label: 'OK', val: ''}],
                    });
                   } else {
                    $('#existingForm').submit();
                   }">
          <img src="img/copysurvey.jpg" class="btnimg" style="display:none;"><span class="btntext">Copy Survey</span>
        </a>
        <br><p class="center">
          <a href="/cewp.php" class="large button wvorange"><img src="img/close.jpg" class="btnimg" style="display:none;"><span class="btntext">Close</span></a>
        </p>
      </form>
    </div>
  </body>
</html>
