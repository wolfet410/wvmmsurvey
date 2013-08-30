<?php
session_start();
header('Cache-control: private');

// DTC specific includes
require "/var/www/constants-wv.inc";
require "/var/www/lib/php/library.php";

// Initializing variables and open DB
fnOpenDatabase($DBSERVER,$DBUSER,$DBPASSWD,$DB);

if (isset($_GET['report'])) { 
  call_user_func(safe($_GET['report'])); 
} else {
  call_user_func(safe($_POST['todo']));
}

function createSurvey() {
  // Creates survey record
  $store = safe($_POST['store']);
  $userCreated = safe($_POST['usercreated']);

  // Creates & stores a list of the current questions being asked are at this point in time
  $ql = "SELECT quid FROM Questions WHERE active = 'true' ORDER BY sort";
  $rl = mysql_query($ql) or fnErrorDie("WVMMSURVEY: Error 1 in createSurvey: " . mysql_error());
  $qList = '';
  while ($row = mysql_fetch_array($rl)) {
    $qList .= $row['quid'] . ",";
  }
  $qList = rtrim($qList,",");

  $q = "INSERT INTO Surveys (store,quids,userCreated) VALUES ('$store','$qList','$userCreated')";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Error 2 in createSurvey: " . mysql_error());
  echo mysql_insert_id();
}

function makeSelectSurvey() {
  // Gets list of surveys to be selected
  fnQueryJSON("suid,store,userCreated","Surveys","","store");
}

function makeEditSurvey() {
  // Gets static info for survey being edited
  fnQueryJSON("suid,store,userCreated,systemLastModified","Surveys","suid=".safe($_POST['suid']));
}

function makeSurveyQuestions() {
  // Queries Questions table, returns JSON
  $suid = safe($_POST['suid']);
  if ($suid == '0') {
    // $suid is 0 when we are changing the survey questions
    fnQueryJSON("*","Questions","active='true'","sort");
  } else {
    // Otherwise $suid is set to the survey uid that we are editing
    $q = "SELECT quids FROM Surveys WHERE suid = $suid";
    $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting quids from Survey: " . mysql_error());
    $quids = mysql_result($r,0);
    $quidArray = explode(",",$quids);
    $s[] = '';
    foreach ($quidArray as $v) {
      $q = "SELECT * FROM Questions WHERE quid = $v";
      $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting question row $v: " . mysql_error());
      while ($a = mysql_fetch_assoc($r)) {
        $s[] = $a;
      }
    }
    if (isset($s)) echo json_encode($s);
  }
}

function makeGetAnswers() {
  // Get answers to each question to populate each question's answer
  $suid = safe($_POST['suid']);
  $quid = safe($_POST['quid']);
  $type = safe($_POST['type']);
  $q = "SELECT * FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$quid' AND suid = '$suid' AND $type != '') AND "
     . "quid = '$quid' AND suid = '$suid' AND $type != '' LIMIT 1";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting answers: " . mysql_error());
  while ($a = mysql_fetch_assoc($r)) {
    $s[] = $a;
  }
  if (isset($s)) { echo json_encode($s); } else { echo 0; }
}

function actWriteResults() {
  // Writes survey results to database
  $out = '0';
  $quid = safe($_POST['quid']);
  $suid = safe($_POST['suid']);
  $type = safe($_POST['type']);
  $value = safe($_POST['value']);

  $q = "INSERT INTO  `Results` (`suid`,`quid`,`$type`) VALUES ('$suid','$quid','$value');";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems writing survey results: " . mysql_error());
  if (mysql_affected_rows() > 0) {
    $ts = date("Y-m-d H:i:s");
    $qt = "UPDATE `Surveys` SET `systemLastModified` = '$ts' WHERE `suid` = '$suid'";
    $rt = mysql_query($qt) 
      or fnErrorDie("WVMMSURVEY: Error updating timestamp while writing survey results: " . mysql_error());
    $out = date("m/d/Y H:i:s",strtotime($ts));
  }
  echo '"'.$out.'"';
}

