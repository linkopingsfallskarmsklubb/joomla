<?php

  
// Connect to database and select "lfk_misc"
include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");


  //-------------------------------------------
  // Get all pilots
  //-------------------------------------------
  
  if ($_GET['action'] == 'get_all_pilots') {
    
    $query      = "SELECT * FROM tandem_piloter ORDER BY id";
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());

    // Create the ajax response string
    $response  = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['id',         '". $row['id']         ."'],";
        $response .= "['certnr',     '". $row['certnr']     ."'],";
        $response .= "['fornamn',    '". $row['fornamn']    ."'],";
        $response .= "['efternamn',  '". $row['efternamn']  ."'],";
        $response .= "['maxvikt',    '". $row['maxvikt']    ."'],";
        $response .= "['maxlangd',   '". $row['maxlangd']   ."'],";
        $response .= "['tid_mellan', '". $row['tid_mellan'] ."'],";
        $response .= "['aktiv',      '". $row['aktiv']      ."']|";
      }
      $response = substr($response, 0, -1); // Remove last "|"
    }
    echo($response);

  }

  //-------------------------------------------
  // Get all photographers
  //-------------------------------------------
  
  else if ($_GET['action'] == 'get_all_photographers') {
    
    $query      = "SELECT * FROM tandem_fotografer ORDER BY id";
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());

    // Create the ajax response string
    $response  = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['id',         '". $row['id']         ."'],";
        $response .= "['certnr',     '". $row['certnr']     ."'],";
        $response .= "['fornamn',    '". $row['fornamn']    ."'],";
        $response .= "['efternamn',  '". $row['efternamn']  ."'],";
        $response .= "['video',      '". $row['video']      ."'],";
        $response .= "['foto',       '". $row['foto']       ."'],";
        $response .= "['aktiv',       '". $row['aktiv']     ."']|";
      }
      $response = substr($response, 0, -1); // Remove last "|"
    }
    echo($response);

  }


  //-------------------------------------------
  // New pilot
  //-------------------------------------------
  
  else if ($_GET['action'] == 'new_pilot') {
    
    // Insert a row of information into the table.
    mysql_query("INSERT INTO tandem_piloter (
                   certnr,
                   fornamn,
                   efternamn,
                   maxlangd,
                   maxvikt,
                   tid_mellan,
                   aktiv)
                 VALUES(
                   '$_GET[certnr]',
                   '$_GET[fornamn]',
                   '$_GET[efternamn]',
                   '$_GET[maxlangd]',
                   '$_GET[maxvikt]',
                   '$_GET[tid_mellan]',
                   '1')") or die(mysql_error());

    echo("ok");

  }


  //-------------------------------------------
  // New photographer
  //-------------------------------------------
  
  else if ($_GET['action'] == 'new_photo') {
    
    // Insert a row of information into the table.
    mysql_query("INSERT INTO tandem_fotografer (
                   certnr,
                   fornamn,
                   efternamn,
                   video,
                   foto,
                   aktiv)
                 VALUES(
                   '$_GET[certnr]',
                   '$_GET[fornamn]',
                   '$_GET[efternamn]',
                   '$_GET[video]',
                   '$_GET[foto]',
                   '1')") or die(mysql_error());

    echo("ok");

  }


  //-------------------------------------------
  // Get pilot
  //-------------------------------------------
  
  else if ($_GET['action'] == 'get_pilot') {

    $id = $_GET['id'];

    // Query database
    $query  = "SELECT * FROM tandem_piloter WHERE id = '".$id."'";
    $result = mysql_query($query) or die(mysql_error());
    $row    = mysql_fetch_array($result) or die(mysql_error());
  
      // Create the ajax response string
    $response  = "['id',          '".  $row['id']          ."'],";
    $response .= "['certnr',      '".  $row['certnr']      ."'],";
    $response .= "['fornamn',     '".  $row['fornamn']     ."'],";
    $response .= "['efternamn',   '".  $row['efternamn']   ."'],";
    $response .= "['maxlangd',    '".  $row['maxlangd']    ."'],";
    $response .= "['maxvikt',     '".  $row['maxvikt']     ."'],";
    $response .= "['tid_mellan',  '".  $row['tid_mellan']  ."'],";
    $response .= "['aktiv',       '".  $row['aktiv']       ."']";

    echo($response);
  }



  //-------------------------------------------
  // Edit pilot
  //-------------------------------------------
  
  else if ($_GET['action'] == 'edit_pilot') {

    mysql_query("UPDATE tandem_piloter SET 
                   certnr     = '$_GET[certnr]',
                   fornamn    = '$_GET[fornamn]',
                   efternamn  = '$_GET[efternamn]',
                   maxlangd   = '$_GET[maxlangd]',
                   maxvikt    = '$_GET[maxvikt]',
                   tid_mellan = '$_GET[tid_mellan]',
                   aktiv      = '$_GET[aktiv]'
                 WHERE id = '$_GET[id]'") or die(mysql_error());

    echo("ok");
  }


  //-------------------------------------------
  // Get photographer
  //-------------------------------------------
  
  else if ($_GET['action'] == 'get_photo') {

    $id = $_GET['id'];

    // Query database
    $query  = "SELECT * FROM tandem_fotografer WHERE id = '".$id."'";
    $result = mysql_query($query) or die(mysql_error());
    $row    = mysql_fetch_array($result) or die(mysql_error());
  
      // Create the ajax response string
    $response  = "['id',          '".  $row['id']          ."'],";
    $response .= "['certnr',      '".  $row['certnr']      ."'],";
    $response .= "['fornamn',     '".  $row['fornamn']     ."'],";
    $response .= "['efternamn',   '".  $row['efternamn']   ."'],";
    $response .= "['video',       '".  $row['video']       ."'],";
    $response .= "['foto',        '".  $row['foto']        ."'],";
    $response .= "['aktiv',       '".  $row['aktiv']       ."']";

    echo($response);
  }


  //-------------------------------------------
  // Edit pilot
  //-------------------------------------------
  
  else if ($_GET['action'] == 'edit_photo') {

    mysql_query("UPDATE tandem_fotografer SET 
                   certnr    = '$_GET[certnr]',
                   fornamn   = '$_GET[fornamn]',
                   efternamn = '$_GET[efternamn]',
                   video     = '$_GET[video]',
                   foto      = '$_GET[foto]',
                   aktiv     = '$_GET[aktiv]'
                 WHERE id = '$_GET[id]'") or die(mysql_error());

    echo("ok");
  }

  


// Close database connection
mysql_close($connection);


?>
