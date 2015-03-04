<?php

// Connect to database and select "lfk_misc"
include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");



//------------------------------------------------------------------
// Step 1. 
//------------------------------------------------------------------

  //-------------------------------------------------------------------------
  // Get days with events
  //
  // In order to do the calender higlightning we need three types of events:
  //   1. Jumping is scheduled 
  //   2. Pilots are scheduled
  //   3. Photographersare scheduled.
  //-------------------------------------------------------------------------

  if ($_GET['action'] == 'get_event_days') {

    $date = $_GET["date"];

    //--------------------------------------------------
    // Query database
    // Dates with scheduled jumping
    //--------------------------------------------------

    $query      = "SELECT datum FROM hopp_schema  WHERE YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date')"; 
    $result     = mysql_query($query);
    $match_rows = mysql_num_rows($result);
  
    // Create the ajax response string
    $response = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= $row['datum'] .",";
      }
      // Remove last ","
      $response  = substr($response, 0, -1);
    }


    //--------------------------------------------------
    // Query database
    // Dates with scheduled pilot
    //--------------------------------------------------
    
    $query      = "SELECT t1.id_datum,
                          t2.datum 
                   FROM     tandem_schema_pilot AS t1 
                   JOIN     tandem_schema_datum AS t2 ON t1.id_datum = t2.id 
                   WHERE    YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date')
                   GROUP BY t2.datum";

    $result     = mysql_query($query);
    $match_rows = mysql_num_rows($result);
  
    // Add to the ajax response string.
    $response .= ":";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= $row['datum'] .",";
      }

      // Remove last ","
      $response  = substr($response, 0, -1);
    }


    //------------------------------------------------
    // Query database
    // Dates with photographer scheduled
    //------------------------------------------------

    $query  = "SELECT   t1.*, t2.*
               FROM     tandem_schema_foto  AS t1 
               JOIN     tandem_schema_datum AS t2 ON t1.id_datum = t2.id
               WHERE    YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date')
               GROUP BY datum"; 

    $result     = mysql_query($query);
    $match_rows = mysql_num_rows($result);
  
    // Add to the ajax response string.
    $response .= ":";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= $row['datum'] .",";
      }

      // Remove last ","
      $response  = substr($response, 0, -1);
    }


    //----------------------------------------
    // Return result
    //----------------------------------------

    echo($response);

  }


//------------------------------------------------------------------
// Step 2. 
//------------------------------------------------------------------

  //------------------------------------------------------------------
  // Get jump hours
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'jump_hours') {
    
    $wd = $_GET["wd"];

    // Query database (jump_hours)
    $query      = "SELECT start_time, stop_time FROM jump_hours WHERE weekday = '$wd'";
    $result     = mysql_query($query) or die(mysql_error());
    $row        = mysql_fetch_array($result);
    
    // Add to the ajax response string.
    $response  = "['start_time','". substr($row['start_time'],0,5) ."'],";
    $response .= "['stop_time','".  substr($row['stop_time'],0,5)  ."']";
   
    echo($response);
  }



  //------------------------------------------------------------------
  // Get pilots for current day
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'list_day') {
    
    $date = $_GET["date"];

    // Query database (hopp_schema)
    $query  = "SELECT id, datum, tid_start, tid_stop FROM hopp_schema WHERE datum = '$date'";
    $result = mysql_query($query) or die(mysql_error());
    $row    = mysql_fetch_array($result);
    
    // Add to the ajax response string.
    $response  = "['hoppning_start','". substr($row['tid_start'],0,5) ."'],";
    $response .= "['hoppning_stop','".  substr($row['tid_stop'],0,5)  ."'],";
    $response  = substr($response, 0, -1); // Remove last ","
    
    // Query database (tandem_schema)
    $query  = "SELECT *,
                      t2.id       AS p_id,
                      t2.id_pilot AS p_id_pilot
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_piloter      AS t3 ON t2.id_pilot    = t3.id 
                        LEFT JOIN tandem_pax          AS t4 ON t2.id_pax      = t4.id 
               WHERE t1.datum='$date' ORDER BY t2.tid";
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);
  
    // Add to the ajax response string.
    // The '|' is used to seperate queries in respons string
    $i         = 1;
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['id_".      $i ."','". $row['p_id'] ."'],";
        $response .= "['tid_".     $i ."','". substr($row['tid'],0,5) ."'],";
        $response .= "['pilot_".   $i ."','". $row['fornamn']  ." ". $row['efternamn'] ."'],";
        $response .= "['bokad_".   $i ."','". $row['id_pax'] ."'],";
        $i = $i +1;
      }

      // Remove last ","
      $response = substr($response, 0, -1);
    }

    echo($response);
  }

  //------------------------------------------------------------------
  // Delete time
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'delete_time_popup') {

    $tid = $_GET["tid"];

    // Query database
    $query  = "SELECT t1.id        AS s_id,
                      t1.tid       AS s_tid,
                      t2.datum     AS d_datum,
                      t3.fornamn   AS p_fornamn,
                      t3.efternamn AS p_efternamn
                        FROM tandem_schema_pilot AS t1
                        JOIN tandem_schema_datum AS t2 ON t1.id_datum = t2.id
                        JOIN tandem_piloter      AS t3 ON t1.id_pilot = t3.id
                        WHERE t1.id = '". $tid ."'";
    $result = mysql_query($query)        or die(mysql_error());
    $row    = mysql_fetch_array($result) or die(mysql_error());

    // Create the ajax response string.
    $response  = "['id','".    $row['s_id']      ."'],";
    $response .= "['tid','".   $row['s_tid']     ."'],";
    $response .= "['datum','". $row['d_datum']   ."'],";
    $response .= "['pilot','". $row['p_fornamn'] ." ". $row['p_efternamn'] ."']";
 
    echo($response);
  }


  //--------------------------------------------------------------------
  // Delete pilot booking
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'delete_time') {

    $id = $_GET["id"];

    // Delete row in 'tandem_schema_pilot'
    mysql_query("SELECT id_datum FROM tandem_schema_pilot WHERE id = '$id' INTO @id_datum");
    mysql_query("DELETE FROM tandem_schema_pilot WHERE id = '$id'") or die(mysql_error());

    // Unless anyone else is booked the same day, delete row in 'tandem_schema_datum'
    $result       = mysql_query("SELECT id FROM tandem_schema_foto WHERE id_datum = @id_datum");
    $match_rows_f = mysql_num_rows($result);
    $result       = mysql_query("SELECT id FROM tandem_schema_pilot WHERE id_datum = @id_datum");
    $match_rows_p = mysql_num_rows($result);
    if (($match_rows_f == 0) && ($match_rows_p == 0)) {
      mysql_query("DELETE FROM tandem_schema_datum WHERE id = @id_datum");
    }

    echo(true);
  } 


  //------------------------------------------------------------------
  // Is scheduled?
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'is_scheduled') {

    $datum = $_GET["date"];
    $pilot = $_GET["pilot"];

    // Query database
    $query  = "SELECT t1.*,
                      t2.id_pilot AS p_id_pilot
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_piloter      AS t3 ON t2.id_pilot    = t3.id 
               WHERE (t3.id='$pilot' AND t1.datum='$datum')";
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);
    $response   = true ? $match_rows > 0 : false;

    echo($response);

  }


