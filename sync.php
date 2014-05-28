<!DOCTYPE html>
<html>
  <head>
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="https://wv.datatechcafe.com/wvmmsurvey.css">
    <link rel="stylesheet" href="https://wv.datatechcafe.com/dtclib/css/messi.css">
    <script src="https://wv.datatechcafe.com/dtclib/js/messi.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#syncbtn').click( function () {
          new Messi.img('The sync process is starting', {
            title: 'Please wait ....',
            buttons: [{id: 0, label: 'OK', val: ''}],
            modal: true
          });
          $('#beginsync').css('display','none');
          $('#iframesync').css('display','block');
          wvmmsurvey.sharepoint.pass('stores','https://wv.datatechcafe.com/syncrun.php',JSON.stringify(wvmmsurvey.sharepoint.stores()));
        });
      });
    </script>
  </head>
  <body>
    <div id="beginsync">
      <p class="center"><a id="syncbtn" href="javascript:;"><img src="https://wv.datatechcafe.com/img/sync.png" class="btnimg"></a></p>
    </div>
  </body>
</html>
