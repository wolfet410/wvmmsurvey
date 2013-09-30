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
  $email = $_SESSION['email'];
  $userCreated = safe($_POST['usercreated']);

  // Creates & stores a list of the current questions being asked are at this point in time
  $ql = "SELECT quid FROM Questions WHERE active = 'true' ORDER BY sort";
  $rl = mysql_query($ql) or fnErrorDie("WVMMSURVEY: Error 1 in createSurvey: " . mysql_error());
  $qList = '';
  while ($row = mysql_fetch_array($rl)) {
    $qList .= $row['quid'] . ",";
  }
  $qList = rtrim($qList,",");

  $q = "INSERT INTO Surveys (email,store,quids,userCreated) VALUES ('$email','$store','$qList','$userCreated')";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Error 2 in createSurvey: " . mysql_error());
  echo mysql_insert_id();
}

function makeCreateSurvey() {
  // Gets list of stores
  // SHOULDN'T BE NEEDED ANYMORE
  echo fnQueryJSON("*","Stores");
}

function makeSelectStore() {
  // Create Month entry if it doesn't already exist
  $q = "SELECT muid FROM Months WHERE datedesc = '" . date("Y-m") . "-01'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeSelectStore getting today's muid");
  if (mysql_num_rows($r) == 0) {
    // An muid for this month does not exist, so let's create one by copying
    // from the previous month
    $qp = "SELECT muid FROM Months WHERE datedesc = '" . date("Y-m",strtotime("-1 month")) . "-01'";
    $rp = mysql_query($qp) or fnErrorDie("WVMMSURVEY: makeSelectStore getting prev muid");
    $prevmonth = mysql_result($rp, 0);
    copySurvey($prevmonth,date("Y-m") . "-01");
  }
  // Return array of stores to select from
  $where = safe($_POST['where']);
  $where = $where == "all" ? "" : str_replace("\\","",$where);
  echo fnQueryJSON("*","Stores",$where,"sap");
}

function makeAdminSurvey() {
  // Populate the drop down lists for the admin survey page
  $where = safe($_POST['list']) == 'editable' ? 'datedesc > CURRENT_DATE()' : '';
  $where = safe($_POST['list']) == 'select' ? 'datedesc <= CURRENT_DATE()' : $where;
  echo fnQueryJSON("muid,DATE_FORMAT(datedesc,'%M %Y') AS monthdesc","Months",$where,"datedesc");
}

function makeEditSurvey() {
  // Gets static info for survey being edited if it exists
  // If it doesn't exist, create it
  $store = safe($_POST['store']);
  $muid = safe($_POST['muid']);
  $q = "SELECT suid,email,Surveys.store,Stores.desc,Surveys.muid,systemLastModified,Months.datedesc as datedesc FROM Surveys INNER JOIN Months ON "
     . "Surveys.muid = Months.muid INNER JOIN Stores ON Surveys.store = Stores.sap WHERE Surveys.store='$store' AND Surveys.muid='$muid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeEditSurvey getting survey info");
  if (mysql_num_rows($r) > 0) {
    // Survey exists, ship it
    while ($a = mysql_fetch_assoc($r)) {
      $s[] = $a;
    }
    echo json_encode($s);
  } else {
    // Survey doesn't exist, create it
    $q = "INSERT INTO Surveys (email,store,muid) VALUES ('".$_SESSION['email']."','$store','$muid')";
    $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeEditSurvey creating survey");
    $suid = mysql_insert_id();
    // Getting the data from the database instead of building JSON by hand to get correct systemLastModified
    echo fnQueryJSON("suid,email,Surveys.store,Stores.desc,Surveys.muid,systemLastModified,Months.datedesc as datedesc",
      "Surveys INNER JOIN Months ON Surveys.muid = Months.muid INNER JOIN Stores ON Surveys.store = Stores.sap","Surveys.store='$store' AND Surveys.muid='$muid'");
  }
}

function makeSurveyQuestions() {
  // Queries Questions table, returns JSON
  $muid = safe($_POST['muid']);
  $q = "SELECT quids FROM Months WHERE muid = $muid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems getting quids from Months: " . mysql_error());
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

