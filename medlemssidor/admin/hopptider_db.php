<?php

// Connect to database and select "lfk_misc"
include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");


//--------------------------------------------------
// Get times
//--------------------------------------------------

  if ($_GET['action'] == 'get_times') {

    $query      = "SELECT * FROM jump_hours";
    $result     = mysql_query($query);
    $match_rows = mysql_num_rows($result);

    // Add to the ajax response string.
    $response = "";
    if ($match_rows != 0) {
      $i = 1;
      while ($row = mysql_fetch_array($result)) {
        $response .= "['start_". $i ."','". substr($row['start_time'],0,5) ."'],";
        $response .= "['stop_".  $i ."','". substr($row['stop_time'],0,5)  ."'],";
        $i++;
      }
      
      // Remove last ","
      $response  = substr($response, 0, -1);
    }

    echo($response);
  }


//--------------------------------------------------
// Set times
//--------------------------------------------------

  if ($_GET['action'] == 'set_times') {

    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_1]' WHERE weekday = 1") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_1]'  WHERE weekday = 1") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_2]' WHERE weekday = 2") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_2]'  WHERE weekday = 2") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_3]' WHERE weekday = 3") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_3]'  WHERE weekday = 3") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_4]' WHERE weekday = 4") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_4]'  WHERE weekday = 4") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_5]' WHERE weekday = 5") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_5]'  WHERE weekday = 5") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_6]' WHERE weekday = 6") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_6]'  WHERE weekday = 6") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET start_time = '$_GET[tid_start_7]' WHERE weekday = 7") or die(mysql_error());
    mysql_query("UPDATE jump_hours SET stop_time  = '$_GET[tid_stop_7]'  WHERE weekday = 7") or die(mysql_error());

    echo(true);
    
  }
    


//************************************************************************************

// Close database connection
mysql_close($connection);


?>
