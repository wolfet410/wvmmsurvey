<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Wireless Vision Market Manager Survey</title>
    <link rel="stylesheet" href="/dtclib/css/buttons.css">
    <link rel="stylesheet" href="wvmmsurvey.css">
    <script src="/dtclib/js/jquery-1.8.2.js"></script>
    <script src="/dtclib/js/dtc.js"></script>
    <?php echo '<script src="wvmmsurvey.js?' . time() . '"></script>'; ?>
    <script>
      $(document).ready(function() {
      });
    </script>
  </head>
  <body class="body">
    <div>
      <?php
        // DTC specific includes
        require "/var/www/wv.datatechcafe.com/constants-wv.inc";
        require "/var/www/wv.datatechcafe.com/dtclib/php/library.php";

        // Initializing variables and open DB
        fnOpenDatabase($DBSERVER,$DBUSER,$DBPASSWD,$DB);

        $q = "TRUNCATE TABLE Stores";
        $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: syncrun.php error in truncate stores");
        $arr = json_decode($_POST['stores'], true);
        foreach ($arr as $v) {
          $q = "INSERT INTO Stores (`sap`,`desc`,`market`,`region`) VALUES ('"
             . $v['Title'] . "','" . mysql_real_escape_string($v['Description']) ."','" . mysql_real_escape_string($v['Market']) . "','"
             . mysql_real_escape_string($v['Region']) . "');";
          $r = mysql_query($q) or fnErrorLog("WVMMSURVEY: syncrun.php error adding store:" . mysql_error());
        }
        print("All done syncing Store list to database!");
      ?>
    </div>
  </body>
</html>
