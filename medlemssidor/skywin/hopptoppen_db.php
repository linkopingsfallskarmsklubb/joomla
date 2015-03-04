<?php

  //------------------------------------------------
  // Connect to Skywin database
  //------------------------------------------------

  $db = "lfk_skywin";
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");


  //------------------------------------------------
  // Fetch all years
  //------------------------------------------------

  if ($_GET['action'] == 'get_years') {

    $query  = "SELECT DISTINCT year FROM member WHERE year != 'NULL' ORDER BY year DESC";
    $result = mysql_query($query) or die(mysql_error());

    $response = "";
    while ($row = mysql_fetch_array($result)) {
      $response .= $row['year'] .",";
    }
    $response = substr($response, 0, -1); // Remove last ","

    echo($response);
  }


  //------------------------------------------------
  // Fetch 'hopptoppen'
  //------------------------------------------------

  if ($_GET['action'] == 'get_all') {

    $year  = $_GET["year"];

    $query = "SELECT t1.LoadNo, 
                     t1.RegDate,
                     t1.JumpType,
                     t1.InternalNo,
                     t2.InternalNo,
                     t2.FirstName,
                     t2.LastName,
                     COUNT(t2.InternalNo) AS fn
              FROM loadjump     AS t1
              INNER JOIN member AS t2 ON t1.InternalNo = t2.InternalNo
              WHERE YEAR(Regdate) = '". $year ."'
              GROUP BY t1.InternalNo
              ORDER BY fn DESC";

    $result = mysql_query($query) or die(mysql_error());

    $response = "";
    $i        = 1;
    while ($row = mysql_fetch_array($result)) {
      $response .= "['rank',  '". $i ."'],";
      $response .= "['name',  '". $row['FirstName'] ." ". $row['LastName'] ."'],";
      $response .= "['jumps', '". $row['fn'] ."']|";
      $i++;
    }
    $response = substr($response, 0, -1); // Remove last "|"
    
    echo($response);

  }



// Close database connection
mysql_close($connection);

?>

