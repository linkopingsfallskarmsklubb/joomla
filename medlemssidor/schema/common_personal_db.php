<?php

  
//-------------------------------------------
// Get all
//-------------------------------------------
  
if ($_GET['action'] == 'get_all') {
    
  // Connect to database "lfk_skywin"
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");
  
  $query      = "SELECT * FROM instruktorer WHERE ". $_GET['type'] ."=1 ORDER BY fornamn";
  $result     = mysql_query($query) or die(mysql_error());
  $match_rows = mysql_num_rows($result) or die(mysql_error());
  
  // Create the ajax response string
  $response  = "";
  if ($match_rows != 0) {
    while ($row = mysql_fetch_array($result)) {
      $response .= "['id',         '". $row['id']         ."'],";
      $response .= "['fornamn',    '". $row['fornamn']    ."'],";
      $response .= "['efternamn',  '". $row['efternamn']  ."'],";
      $response .= "['sff_nummer', '". $row['sff_nummer'] ."']|";
    }
    $response = substr($response, 0, -1); // Remove last "|"
  }
  echo($response);
  
}



//------------------------------------------------
// New - get years
//------------------------------------------------

if ($_GET['action'] == 'new_get_years') {
  
  // Connect to database "lfk_skywin"
  $db = "lfk_skywin";
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");
  
  // Select all years from skywin
  $query  = "SELECT DISTINCT year FROM member WHERE year != 'NULL' ORDER BY year DESC";
  $result = mysql_query($query) or die(mysql_error());
  
  // Create the ajax response string
  $response = "";
  while ($row = mysql_fetch_array($result)) {
    $response .= $row['year'] .",";
  }
  $response = substr($response, 0, -1); // Remove last ","
  
  echo($response);
}



//-------------------------------------------
// New - get all
//-------------------------------------------
  
else if ($_GET['action'] == 'new_get_all') {

  // Connect to database "lfk_misc"
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");
  
  // First check who is already marked
  $query      = "SELECT sff_nummer FROM instruktorer WHERE ". $_GET['type'] ." = '1'";
  $result     = mysql_query($query) or die(mysql_error());
  $currentArr = array();
  $currentStr = "";
  while ($row = mysql_fetch_array($result)) {
    array_push($currentArr, $row['sff_nummer']);
    $currentStr .= $currentStr . "," . $row['sff_nummer'];
  }
  $currentStr = substr($currentStr, 1); // Remove first ","
  

  // Filter
  $filter = "";
  if ($_GET['marked'] == '1') {
    $filter = "AND MemberNo IN ($currentStr) ";
  }
  else {
    if ($_GET['lfk'] == '1') {
      $filter = "AND Club = 'LFK' ";
    }
    if (preg_match('/^\d+$/', $_GET['year'])) {
      $filter .= "AND Year >= ".$_GET['year'] ." ";
    }
  }


  // Connect to database "lfk_skywin"
  $db = "lfk_skywin";
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");
  

  // Get all entries
  $query      = "SELECT FirstName, LastName, MemberNo, Club, Year FROM member WHERE MemberNo IS NOT NULL AND MemberNo != 0 ". $filter ."  ORDER BY FirstName";
  $result     = mysql_query($query) or die(mysql_error());
  $match_rows = mysql_num_rows($result) or die(mysql_error());

  // Create the ajax response string
  $response  = "";
  if ($match_rows != 0) {
    while ($row = mysql_fetch_array($result)) {
      if (in_array($row['MemberNo'], $currentArr)) { $checked = "checked"; } else { $checked = ""; }

      // Some entries contain bad characters - remove them
      $FirstName = preg_replace ('/[^\p{L}\p{N}-]/u', '', $row['FirstName']);
      $LastName  = preg_replace ('/[^\p{L}\p{N}-]/u', '', $row['LastName']);

      // The response
      $response .= "['checked',    '". $checked         ."'],";
      $response .= "['fornamn',    '". $FirstName       ."'],";
      $response .= "['efternamn',  '". $LastName        ."'],";
      $response .= "['sff_nummer', '". $row['MemberNo'] ."'],";
      $response .= "['klubb',      '". $row['Club']     ."']|";
    }
    $response = substr($response, 0, -1); // Remove last "|"
  }
  echo($response);
}


//-------------------------------------------
// New -submit
//-------------------------------------------
  
else if ($_GET['action'] == 'new_submit') {
  // Connect to database "lfk_misc"
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");

  // First clear all
  mysql_query("UPDATE instruktorer SET ". $_GET['type'] ." = 0") or die(mysql_error());

  // Then set all current
  $allArr = explode("|", $_GET['all']);

  foreach ($allArr as $item)  {

    $tmp       = explode(",", $item);
    $first_name = $tmp[0];
    $last_name  = $tmp[1];
    $sff_nr     = $tmp[2];

    if ($sff_nr != "") {
      $query = "INSERT INTO instruktorer (
                  fornamn,
                  efternamn,
                  sff_nummer,
                  ".$_GET['type'] .")
                VALUES (
                  '$first_name',
                  '$last_name',
                  '$sff_nr',
                  '1')
                ON DUPLICATE KEY UPDATE 
                  fornamn    = VALUES(fornamn),
                  efternamn  = VALUES(efternamn),
                  sff_nummer = VALUES(sff_nummer),
                  " .$_GET['type'] ." = VALUES(". $_GET['type'] .")";
  
      $result = mysql_query($query) or die(mysql_error());
    }  
  }
  echo("ok");
}



// Close database connection
mysql_close($connection);


?>
