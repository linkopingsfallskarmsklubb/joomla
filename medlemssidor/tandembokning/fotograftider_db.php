<?php

// Id can be id or date 
$id = $_GET["id"];
  

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

    $date = $_GET['date'];

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
    // The ':' is used to seperate queries in respons string
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
    // The ':' is used to seperate queries in respons string
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
  // Get default jump hours
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
  // List details for current day
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'list_day') {
    
    $date = $_GET['date'];

    //----------------------------------------
    // Query database
    // Get the start and stop time for jumping
    //----------------------------------------

    $query  = "SELECT id, datum, tid_start, tid_stop FROM hopp_schema WHERE datum = '$date'";
    $result = mysql_query($query) or die(mysql_error());
    $row    = mysql_fetch_array($result);
    
    // Add to the ajax response string.
    $response  = "['hoppning_start','". $row['tid_start'] ."'],";
    $response .= "['hoppning_stop','".  $row['tid_stop']  ."']";

    
    //----------------------------------------
    // Query database
    // Get photographers for current day
    //----------------------------------------


    $query  = "SELECT * FROM 
                    (SELECT t1.id          AS t1_id,
                      t1.datum       AS t1_datum,
                      t2.id          AS t2_id,
                      t2.tid_start   AS t2_tid_start,
                      t2.tid_stop    AS t2_tid_stop,
                      t2.kommentar   AS t2_kommentar,
                      t2.id_datum    AS t2_id_datum,
                      t2.id_fotograf AS t2_id_fotograf,
                      t3.id          AS t3_id,
                      t3.fornamn     AS t3_fornamn,
                      t3.efternamn   AS t3_efternamn,
                      t4.id_fotograf AS t4_id_fotograf
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_foto  AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_fotografer   AS t3 ON t2.id_fotograf = t3.id 
                        LEFT JOIN tandem_schema_pilot AS t4 ON (t2.id_datum   = t4.id_datum AND t4.id_fotograf = t3.id)
                      WHERE t1.datum='$date' ORDER BY t4_id_fotograf DESC
                    ) AS tmp_table GROUP BY t2_id_fotograf";


    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);

    // Add to the ajax response string.
    // The '|' is used to seperate queries in respons string
    $i         = 1;
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['id_".          $i ."','". $row['t2_id'] ."'],";
        $response .= "['fotograf_id_". $i ."','". $row['t3_id'] ."'],";
        $response .= "['tid_start_".   $i ."','". substr($row['t2_tid_start'],0,5) ."'],";
        $response .= "['tid_stop_".    $i ."','". substr($row['t2_tid_stop'],0,5) ."'],";
        $response .= "['fotograf_".    $i ."','". $row['t3_fornamn']  ." ". $row['t3_efternamn'] ."'],";
        $response .= "['bokad_".       $i ."','". $row['t4_id_fotograf'] ."'],";
        $response .= "['kommentar_".   $i ."','". $row['t2_kommentar'] ."'],";
        $i         = $i +1;
      }
      $response = substr($response, 0, -1); // Remove last ","
    }


    //-------------------------------------------
    // Query database
    // Get the times that are booked with a pax
    //-------------------------------------------

    $query  = "SELECT *, t1.id AS b_id, t3.id AS f_id
                 FROM tandem_schema_pilot AS t1 
                 JOIN tandem_schema_datum AS t2 ON t1.id_datum    = t2.id 
                 JOIN tandem_fotografer   AS t3 ON t1.id_fotograf = t3.id
               WHERE t2.datum = '$date' ORDER BY tid";

    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);

    // Add to the ajax response string.
    // The '|' is used to seperate queries in respons string
    $i         = 1;
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['bokn_id_".   $i ."','". $row['b_id'] ."'],";
        $response .= "['foto_id_".   $i ."','". $row['f_id'] ."'],";
        $response .= "['tid_".       $i ."','". substr($row['tid'],0,5) ."'],";
        $response .= "['fotograf_".  $i ."','". $row['fornamn']  ." ". $row['efternamn'] ."'],";
        $response .= "['video_".     $i ."','". $row['video'] ."'],";
        $response .= "['foto_".      $i ."','". $row['foto']  ."'],";
        $i = $i +1;
      }
      $response = substr($response, 0, -1); // Remove last ","
    }

    //----------------------------------------
    // Return result
    //----------------------------------------

    echo($response);
  }


  //------------------------------------------------------------------
  // Delete time - get details for popup box
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'delete_time_info') {

    // Query database
    $query  = "SELECT t1.id        AS s_id,
                      t1.tid_start AS s_tid_start,
                      t1.tid_stop  AS s_tid_stop,
                      t2.datum     AS d_datum,
                      t3.fornamn   AS f_fornamn,
                      t3.efternamn AS f_efternamn
                        FROM tandem_schema_foto  AS t1
                        JOIN tandem_schema_datum AS t2 ON t1.id_datum    = t2.id
                        JOIN tandem_fotografer   AS t3 ON t1.id_fotograf = t3.id
                        WHERE t1.id = '". $id ."'";
    $result = mysql_query($query)        or die(mysql_error());
    $row    = mysql_fetch_array($result) or die(mysql_error());

    // Create the ajax response string.
    $response  = "['id','".        $row['s_id']        ."'],";
    $response .= "['tid_start','". $row['s_tid_start'] ."'],";
    $response .= "['tid_stop','".  $row['s_tid_stop']  ."'],";
    $response .= "['datum','".     $row['d_datum']     ."'],";
    $response .= "['fotograf','".  $row['f_fornamn']   ." ". $row['f_efternamn'] ."']";
 
    echo($response);
  }


  //--------------------------------------------------------------------
  // Delete time
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'delete_time') {

    $id   = $_GET['id'];

    // Delete row in 'tandem_schema_foto'
    mysql_query("SELECT id_datum FROM tandem_schema_foto WHERE id = '$id' INTO @id_datum");
    mysql_query("DELETE FROM tandem_schema_foto WHERE id = '$id'") or die(mysql_error());

    // Unless anyone else is booked the same day, delete row in 'tandem_schema_datum'
    $result       = mysql_query("SELECT id FROM tandem_schema_foto WHERE id_datum = @id_datum");
    $match_rows_f = mysql_num_rows($result);

    $result     = mysql_query("SELECT id FROM tandem_schema_pilot WHERE id_datum = @id_datum");
    $match_rows_p = mysql_num_rows($result);

    if (($match_rows_f == 0) && ($match_rows_p == 0)) {
      mysql_query("DELETE FROM tandem_schema_datum WHERE id = @id_datum");
    }

  } 


