<?php
function csvCompPerc() {
  // CSV generation adapted from http://stackoverflow.com/a/12333533/1779382
  $group = explode(",",$_GET['group']);
  $type = $group[0];
  $data = $group[1];
  $fromMonth = $_GET['fromMonth'];
  $toMonth = $_GET['toMonth'];
  $fromYear = $_GET['fromYear'];
  $toYear = $_GET['toYear'];
  // $months will contain a list of all of the months we are reporting against
  $months = array();

  // Create $ans array populated with report data
  $ans = array(array("Completion Percetage"));
  $ans[] = array("Date Range:",$fromMonth." ".$fromYear,$toMonth." ".$toYear);
  $ans[] = array('--------------');
  $columns = array();
  $columns[] = "SAP";
  $columns[] = "Store Name";
  // Create a new column for every month between the From and To range selected
  // Adapted from: http://phphelp.co/2012/03/28/how-to-print-all-the-months-and-years-between-two-dates-in-php/
  $time1 = strtotime($fromMonth."-".$fromYear);
  $time2 = strtotime($toMonth."-".$toYear);
  $columns[] = date('F Y', $time1);
  $months[] = date('F Y', $time1);
  $to = date('mY', $time2);
  while ($time1 < $time2) {
    $time1 = strtotime(date('Y-m-d', $time1).' +1 month');
    if(date('mY', $time1) != $to && ($time1 < $time2)) {
      $columns[] = date('F Y', $time1);
      $months[] = date('F Y', $time1);
    }
  }
  $columns[] = date('F Y', $time2);
  $months[] = date('F Y', $time2);
  // Add the contents of the $columns array as one row to $ans array
  $ans[] = $columns;
  $storesWhere = $data == "all" ? "" : "WHERE $type = '$data'";
  $q = "SELECT sap,`desc` FROM Stores $storesWhere";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: csvCompPerc building $sapArr");
  while ($sapArr = mysql_fetch_array($r)) {
    // For each SAP, for each $months, check the comp % and add it to $ans[]
    $columns = array();
    $columns[] = $sapArr['sap'];
    $columns[] = $sapArr['desc'];
    foreach($months as $month) {
      $t = strtotime($month);
      $qm = "SELECT muid FROM Months WHERE datedesc='".date('Y-m-d', $t)."'";
      $rm = mysql_query($qm) or fnErrorDie("WVMMSURVEY: csvCompPerc getting muid");
      $muid = mysql_num_rows($rm) > 0 ? mysql_result($rm,0) : 'none';
      $columns[] = makeCompPercent($sapArr['sap'],$muid,'yes')."%";
    }
    $ans[] = $columns;
  }

  // Create CSV file
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header('Content-type: text/csv');
  header("Content-Disposition: attachment;filename=file.csv");
  header("Content-Transfer-Encoding: binary");

  $fp = fopen('php://output', 'a');
  foreach ($ans as $fields) {
      fputcsv($fp, $fields);
  }
  fclose($fp);
}
?>