function actWriteQuestions() {
  // Writes question changes to database
  // Note: changes to questions create new rows in database by design, 
  // to avoid nullifying past survey results
  $oldquid = safe($_POST['oldquid']);
  $table = isset($_POST['table']) && !empty($_POST['table']) ? safe($_POST['table']) : 'false';
  $rated = isset($_POST['rated']) && !empty($_POST['rated']) ? safe($_POST['rated']) : 'false';
  $type = safe($_POST['type']);
  $text = isset($_POST['text']) && !empty($_POST['text']) ? safe($_POST['text']) : '';
  $answers = isset($_POST['answers']) && !empty($_POST['answers']) ? safe($_POST['answers']) : '';
  $answers == 'undefined,undefined,undefined,undefined' && $answers = '';
  $notes = isset($_POST['notes']) && !empty($_POST['notes']) ? safe($_POST['notes']) : 'false';
  $notestext = isset($_POST['notestext']) && !empty($_POST['notestext']) ? safe($_POST['notestext']) : '';
  // Get sort value of old row
  $q = "SELECT sort FROM Questions WHERE quid = '$oldquid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting question sort value: " . mysql_error());
  $sort = mysql_result($r,0);
  if (!$sort) fnErrorDie("WVMMSURVEY: Empty sort value");
  // Write new row
  $q = "INSERT INTO Questions (`active`, `sort`, `table`, `rated`, `type`, `text`, `answers`, `notes`, `notestext`) "
     . "VALUES ('true', '$sort', '$table', '$rated', '$type', '".mysql_real_escape_string($text)."', '".mysql_real_escape_string($answers)
     . "', '$notes', '".mysql_real_escape_string($notestext)."')";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems writing questions: " . mysql_error());
  // Deactivate old row
  $q = "UPDATE Questions SET `active` = 'false' WHERE `quid` = '$oldquid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems deactivating question: " . mysql_error());
  echo 0;
}

function rowAdd() {
  $quid = safe($_POST['quid']);
  // Get sort value of the row we are adding below
  $q = "SELECT sort FROM Questions WHERE quid = $quid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting sort value: " . mysql_error());
  $below = mysql_result($r,0);
  // Increment the sort value of all of the rows under the current row
  $q = "UPDATE Questions SET sort = sort + 1 WHERE active = 'true' AND sort > $below";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems changing sort values: " . mysql_error());
  // Add a new row at sort+1 with a new quid
  $q = "INSERT INTO Questions (`active`, `sort`, `table`, `type`, `notes`) VALUES ('true', '" . (++$below)
     . "', 'false', 'heading', 'false')";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems adding row: " . mysql_error());
  // Sending the quid of the row that is above the new row, for scrolling
  echo $quid;
}

function rowDel() {
  $quid = safe($_POST['quid']);
  // Get sort value of the row that we are deleting
  $q = "SELECT sort FROM Questions WHERE quid = $quid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting del sort value: " . mysql_error());
  $sort = mysql_result($r,0);
  // Get the quid of the row above the one we are deleting, so we can scroll to it later
  $above = 0;
  if ($sort > 2) {
    // If it's the first couple rows, we aren't going to bother scrolling anywhere
    $q = "SELECT quid FROM Questions WHERE sort = " . --$sort;
    $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting above row during del: " . mysql_error());
    $above = mysql_result($r,0);
  }
  $q = "UPDATE Questions SET active = 'false' WHERE quid = $quid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems deactivating row: " . mysql_error());
  // Sending the quid of the row that was sorted above the old row, for scrolling
  echo $above;
}

function rowSwap() {
  $quid = safe($_POST['quid']);
  $direction = safe($_POST['direction']);
  ($direction != 'up' && $direction != 'down') && fnErrorDie("WVMMSURVEY: Invalid direction during rowSwap");
  // Get sort value of the row that we are moving from & quid of the row we are moving to
  $q = "SELECT sort FROM Questions WHERE quid = $quid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting from sort value: " . mysql_error());
  $from = mysql_result($r,0);
  $q = "SELECT quid FROM Questions WHERE sort = '" . ($direction == 'up' ? $from - 1 : $from + 1) . "'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting to quid");
  $toQuid = mysql_result($r,0);
  // Swap
  $q = "UPDATE Questions SET sort = '";
  $q .= $direction == 'up' ? $from - 1 : $from + 1;
  $q .= "' WHERE quid='$quid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems changing from value: " . mysql_error());
  $q = "UPDATE Questions SET sort = '$from' WHERE quid='$toQuid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems changing to value: " . mysql_error());
  // Sending the quid of the row that was swapped, for scrolling
  echo $quid;  
}

