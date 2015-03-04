<?php

// Connect to database
include("../../includes/db_connect.php");

  //------------------------------------------------
  // Fetch an array of dates that have an event
  //------------------------------------------------

  if ($_GET['action'] == 'get_event_days') {

    // This variable contains the date that we want fetch from the database.
    if (!isset($_GET["date"])) {
      $date = date('Y-m-d');
    } else {
      $date = $_GET["date"];
    }

    // Query database
    $sql    = "SELECT * FROM hopp_schema WHERE YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date')";
    $result = mysql_query($sql) or die(mysql_error());

    // Create the ajax response string
    $response = "";
    while ($row = mysql_fetch_array($result)) {
      $response .= $row['datum'] .",";
    }
    $response = substr($response, 0, -1); // Remove last ","

    echo($response);
  }


  //-------------------------------------------
  // Get all names for autocomplete function
  //-------------------------------------------

  else if ($_GET['action'] == 'get_autocomplete') {

    // Instruktörer
    $sql        = "SELECT * FROM instruktorer";
    $result     = mysql_query($sql) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());
  
  
    $hoppledare  = "";
    $hoppmastare = "";
    $aff         = "";
    $manifestor  = "";

    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        if ($row['hoppledare'] == 1) {
          $hoppledare .= $row['fornamn'] ." ". $row['efternamn'] ."|". $row['id'] .",";
        }

        if ($row['hoppmastare'] == 1) {
          $hoppmastare .= $row['fornamn'] ." ". $row['efternamn'] ."|". $row['id'] .",";
        }
        if ($row['aff'] == 1) {
          $aff .= $row['fornamn'] ." ". $row['efternamn'] ."|". $row['id'] .",";
        }
        if ($row['manifestor'] == 1) {
          $manifestor .= $row['fornamn'] ." ". $row['efternamn'] ."|". $row['id'] .",";
        }
      }
    }

    // Piloter
    $sql        = "SELECT * FROM piloter";
    $result     = mysql_query($sql) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());
    
    $pilot = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $pilot .= $row['fornamn'] ." ". $row['efternamn'] ."|". $row['id'] .",";
      }
    }

    // Remove last ","
    $hoppledare  = substr($hoppledare, 0, -1);
    $hoppmastare = substr($hoppmastare, 0, -1);
    $aff         = substr($aff, 0, -1);
    $manifestor  = substr($manifestor, 0, -1);
    $pilot       = substr($pilot, 0, -1);


    // Assebmle the response string
    $response  = "[\"hl\",  \"". $hoppledare  ."\"],";
    $response .= "[\"hm\",  \"". $hoppmastare ."\"],";
    $response .= "[\"aff\", \"". $aff         ."\"],";
    $response .= "[\"man\", \"". $manifestor  ."\"],";
    $response .= "[\"pil\", \"". $pilot       ."\"]";

    echo($response);
  }


  //------------------------------------------------------------------
  // Get jump hours
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'jump_hours') {
    
    $wd = $_GET["wd"];

    // Query database (jump_hours)
    $query      = "SELECT start_time, stop_time FROM jump_hours WHERE weekday = '$wd'";
    $result     = mysql_query($query) or die(mysql_error());
    $row        = mysql_fetch_array($result) or die(mysql_error());
    
    // Add to the ajax response string.
    $response  = "['start_time','". substr($row['start_time'],0,5) ."'],";
    $response .= "['stop_time','".  substr($row['stop_time'],0,5)  ."']";
   
    echo($response);
  }


  //-------------------------------------------
  // Fetch all details for a specific date
  //-------------------------------------------

  else if ($_GET['action'] == 'get_day_info') {
  
    $date       = $_GET["date"];
    $sql        = "SELECT * FROM hopp_schema WHERE (datum = '". $date ."')";
    $result     = mysql_query($sql) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());
  
  
    // Add to the ajax response string.
    $response = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['tid_start',   '".  substr($row['tid_start'],0,-3) ."'],";
        $response .= "['tid_stop',    '".  substr($row['tid_stop'],0,-3)  ."'],";
      }
      $response = substr($response, 0, -1); // Remove last ","
    }

    $sql    = "SELECT * FROM ( 
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      t3.id,
                      'hl' AS 'type'
               FROM hopp_schema    AS t1
               JOIN hopp_schema_hl AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer   AS t3 ON t3.id       = t2.id_namn
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      t3.id,
                      'hm' AS 'type'
               FROM hopp_schema    AS t1
               JOIN hopp_schema_hm AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer   AS t3 ON t3.id       = t2.id_namn
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      t3.id,
                      'manifest' AS 'type'
               FROM hopp_schema          AS t1
               JOIN hopp_schema_manifest AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer         AS t3 ON t3.id       = t2.id_namn
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      t3.id,
                      'aff' AS 'type'
               FROM hopp_schema          AS t1
               JOIN hopp_schema_aff      AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer         AS t3 ON t3.id       = t2.id_namn
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      t3.id,
                      'pilot' AS 'type'
               FROM hopp_schema          AS t1
               JOIN hopp_schema_pilot    AS t2 ON t2.id_datum = t1.id
               JOIN piloter              AS t3 ON t3.id       = t2.id_namn) AS thestuff
             WHERE datum = '". $date ."'"; 


    $result     = mysql_query($sql) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());

    // Create the ajax response string
    if ($match_rows != 0) {
      // The '|' is used to seperate 'hoppschema' and 'tandemschema'.
      $response .= "|";
      while ($row = mysql_fetch_array($result)) {
        $response .= "['type',        '".  $row['type']                   ."'],";
        $response .= "['fornamn',     '".  $row['fornamn']                ."'],";
        $response .= "['efternamn',   '".  $row['efternamn']              ."'],";
        $response .= "['id',          '".  $row['id']                     ."'],";
        $response .= "['tid_start',   '".  substr($row['tid_start'],0,-3) ."'],";
        $response .= "['tid_stop',    '".  substr($row['tid_stop'],0,-3)  ."']~";
      }
      $response = substr($response, 0, -1); // Remove last "~"
    }

    echo($response);
  }


  //-------------------------------------------
  // New day
  //-------------------------------------------
  
  else if ($_GET['action'] == 'new_day') {

    // If date exists, update
    mysql_query("UPDATE hopp_schema 
                 SET    tid_start  = '$_GET[tid_start]',
                        tid_stop   = '$_GET[tid_stop]',
                        kommentar  = '$_GET[kommentar]',
                        modifierad = NOW()
                 WHERE  datum      = '$_GET[datum]'") or die(mysql_error());

    // If date doesn't exist, insert new
    if (mysql_affected_rows()==0) {
      mysql_query("INSERT INTO hopp_schema
                     (datum,
                      tid_start,
                      tid_stop,
                      kommentar,
                      tillagd)
                    VALUES (
                      '$_GET[datum]',
                      '$_GET[tid_start]',
                      '$_GET[tid_stop]',
                      '$_GET[kommentar]',
                       NOW())") or die(mysql_error());
    }


    // Get id for selected date
    $result   = mysql_query("SELECT id FROM hopp_schema WHERE datum = '$_GET[datum]'") or die(mysql_error());
    $row      = mysql_fetch_array($result) or die(mysql_error());
    $id_datum = $row[id];


    foreach(array_keys($_GET) as $key) {
      $clean[$key] = mysql_real_escape_string($_GET[$key]);
    }


    // Insert into different instruktor tables (hl, hm etc)
    function insert_f($type, $id_datum, $index, $table, $clean) {

      $tid_start = $clean[$type.'_tid_start_'.$index];
      $tid_stop  = $clean[$type.'_tid_stop_'.$index];
      $id_namn   = $clean[$type.'_id_'.$index];

      mysql_query("INSERT INTO ".$table." 
                    (tid_start,
                     tid_stop,
                     kommentar,
                     id_datum,
                     id_namn)
                   VALUES (
                    '$tid_start',
                    '$tid_stop',
                    'test',
                    '$id_datum',
                    '$id_namn')") or die(mysql_error());
    }


    $nrof_pil = $clean['nrof_pil'];
    $table    = "hopp_schema_pilot";
    mysql_query("DELETE FROM ".$table." WHERE id_datum = '$id_datum'")  or die(mysql_error());

    for ($i=1; $i <= $nrof_pil; $i++) {
      insert_f('pil', $id_datum, $i, $table, $clean);
    }

    $nrof_hl = $clean['nrof_hl'];
    $table   = "hopp_schema_hl";
    mysql_query("DELETE FROM ".$table." WHERE id_datum = '$id_datum'")  or die(mysql_error());
    for ($i=1; $i <= $nrof_hl; $i++) {
      insert_f('hl', $id_datum, $i, $table, $clean);
    }

    $nrof_hm = $clean['nrof_hm'];
    $table   = "hopp_schema_hm";
    mysql_query("DELETE FROM ".$table." WHERE id_datum = '$id_datum'")  or die(mysql_error());
    for ($i=1; $i <= $nrof_hm; $i++) {
      insert_f('hm', $id_datum, $i, $table, $clean);
    }

    $nrof_aff = $clean['nrof_aff'];
    $table    = "hopp_schema_aff";
    mysql_query("DELETE FROM ".$table." WHERE id_datum = '$id_datum'")  or die(mysql_error());
    for ($i=1; $i <= $nrof_aff; $i++) {
      insert_f('aff', $id_datum, $i, $table, $clean);
    }

    $nrof_man = $clean['nrof_man'];
    $table    = "hopp_schema_manifest";
    mysql_query("DELETE FROM ".$table." WHERE id_datum = '$id_datum'")  or die(mysql_error());
    for ($i=1; $i <= $nrof_man; $i++) {
      insert_f('man', $id_datum, $i, $table, $clean);
    }

    echo("ok");
  } 

  //-------------------------------------------
  // Remove day
  //-------------------------------------------

  else if ($_GET['action'] == 'remove_day') {
    
    $datum = $_GET['date'];

    $result = mysql_query("SELECT * FROM hopp_schema WHERE datum = '$datum'") or die(mysql_error());
    $row    = mysql_fetch_array($result);
    $id     = $row['id'];
  
    mysql_query("DELETE FROM hopp_schema_pilot    WHERE id_datum = '$id'") or die(mysql_error());
    mysql_query("DELETE FROM hopp_schema_hl       WHERE id_datum = '$id'") or die(mysql_error());
    mysql_query("DELETE FROM hopp_schema_manifest WHERE id_datum = '$id'") or die(mysql_error());
    mysql_query("DELETE FROM hopp_schema_hm       WHERE id_datum = '$id'") or die(mysql_error());
    mysql_query("DELETE FROM hopp_schema_aff      WHERE id_datum = '$id'") or die(mysql_error());
    mysql_query("DELETE FROM hopp_schema          WHERE id       = '$id'") or die(mysql_error());
    
    echo("ok");
  } 


// Close database connection
mysql_close($connection);

?>