//------------------------------------------------------------------
// Step 3. 
//------------------------------------------------------------------

  //--------------------------------------------------------------------
  // New photographer booking
  //--------------------------------------------------------------------

  if ($_GET['action'] == 'new_photo_booking') {
    
    // Check if NULL
    if ($_GET['kommentar']) { $kommentar = "'" . mysql_escape_string($_GET['kommentar']) . "'"; } else { $kommentar = "NULL"; }

    // These can't be NULL
    $date        = $_GET['date'];
    $start       = $_GET['tid_start'];
    $stop        = $_GET['tid_stop'];
    $id_fotograf = $_GET['id_fotograf'];
    
    // Update database
    mysql_query("INSERT IGNORE INTO tandem_schema_datum (datum) VALUES ('$date')") or die(mysql_error());
    mysql_query("SELECT id FROM tandem_schema_datum WHERE datum='$date' INTO @id_datum") or die(mysql_error());;
    mysql_query("INSERT INTO tandem_schema_foto (tid_start,tid_stop,kommentar,id_datum,id_fotograf) VALUES ('$start','$stop', $kommentar, @id_datum, '$id_fotograf')") or die(mysql_error());

    echo('ok');

  } 

  //--------------------------------------------------------------------
  // Update photgrapher booking
  //--------------------------------------------------------------------

  if ($_GET['action'] == 'update_photo_booking') {

    // Check if NULL
    if ($_GET['kommentar']) { $kommentar = "'" . mysql_escape_string($_GET['kommentar']) . "'"; } else { $kommentar = "NULL"; }

    // These can't be NULL
    $date        = $_GET['date'];
    $start       = $_GET['tid_start'];
    $stop        = $_GET['tid_stop'];
    $id_fotograf = $_GET['id_fotograf'];

    // Update database
    mysql_query("SELECT id FROM tandem_schema_datum WHERE datum='$date' INTO @id_datum") or die(mysql_error());
    mysql_query("UPDATE tandem_schema_foto SET tid_start='$start', tid_stop='$stop', kommentar=$kommentar WHERE id_datum=@id_datum AND id_fotograf=$id_fotograf") or die(mysql_error());;

    echo('ok');
  } 



//************************************************************************************

// Close database connection
mysql_close($connection);


?>