function updateOutput() {
  // Updates the output table with data from the results table
  // Populate the output table with every survey, each question in the survey, the question text and notestext
  // Then go through the output table and populate each answer
  $q = "TRUNCATE TABLE Output";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: updateOutput problems truncating output");
  $q = "SELECT * FROM Surveys";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: updateOutput errors getting Surveys");
  while ($a = mysql_fetch_assoc($r)) {
    $qarray = explode(",",$a['quids']);
    foreach ($qarray as $v) {
      $q2 = "SELECT `type`,`text`,`notestext` FROM Questions WHERE quid = '".$v."'";
      $r2 = mysql_query($q2) or fnErrorDie("WVMMSURVEY: updateOutput errors getting quid text");
      $type = mysql_result($r2,0);
      $qtext = mysql_real_escape_string(mysql_result($r2,0,1));
      $notestext = mysql_real_escape_string(mysql_result($r2,0,2));
      switch ($type) {
        case "textbox":
          $qt = "SELECT textarea,updated FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$v' "
              . "AND suid = '".$a['suid']."') AND quid = '$v' AND suid = '".$a['suid']."' LIMIT 1";
          $rt = mysql_query($qt) or fnErrorDie("WVMMSURVEY: updateOutput problems getting textarea");
          if (mysql_num_rows($rt) > 0) {
            $textarea = mysql_result($rt,0);
            $response = mysql_result($rt,0,1);
            $qu = "INSERT INTO  `Output` (`suid`,`quid`,`store`,`qtext`,`textarea`,`response`) "
                . "VALUES ('".$a['suid']."','$v','".$a['store']."','$qtext','$textarea','$response')";
            $ru = mysql_query($qu) or fnErrorDie("WVMMSURVEY: updateOutput problems inserting initial record: " . mysql_error());
          }
          break;
        case "radio":
          $textarea = $radio = '';
          $qt = "SELECT textarea,updated FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$v' "
              . "AND suid = '".$a['suid']."' AND textarea != '') AND quid = '$v' AND suid = '".$a['suid']."' AND textarea != '' LIMIT 1";
          $rt = mysql_query($qt) or fnErrorDie("WVMMSURVEY: updateOutput problems getting textarea for radio");
          if (mysql_num_rows($rt) > 0) {
            $textarea = mysql_result($rt,0);
            $response = mysql_result($rt,0,1);
          } else {
            $response = "0000-00-00 00:00:00";
          }
          $qr = "SELECT radio,updated FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$v' "
              . "AND suid = '".$a['suid']."' AND radio != '') AND quid = '$v' AND suid = '".$a['suid']."' AND radio != '' LIMIT 1";
          $rr = mysql_query($qr) or fnErrorDie("WVMMSURVEY: updateOutput problems getting radio");
          if (mysql_num_rows($rr) > 0) {
            $rarr = explode("~",mysql_result($rr,0));
            $radio = $rarr[0];
            if (isset($rarr[1])) { $rating = $rarr[1]; } else { $rating = ''; }
            $response = ($response > mysql_result($rr,0,1)) ? $response : mysql_result($rr,0,1);
          }
          // Get maximum rating value to determine averages
          $q = "SELECT answers FROM Questions WHERE quid = $v";
          $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: updateOutput problems getting max");
          if (mysql_num_rows($r) > 0) {
            $ansarr = explode(",",mysql_result($r,0));
            $max = 0;
            foreach($ansarr as $maxv) {
              $maxa = explode("~",$maxv);
              isset($maxa[1]) && $max = $maxa[1] > $max ? $maxa[1] : $max;
            }
            $max = $max == 0 ? '' : $max;
          }
          if ($textarea != '' || $radio != '') {
            $qu = "INSERT INTO  `Output` (`suid`,`quid`,`rating`,`maxrating`,`store`,`qtext`,`notestext`,`radio`,`textarea`,`response`) "
                . "VALUES ('".$a['suid']."','$v','".$rating."','".$max."','".$a['store']."','$qtext','$notestext','".$radio."','$textarea','$response')";
            $ru = mysql_query($qu) or fnErrorDie("WVMMSURVEY: updateOutput problems inserting initial record: " . mysql_error());
          }
          break;
      }
    }
  }
  echo 0;
}

function csvBySurvey() {
  // CSV generation adapted from http://stackoverflow.com/a/12333533/1779382
  $ans = array(array('Store','Rating','Question Text','Button Response','Notes Text','Text Response','Respond Date/Time'));
  $suid = safe($_GET['surveyList']);
  $q = "SELECT store,rating,maxrating,qtext,radio,notestext,textarea,response FROM Output WHERE suid = $suid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: csvBySurvey problems getting survey data");
  while ($a = mysql_fetch_assoc($r)) {
    $ans[] = array($a['store'],$a['rating'],$a['qtext'],$a['notestext'],$a['radio'],$a['textarea'],$a['response']);
    $ratingTotal += $a['rating'];
    $ratingMaximum += $a['maxrating'];
  }
  $ratingAverage = $ratingTotal / $ratingMaximum;
  $ans[] = array("Average Rating: ",intval($ratingAverage*100)."%");

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

function printRating() {
  // Has to rebuild Output table, this is not ideal
  updateOutput();
  $suid = safe($_POST['suid']);
  // Get's and sends average rating back
  $q = "SELECT rating,maxrating FROM Output WHERE suid = $suid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: csvBySurvey problems getting survey data");
  while ($a = mysql_fetch_assoc($r)) {
    $ans[] = array($a['store'],$a['rating'],$a['qtext'],$a['notestext'],$a['radio'],$a['textarea'],$a['response']);
    $ratingTotal += $a['rating'];
    $ratingMaximum += $a['maxrating'];
  }
  $ratingAverage = $ratingTotal / $ratingMaximum;
  echo $ratingAverage;
}

?>