function makeCompPercent() {
  $store = safe($_POST['store']);
  $muid = safe($_POST['muid']);
  if ($muid == 'current') {
    // If $muid = 'current' then we need to find the real muid for the current month
    $q = "SELECT muid FROM Months WHERE datedesc = '" . date("Y-m") . "-01'";
    $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeCompPercent getting muid");
    $muid = mysql_result($r, 0);
  }
  $q = "SELECT suid FROM Surveys WHERE store = '$store' AND muid = '$muid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeCompPercent getting suid");
  if (mysql_num_rows($r) == 0) { echo 0; die; }
  $suid = mysql_result($r, 0);
  $q = "SELECT quids FROM Months WHERE muid = '$muid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeCompPercent getting quids");
  if (mysql_num_rows($r) == 0) { echo 0; die; }
  $quids = mysql_result($r, 0);
  $arrQuids = explode(",", $quids);
  $quidTotal = $quidCount = 0;
  foreach($arrQuids as $quid) {
    // Work depending on question type
    $q = "SELECT type FROM Questions WHERE quid = '$quid'";
    $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeCompPercent getting type");
    if (mysql_num_rows($r) == 0) { echo 0; die; }
    $type = mysql_result($r, 0);
    if ($type == 'radio' || $type == 'textbox') {
      // Only care about radio or textbox types
      $quidTotal++;
      $type = $type == 'textbox' ? 'textarea' : $type;
      $q = "SELECT COUNT(*) FROM Results WHERE suid = '$suid' AND quid = '$quid' AND $type <> ''";
      $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeCompPercent getting count".mysql_error());
      (mysql_result($r, 0) > 0) && $quidCount++;
    }
  }
  echo intval(($quidCount/$quidTotal)*100);
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
  $muid = safe($_POST['muid']);
  $oldquid = safe($_POST['oldquid']);
  $table = isset($_POST['table']) && !empty($_POST['table']) ? safe($_POST['table']) : 'false';
  $rated = isset($_POST['rated']) && !empty($_POST['rated']) ? safe($_POST['rated']) : 'false';
  $type = safe($_POST['type']);
  $text = isset($_POST['text']) && !empty($_POST['text']) ? safe($_POST['text']) : '';
  $answers = isset($_POST['answers']) && !empty($_POST['answers']) ? safe($_POST['answers']) : '';
  $answers == 'undefined,undefined,undefined,undefined' && $answers = '';
  $notes = isset($_POST['notes']) && !empty($_POST['notes']) ? safe($_POST['notes']) : 'false';
  $notestext = isset($_POST['notestext']) && !empty($_POST['notestext']) ? safe($_POST['notestext']) : '';
  // Get list of original QUIDs from Months so we can modify it with the new changes
  $q = "SELECT quids FROM Months WHERE muid = $muid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: actWriteQuestions Problems getting quids from Months");
  $oldMonthsQuids = mysql_result($r, 0);
  $q = "INSERT INTO Questions (`table`, `rated`, `type`, `text`, `answers`, `notes`, `notestext`) "
     . "VALUES ('$table', '$rated', '$type', '".mysql_real_escape_string($text)."', '".mysql_real_escape_string($answers)
     . "', '$notes', '".mysql_real_escape_string($notestext)."')";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: Problems writing questions: " . mysql_error());
  $newquid = mysql_insert_id();
  $arrQuids = explode(",",$oldMonthsQuids);
  foreach($arrQuids as &$v) {
    $v = $v == $oldquid ? $newquid : $v;
  }
  unset($v);
  $newMonthsQuids = implode(",",$arrQuids);
  $q = "UPDATE Months SET quids = '$newMonthsQuids' WHERE muid = $muid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: actWriteQuestions Writing new quids to Months"); 
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
  $muid = safe($_POST['muid']);
  $quid = safe($_POST['quid']);
  $direction = safe($_POST['direction']);
  ($direction != 'up' && $direction != 'down') && fnErrorDie("WVMMSURVEY: Invalid direction during rowSwap");
  
  // Get current QUID list
  $q = "SELECT quids FROM Months WHERE muid = $muid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: rowSwap getting quids from Months");
  $oldQuids = mysql_result($r, 0);
  $arrQuids = explode(",",$oldQuids);

  // Swap Quids appropriately
  $n = 0;
  foreach($arrQuids as $k => $v) {
    switch($direction) {
      case 'up': 
        if ($v == $quid) {
          if ($n > 0) {
            $t = $arrQuids[$n-1];
            $arrQuids[$n-1] = $arrQuids[$n];
            $arrQuids[$n] = $t;
          }
        }
        break;
      case 'down':
        if ($v == $quid) {
          if ($n < (count($arrQuids)-1)) {
            $t = $arrQuids[$n+1];
            $arrQuids[$n+1] = $arrQuids[$n];
            $arrQuids[$n] = $t;
          }
        }
        break;
    }
    $n++;
  }
  $newQuids = implode(",",$arrQuids);

  // Write back to Months
  $q = "UPDATE Months SET quids = '$newQuids' WHERE muid = '$muid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: rowSwap setting quids in Months");

  // Sending the quid of the row that was swapped, for scrolling
  echo $quid;  
}