//------------------------------------------------------------------
// Step 3. 
//------------------------------------------------------------------

  //------------------------------------------------------------------
  // Get default time between jumps
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'new_times') {

    $id = $_GET['pilot'];

    // Query database
    $query      = "SELECT * FROM tandem_piloter WHERE id = $id";
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);
  
    // Add to the ajax response string.
    $i        = 1;
    $response = "";
    if ($match_rows != 0) {
      $row    = mysql_fetch_array($result) or die(mysql_error());
      $response .= "['id_pilot','". $row['id']         ."'],";
      $response .= "['inc','".      $row['tid_mellan'] ."']";
    }

    echo($response);
  }


  //------------------------------------------------------------------
  // 
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'update_times') {

    $datum = $_GET['datum'];
    $pilot = $_GET['pilot'];

    // Query database

    $query  = "SELECT t1.*,
                      t2.tid,
                      t2.id_pilot,
                      t2.id_pax
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum = t1.id 
               WHERE (t1.datum='$datum' AND t2.id_pilot='$pilot')";


    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);
  
    // Add to the ajax response string.
    $i        = 1;
    $response = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['tid_". $i ."','". $row['tid']    ."'],";
        $response .= "['pax_". $i ."','". $row['id_pax'] ."'],";
        $i = $i +1;
      }
      // Remove last ","
      $response = substr($response, 0, -1);

      $response .= "|" . ($i-1);
    }

    echo($response);
  }



  //--------------------------------------------------------------------
  // New pilot booking
  //--------------------------------------------------------------------

  if ($_GET['action'] == 'new_pilot_booking') {

    $datum    = $_GET['form_date'];
    $id_pilot = $_GET['form_name'];

    mysql_query("INSERT IGNORE INTO tandem_schema_datum (datum) VALUES ('$datum')") or die(mysql_error());
    mysql_query("SELECT id FROM tandem_schema_datum WHERE datum='$datum' INTO @id_datum") or die(mysql_error());
    foreach($_GET as $key => &$val) {
      if (preg_match("/cb/", $key)) {
        mysql_query("INSERT INTO tandem_schema_pilot (tid,id_pilot,id_datum) VALUES ('$val','$id_pilot',@id_datum)") or die(mysql_error());
      }
    }
    echo(true);
  } 



  //--------------------------------------------------------------------
  // Update pilot booking
  //--------------------------------------------------------------------

  if ($_GET['action'] == 'update_pilot_booking') {

    $datum    = $_GET['form_date'];
    $id_pilot = $_GET['form_name'];

    mysql_query("SELECT id FROM tandem_schema_datum WHERE datum='$datum' INTO @id_datum") or die(mysql_error());
    mysql_query("DELETE FROM tandem_schema_pilot WHERE (id_pilot='$id_pilot' AND id_datum=@id_datum AND id_pax = NULL)") or die(mysql_error());
    foreach($_GET as $key => &$val) {
      if (preg_match("/cb/", $key)) {
        mysql_query("INSERT INTO tandem_schema_pilot (tid,id_pilot,id_datum) VALUES ('$val','$id_pilot',@id_datum) ON DUPLICATE KEY UPDATE id=id") or die(mysql_error());
      }
    }
    echo(true);
  } 



//************************************************************************************

// Close database connection
mysql_close($connection);


?>
