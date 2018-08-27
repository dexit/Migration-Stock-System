<?php

//get a two dimensional array from the csv that a report "Detail" returns
function get2DimFromCSV($csv) {
	$lines = explode("\n", $csv); //explode by a new line, so you can get every line first

	$head = str_getcsv(array_shift($lines));

	$array = array();
	foreach ($lines as $line) {
		$row = array_pad(str_getcsv($line), count($head), '');
		$array[] = array_combine($head, $row);
	}
	return $array;
}

//Filter a two dimensional array extracted from a report "Detail"; returns a two dimensional array with only the wanted data
function filterArray($column, $value, $twoDimArray, $comparison = "equals") {
	$filtered_array = array();
	foreach ($twoDimArray as $a) {
		if ($comparison == "equals" || $comparison == "Equals") { // if the column should be equal to the value
			if ($a[$column] == $value) {
				 $filtered_array[] = $a; //add to the filtered array
			}
		}else if ($comparison == "not equals" || $comparison == "Not equals") { // if the column should not be equal to the value
			if ($a[$column] != $value) {
				 $filtered_array[] = $a; //add to the filtered array
			}	
		}else {
			echo "No comparison set!";
		}
	}
	return $filtered_array; //returned the array with only the filtered data
}

//filters a multidimensional array, but the $value parameter is actually ANOTHER COLUMN VALUE
//example:
//Runing "Item movement history and saying I want the value of column "FROM" to not be equal to the value of column "TO" (because of duplicate rows problem in the report) 
function filterArrayByColumnValue($column, $value, $twoDimArray, $comparison = "equals") {
	$filtered_array = array();
	foreach ($twoDimArray as $a) {
		if ($comparison == "equals" || $comparison == "Equals") { // if the column should be equal to the value
			if ($a[$column] == $a[$value]) {
				 $filtered_array[] = $a; //add to the filtered array
			}
		}else if ($comparison == "not equals" || $comparison == "Not equals") { // if the column should not be equal to the value
			if ($a[$column] != $a[$value]) {
				 $filtered_array[] = $a; //add to the filtered array
			}	
		}else {
			echo "No comparison set!";
		}
	}
	return $filtered_array; //returned the array with only the filtered data
}

//return an array where duplicates are removed --- first parameter is the array, second parameter the column
//from which you want to remove the duplicated values
function super_unique($array,$key){
       $temp_array = [];
       foreach ($array as &$v) {
           if (!isset($temp_array[$v[$key]]))
           $temp_array[$v[$key]] =& $v;
       }
       $array = array_values($temp_array);
       return $array;

}

//FULL LOCATION CHECKER. TAKES A STANDART LOCATION, CHECKS IF ALL DOT SEPARATED PARTS
function getFullLocation($location, $needle = 'R') {
	
	$needle_position = strpos($location, $needle);
	
	if ($needle_position === false) {
		return $needle . '.' . $location;
	}else if ($needle_position == 0) {
		return $location;
	}
	
}

//CHECK IF LOCATION IS PICK LOCATION
function checkIfPickLoc($location) {
	$check_if_pick = strpos($location, '.PICK');

	if ($check_if_pick == true) {
		return true;
	}else {
		return false;
	}
}