function updateOutput() {
  // Updates the output table with data from the results table
  // Populate the output table with every survey, each question in the survey, the question text and notestext
  // Then go through the output table and populate each answer
  $q = "TRUNCATE TABLE Output";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: updateOutput problems truncating output");
  $q = "SELECT suid,email,store,Surveys.muid,systemLastModified,Months.datedesc,Months.quids FROM Surveys "
     . "INNER JOIN Months ON Months.muid = Surveys.muid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: updateOutput errors getting Surveys");
  while ($survey = mysql_fetch_assoc($r)) {
    $month =  date("F Y",strtotime($survey['datedesc']));
    $qarray = explode(",",$survey['quids']);
    foreach ($qarray as $v) {
      $q2 = "SELECT type,text,notestext FROM Questions WHERE quid = '$v'";
      $r2 = mysql_query($q2) or fnErrorDie("WVMMSURVEY: updateOutput errors getting quid text");
      $type = mysql_result($r2,0);
      $qtext = mysql_real_escape_string(mysql_result($r2,0,1));
      $notestext = mysql_real_escape_string(mysql_result($r2,0,2));
      switch ($type) {
        case "textbox":
          $qs = "SELECT `desc` FROM Stores WHERE sap = '".$survey['store']."'";
          $qr = mysql_query($qs) or fnErrorDie("WVMMSURVEY: updateOutput getting store desc");
          $storedesc = mysql_result($qr, 0);
          $qt = "SELECT textarea,updated FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$v' "
              . "AND suid = '".$survey['suid']."') AND quid = '$v' AND suid = '".$survey['suid']."' LIMIT 1";
          $rt = mysql_query($qt) or fnErrorDie("WVMMSURVEY: updateOutput problems getting textarea");
          if (mysql_num_rows($rt) > 0) {
            $textarea = mysql_result($rt,0);
            $response = mysql_result($rt,0,1);
            $qu = "INSERT INTO  Output (muid,suid,quid,sap,store,month,email,qtext,textarea,response) "
                . "VALUES ('".$survey['muid']."','".$survey['suid']."','$v','".$survey['store']."','$storedesc','$month','"
                . $survey['email']."','$qtext','$textarea','$response')";
            $ru = mysql_query($qu) or fnErrorDie("WVMMSURVEY: updateOutput problems inserting initial record: " . mysql_error());
          }
          break;
        case "radio":
          $textarea = $radio = '';
          $qt = "SELECT textarea,updated FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$v' "
              . "AND suid = '".$survey['suid']."' AND textarea != '') AND quid = '$v' AND suid = '".$survey['suid']."' AND textarea != '' LIMIT 1";
          $rt = mysql_query($qt) or fnErrorDie("WVMMSURVEY: updateOutput problems getting textarea for radio");
          if (mysql_num_rows($rt) > 0) {
            $textarea = mysql_result($rt,0);
            $response = mysql_result($rt,0,1);
          } else {
            $response = "0000-00-00 00:00:00";
          }
          $qr = "SELECT radio,updated FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = '$v' "
              . "AND suid = '".$survey['suid']."' AND radio != '') AND quid = '$v' AND suid = '".$survey['suid']."' AND radio != '' LIMIT 1";
          $rr = mysql_query($qr) or fnErrorDie("WVMMSURVEY: updateOutput problems getting radio");
          if (mysql_num_rows($rr) > 0) {
            $rarr = explode("~",mysql_result($rr,0));
            $radio = $rarr[0];
            if (isset($rarr[1])) { $rating = $rarr[1]; } else { $rating = ''; }
            $response = ($response > mysql_result($rr,0,1)) ? $response : mysql_result($rr,0,1);
          }
          $max = maxRatingValue($v);
          if ($textarea != '' || $radio != '') {
            $qu = "INSERT INTO Output (muid,suid,quid,sap,store,month,email,rating,maxrating,qtext,notestext,radio,textarea,response) "
                . "VALUES ('".$survey['muid']."','".$survey['suid']."','$v','".$survey['store']."','$storedesc','$month','"
                . $survey['email']."','$rating','$max','$qtext','$notestext','$radio','$textarea','$response')";
            $ru = mysql_query($qu) or fnErrorDie("WVMMSURVEY: updateOutput problems inserting initial record: " . mysql_error());
          }
          break;
      }
    }
  }
  echo 0;
}

function maxRatingValue($quid) {
  // Return maximum rating value of a question to determine averages, returns '' if there is no maximum
  $max = 0;
  $q = "SELECT answers FROM Questions WHERE quid = $quid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: updateOutput problems getting max");
  if (mysql_num_rows($r) > 0) {
    $ansarr = explode(",",mysql_result($r,0));
    foreach($ansarr as $maxv) {
      $maxa = explode("~",$maxv);
      isset($maxa[1]) && $max = $maxa[1] > $max ? $maxa[1] : $max;
    }
    $max = $max == 0 ? '' : $max;
  }
  return $max;
}

