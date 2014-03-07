<?php
/**
  Constants settings, to be used with Data Tech Cafe's standard PHP library.
  Note: Constants are only available to the calling script, they are not actually
  available to the PHP library!

  Copied from the template at /%libpath%/constants_template.php

  An example of what should appear in the calling PHP script:
  require /%programpath%/constants.inc
  require /%libpath%/library.php
**/

// Database server, usually localhost
$DBSERVER = 'localhost';

// Database name
$DB = 'wv.datatechcafe';

// Database user
$DBUSER = 'wv.datatechcafe';

// Database user password
$DBPASSWD = "wv.datatechcafe.com03072014";

// Storing values as constants
define('DBSERVER',$DBSERVER);
define('DB',$DB);
define('DBUSER',$DBUSER);
define('DBPASSWD',$DBPASSWD);
?>

