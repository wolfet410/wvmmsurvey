<!DOCTYPE html>
<html>
  <head>
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="http://wvmmsurvey.buzzspace.datatechcafe.com/wvmmsurvey.css">
    <link rel="stylesheet" href="http://wvmmsurvey.buzzspace.datatechcafe.com/lib/css/messi.css">
    <script src="http://wvmmsurvey.buzzspace.datatechcafe.com//lib/js/messi.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#syncbtn').click( function () {


// NEED TO CHANGE THIS MESSI TO PROMPT USER FOR A CONFIRMATION AND TELL THEM A NEW WINDOW WILL OPEN, 
// AND TO LEAVE THE COMPUTER ALONE UNTIL IT RETURNS AN "OKAY" MESSAGE



          new Messi.img('This is going to take a while', {
            title: 'Please wait ....',
            buttons: [{id: 0, label: 'OK', val: ''}],
            modal: true
          });
          $('#beginsync').css('display','none');
          $('#iframesync').css('display','block');
          wvmmsurvey.sharepoint.pass('stores','http://wvmmsurvey.buzzspace.datatechcafe.com/syncrun.php',JSON.stringify(wvmmsurvey.sharepoint.stores()));
        });
      });
    </script>
  </head>
  <body>
    <div id="beginsync">
      <p class="center"><a id="syncbtn" href="javascript:;"><img src="http://wvmmsurvey.buzzspace.datatechcafe.com/img/sync.png" class="btnimg"></a></p>
    </div>
  </body>
</html>