function csvBySurvey() {
  // CSV generation adapted from http://stackoverflow.com/a/12333533/1779382
  $group = explode(",",$_GET['group']);
  $type = $group[0];
  $data = $group[1];
  $fromMonth = $_GET['fromMonth'];
  $toMonth = $_GET['toMonth'];
  $fromYear = $_GET['fromYear'];
  $toYear = $_GET['toYear'];

  // Build $sapList, comma separated list of SAPs based off of $type and $data
  $storesWhere = $data == "all" ? "" : "WHERE $type = '$data'";
  $q = "SELECT sap FROM Stores $storesWhere";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: csvBySurvey building sapList");
  $sapList = "";
  while ($sapArr = mysql_fetch_assoc($r)) {
    $sapList .= "," . $sapArr['sap'];
  }
  $sapList = ltrim($sapList,',');

  $where = "SAP IN ($sapList) AND STR_TO_DATE(month,'%M %Y') >= STR_TO_DATE('$fromMonth $fromYear','%M %Y') AND "
         . "STR_TO_DATE(month,'%M %Y') <= STR_TO_DATE('$toMonth $toYear','%M %Y')";
  $ans = array(array('SAP','Store','Rating','Question Text','Button Response','Notes Text','Text Response','Respond Date/Time'));
  $q = "SELECT sap,store,rating,maxrating,qtext,radio,notestext,textarea,response FROM Output WHERE $where";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: csvBySurvey problems getting survey data:" . mysql_error());
  $ratingTotal = $ratingMaximum = 0;
  while ($a = mysql_fetch_assoc($r)) {
    $ans[] = array($a['sap'],$a['store'],$a['rating'],$a['qtext'],$a['notestext'],$a['radio'],$a['textarea'],$a['response']);
    $ratingTotal += $a['rating'];
    $ratingMaximum += $a['maxrating'];
  }
  $ratingAverage = $ratingMaximum > 0 ? $ratingTotal / $ratingMaximum : 0;
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

function makeStoreRating() {
  // Returns the store rating
  $muid = isset($_POST['muid']) ? safe($_POST['muid']) : $muid;
  $suid = isset($_POST['suid']) ? safe($_POST['suid']) : $suid;
  $ratingTotal = 0;
  $ratingMaximum = 0;
  $q = "SELECT quids FROM Months WHERE muid = '$muid'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeStoreRating errors getting muids by suid");
  $quidArray = explode(",",mysql_result($r, 0));
  foreach ($quidArray as $v) {
    // Getting the rating for this particular survey question, if one exists
    $q = "SELECT radio FROM Results WHERE updated = (SELECT MAX(updated) FROM Results WHERE quid = $v AND suid = $suid AND radio LIKE '%~%') "
       . "AND quid = $v AND suid = $suid AND radio LIKE '%~%' LIMIT 1";
    $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: makeStoreRating getting rating answer");
    if (mysql_num_rows($r) > 0) {
      $cra = explode("~",mysql_result($r, 0));
      $ratingTotal += $cra[1];
      $ratingMaximum += maxRatingValue($v);
    }
  }
  $ratingAverage = $ratingMaximum != 0 ? $ratingTotal / $ratingMaximum : 0;
  echo $ratingAverage;
}

function copySurvey($oldMuid='post',$newDateDesc='post') {
  // Copies an existing survey into a new one
  // If no parameteres were passed, get them from post
  $output = $oldMuid == 'post' ? "Survey successfully copied" : "";
  $oldMuid = $oldMuid == 'post' ? safe($_POST['oldmuid']) : $oldMuid;
  $newDateDesc = $newDateDesc == 'post' ? safe($_POST['newdatedesc']) : $newDateDesc;
  $q = "SELECT muid FROM Months WHERE datedesc = '$newDateDesc'";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: copySurvey problems checking dates");
  if (mysql_num_rows($r) > 0) {
    echo "A survey already exists for $newDateDesc"; 
    die;
  }
  $q = "SELECT quids FROM Months WHERE muid = $oldMuid";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: copySurvey problems getting old quids");
  $oldQuids = mysql_result($r,0);
  $q = "INSERT INTO Months (datedesc, quids) VALUES ('$newDateDesc', '$oldQuids')";
  $r = mysql_query($q) or fnErrorDie("WVMMSURVEY: copySurvey problems writing new survey");
  if ($output != "") { echo $output; }
}

?>

