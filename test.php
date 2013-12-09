<?php
require "/var/www/lib/php/library.php";

$months[] = "Jan-13";
$months[] = "Feb-13";

$ans[] = array("Region","Market","SAP","Store","Jan-13","Feb-13");
$ans[] = array("East","Stl2","109","Green","80%","60%");
$ans[] = array("East","Stl2","110","Blue","20%","50%");
$ans[] = array("East","Stl3","111","Red","40%","80%");
$ans[] = array("East","Stl3","112","Orange","N/A","N/A");
$ans[] = array("East","Stl3","113","Green","80%","100%");
$ans[] = array("West","Chi1","114","Black","10%","80%");
$ans[] = array("West","Chi2","115","White","90%","80%");
$ans[] = array("West","Chi2","116","Gold","80%","20%");

$totalCols = range(4,3+count($months));
// $triggerCols = array("0","1");
$triggerCols = array("2");

$out = arraySubAvg($ans,$triggerCols,$totalCols,true);

foreach ($out as $o) {
	echo("<br>");
	print_r($o);
}

function arraySubAvg($in,$triggerCols,$totalCols,$skipHeader=false) {
	// Returns an array with the average calculations as new rows in array
	// $in = array of arrays to sub average, $triggerCols = array of a list of columns
	// to trigger a calculation, $totalCols = array of a list of columns to calculate
	$out = array();
	// Init total and count vars
	foreach($triggerCols as $t) {
		foreach($totalCols as $c) { 
			$totals[$t][$c] = 0; 
			$counts[$t][$c] = 0;
			$finalTotals[$c] = 0;
			$finalCounts[$c] = 0;
		}
	}
	foreach ($in as $row) {
		// Skipping header row calculation if true
		if ($skipHeader) { $skipHeader = false; continue; }
		foreach ($triggerCols as $t) {
			// Checking trigger columns for changes
			if (!isset($prevTrigger[$t])) { $prevTrigger[$t] = $row[$t]; }
			if ($row[$t] != $prevTrigger[$t]) { 
				// Change has occurred on column $t, populate $out with Averages row
				$avg = array();
				for ($i=0;$i<$t;$i++) { array_push($avg,""); } // Forcing correct header row column
				array_push($avg,$prevTrigger[$t]." Averages:");
				foreach ($totalCols as $c) {
					// Calculate averages
					$calc = $counts[$t][$c] > 0 ? intval($totals[$t][$c] / $counts[$t][$c]) : 0;
					for ($i=count($avg);$i<$c;$i++) { array_push($avg,""); } // Forcing calc to the correct col
					array_push($avg,$calc."%");
					$totals[$t][$c] = $counts[$t][$c] = 0;
				}
				$out[] = $avg;
				$prevTrigger[$t] = $row[$t];
			}
			// Calculations
			foreach($totalCols as $c) {
				// For each column we've been told to calculate, add the values to a running total
				// for later calculation
				$digits = preg_replace("/[^0-9]/","",$row[$c]);
				$totals[$t][$c] += $digits;
				$finalTotals[$c] += $digits;
				// Count how many columns have true values
				$counts[$t][$c] += $digits >= 0 && $digits != '' ? 1 : 0;
				$finalCounts[$c] += $digits >= 0 && $digits != '' ? 1 : 0;
			}
		}
		// Writing the current row to $out
		$out[] = $row;
	}
	// Showing sub averages only if more than one triggerCols exists
	if (count($triggerCols) > 1) {
		foreach (array_reverse($triggerCols) as $t) {
			// Subtotal every column because we are at the end of the array
			$avg = array();
			for ($i=0;$i<$t;$i++) { array_push($avg,""); } // Forcing correct header row column
			array_push($avg,$in[count($in)-1][$t]." Averages:");
			foreach ($totalCols as $c) {
				// Calculate averages
				$calc = $counts[$t][$c] > 0 ? intval($totals[$t][$c] / $counts[$t][$c]) : 0;
				for ($i=count($avg);$i<$c;$i++) { array_push($avg,""); } // Forcing calc to the correct col
				array_push($avg,$calc."%");
				$totals[$t][$c] = $counts[$t][$c] = 0;
			}
			$out[] = $avg;
		}
	}
	// Creating final subtotal, for entire array
	$avg = array();
	array_push($avg,"Total Averages:");
	foreach ($totalCols as $c) {
		// Calculate final averages
		$calc = $finalCounts[$c] > 0 ? intval($finalTotals[$c] / $finalCounts[$c]) : 0;
		for ($i=count($avg);$i<$c;$i++) { array_push($avg,""); } // Forcing calc to the correct col
		array_push($avg,$calc."%");
	}
	$out[] = $avg;
	return $out;
}
?>