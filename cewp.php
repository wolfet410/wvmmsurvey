<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="format-detection" content="telephone=no">
    <title>Wireless Vision Market Manager Survey</title>
    <script src="https://wv.datatechcafe.com/dtclib/js/jquery-1.8.2.js"></script>
    <script src="https://wv.datatechcafe.com/dtclib/js/jquery.SPServices-2013.01.min.js"></script>
    <?php echo '<script src="https://wv.datatechcafe.com/wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
        wvmmsurvey.sharepoint.pass('auth','https://wv.datatechcafe.com/post.php',wvmmsurvey.sharepoint.isAdmin());
        $('#iframe').width($(window).width()-350);
        $(window).resize(function() { 
          $('#iframe').width($(window).width()-350); 
        });
      });
    </script>
  </head>
  <body>
    <iframe name="iframe" id="iframe" frameborder="0" style="height: 590px; width: 900px"></iframe>
  </body>
</html>
