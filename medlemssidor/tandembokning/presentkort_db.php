<?php

// Connect to database and select "lfk_misc"
include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");


  //------------------------------------------------------------------
  // Get all
  //------------------------------------------------------------------

  if ($_GET['action'] == 'get_all') {
  
    // Query database
    $query  = "SELECT t1.*, t2.id_pax FROM tandem_pax t1 LEFT JOIN tandem_schema_pilot t2 ON t1.id=t2.id_pax" or die(mysql_error());
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result) or die(mysql_error());
  
    // Add to the ajax response string.
    $response = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {

        if ($row['id_pax']) { $bokad = 1; } else { $bokad = 0; }

        $response .= "['pax_fornamn',        '". $row['pax_fornamn']        ."'],";
        $response .= "['pax_efternamn',      '". $row['pax_efternamn']      ."'],";
        $response .= "['pax_adress_1',       '". $row['pax_adress_1']       ."'],";
        $response .= "['pax_adress_2',       '". $row['pax_adress_2']       ."'],";
        $response .= "['pax_postnummer',     '". $row['pax_postnummer']     ."'],";
        $response .= "['pax_ort',            '". $row['pax_ort']            ."'],";
        $response .= "['pax_telefon',        '". $row['pax_telefon']        ."'],";
        $response .= "['pax_email',          '". $row['pax_email']          ."'],";
        $response .= "['pax_langd',          '". $row['pax_langd']          ."'],";
        $response .= "['pax_vikt',           '". $row['pax_vikt']           ."'],";
        $response .= "['kontakt_fornamn',    '". $row['kontakt_fornamn']    ."'],";
        $response .= "['kontakt_efternamn',  '". $row['kontakt_efternamn']  ."'],";
        $response .= "['kontakt_adress_1',   '". $row['kontakt_adress_1']   ."'],";
        $response .= "['kontakt_adress_2',   '". $row['kontakt_adress_2']   ."'],";
        $response .= "['kontakt_postnummer', '". $row['kontakt_postnummer'] ."'],";
        $response .= "['kontakt_ort',        '". $row['kontakt_ort']        ."'],";
        $response .= "['kontakt_telefon',    '". $row['kontakt_telefon']    ."'],";
        $response .= "['kontakt_email',      '". $row['kontakt_email']      ."'],";
        $response .= "['hoppat',             '". $row['hoppat']             ."'],";
        $response .= "['video',              '". $row['video']              ."'],";
        $response .= "['foto',               '". $row['foto']               ."'],";
        $response .= "['giltigt_till',       '". $row['giltigt_till']       ."'],";
        $response .= "['betalat',            '". $row['betalat']            ."'],";
        $response .= "['ovrigt',             '". $row['ovrigt']             ."'],";
        $response .= "['tillagd',            '". $row['tillagd']            ."'],";
        $response .= "['id',                 '". $row['id']                 ."'],";
        $response .= "['bokad',              '". $bokad                     ."']|";
      }
      // Remove last "|"
      $response = substr($response, 0, -1);
    }

    echo($response);

  }

  //------------------------------------------------------------------
  // Get detalis fo a certain id
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'details') {
  
    $id = $_GET['id'];

    // Query database
    $query  = "SELECT t1.*, t2.id_pax FROM tandem_pax t1 LEFT JOIN tandem_schema_pilot t2 ON t1.id=t2.id_pax WHERE t1.id = '".$id."'" or die(mysql_error());
    $result = mysql_query($query);
    $row    = mysql_fetch_array($result);
  
    if ($row['id_pax']) { $bokad = 1; } else { $bokad = 0; }

    // Create the ajax response string
    $response  = "";
    $response .= "['use_contact',        '". $row['use_contact']        ."'],";
    $response .= "['pax_fornamn',        '". $row['pax_fornamn']        ."'],";
    $response .= "['pax_efternamn',      '". $row['pax_efternamn']      ."'],";
    $response .= "['pax_adress_1',       '". $row['pax_adress_1']       ."'],";
    $response .= "['pax_adress_2',       '". $row['pax_adress_2']       ."'],";
    $response .= "['pax_postnummer',     '". $row['pax_postnummer']     ."'],";
    $response .= "['pax_ort',            '". $row['pax_ort']            ."'],";
    $response .= "['pax_telefon',        '". $row['pax_telefon']        ."'],";
    $response .= "['pax_email',          '". $row['pax_email']          ."'],";
    $response .= "['pax_langd',          '". $row['pax_langd']          ."'],";
    $response .= "['pax_vikt',           '". $row['pax_vikt']           ."'],";
    $response .= "['kontakt_fornamn',    '". $row['kontakt_fornamn']    ."'],";
    $response .= "['kontakt_efternamn',  '". $row['kontakt_efternamn']  ."'],";
    $response .= "['kontakt_adress_1',   '". $row['kontakt_adress_1']   ."'],";
    $response .= "['kontakt_adress_2',   '". $row['kontakt_adress_2']   ."'],";
    $response .= "['kontakt_postnummer', '". $row['kontakt_postnummer'] ."'],";
    $response .= "['kontakt_ort',        '". $row['kontakt_ort']        ."'],";
    $response .= "['kontakt_telefon',    '". $row['kontakt_telefon']    ."'],";
    $response .= "['kontakt_email',      '". $row['kontakt_email']      ."'],";
    $response .= "['hoppat',             '". $row['hoppat']             ."'],";
    $response .= "['video',              '". $row['video']              ."'],";
    $response .= "['foto',               '". $row['foto']               ."'],";
    $response .= "['giltigt_till',       '". $row['giltigt_till']       ."'],";
    $response .= "['betalat',            '". $row['betalat']            ."'],";
    $response .= "['ovrigt',             '". $row['ovrigt']             ."'],";
    $response .= "['tillagd',            '". $row['tillagd']            ."'],";
    $response .= "['modifierad',         '". $row['modifierad']         ."'],";
    $response .= "['id',                 '". $row['id']                 ."'],";
    $response .= "['bokad',              '". $bokad                     ."']";

    echo("$response");
  }


  //--------------------------------------------------------------------
  // New 'presentkort
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'new_pk') {

    // Check if NULL
    if ($_GET['pax_fornamn'])        { $pax_fornamn        = "'" . mysql_escape_string($_GET['pax_fornamn'])        . "'"; } else { $pax_fornamn        = "NULL"; }
    if ($_GET['pax_efternamn'])      { $pax_efternamn      = "'" . mysql_escape_string($_GET['pax_efternamn'])      . "'"; } else { $pax_efternamn      = "NULL"; }
    if ($_GET['pax_adress_1'])       { $pax_adress_1       = "'" . mysql_escape_string($_GET['pax_adress_1'])       . "'"; } else { $pax_adress_1       = "NULL"; }
    if ($_GET['pax_adress_2'])       { $pax_adress_2       = "'" . mysql_escape_string($_GET['pax_adress_2'])       . "'"; } else { $pax_adress_2       = "NULL"; }
    if ($_GET['pax_postnummer'])     { $pax_postnummer     = "'" . mysql_escape_string($_GET['pax_postnummer'])     . "'"; } else { $pax_postnummer     = "NULL"; }
    if ($_GET['pax_ort'])            { $pax_ort            = "'" . mysql_escape_string($_GET['pax_ort'])            . "'"; } else { $pax_ort            = "NULL"; }
    if ($_GET['pax_telefon'])        { $pax_telefon        = "'" . mysql_escape_string($_GET['pax_telefon'])        . "'"; } else { $pax_telefon        = "NULL"; }
    if ($_GET['pax_email'])          { $pax_email          = "'" . mysql_escape_string($_GET['pax_email'])          . "'"; } else { $pax_email          = "NULL"; }
    if ($_GET['pax_langd'])          { $pax_langd          = "'" . mysql_escape_string($_GET['pax_langd'])          . "'"; } else { $pax_langd          = "NULL"; }
    if ($_GET['pax_vikt'])           { $pax_vikt           = "'" . mysql_escape_string($_GET['pax_vikt'])           . "'"; } else { $pax_vikt           = "NULL"; }
    if ($_GET['kontakt_fornamn'])    { $kontakt_fornamn    = "'" . mysql_escape_string($_GET['kontakt_fornamn'])    . "'"; } else { $kontakt_fornamn    = "NULL"; }
    if ($_GET['kontakt_efternamn'])  { $kontakt_efternamn  = "'" . mysql_escape_string($_GET['kontakt_efternamn'])  . "'"; } else { $kontakt_efternamn  = "NULL"; }
    if ($_GET['kontakt_adress_1'])   { $kontakt_adress_1   = "'" . mysql_escape_string($_GET['kontakt_adress_1'])   . "'"; } else { $kontakt_adress_1   = "NULL"; }
    if ($_GET['kontakt_adress_2'])   { $kontakt_adress_2   = "'" . mysql_escape_string($_GET['kontakt_adress_2'])   . "'"; } else { $kontakt_adress_2   = "NULL"; }
    if ($_GET['kontakt_postnummer']) { $kontakt_postnummer = "'" . mysql_escape_string($_GET['kontakt_postnummer']) . "'"; } else { $kontakt_postnummer = "NULL"; }
    if ($_GET['kontakt_ort'])        { $kontakt_ort        = "'" . mysql_escape_string($_GET['kontakt_ort'])        . "'"; } else { $kontakt_ort        = "NULL"; }
    if ($_GET['kontakt_telefon'])    { $kontakt_telefon    = "'" . mysql_escape_string($_GET['kontakt_telefon'])    . "'"; } else { $kontakt_telefon    = "NULL"; }
    if ($_GET['kontakt_email'])      { $kontakt_email      = "'" . mysql_escape_string($_GET['kontakt_email'])      . "'"; } else { $kontakt_email      = "NULL"; }
    if ($_GET['giltigt_till'])       { $giltigt_till       = "'" . mysql_escape_string($_GET['giltigt_till'])       . "'"; } else { $giltigt_till       = "NULL"; }
    if ($_GET['betalat'])            { $betalat            = "'" . mysql_escape_string($_GET['betalat'])            . "'"; } else { $betalat            = "NULL"; }
    if ($_GET['ovrigt'])             { $ovrigt             = "'" . mysql_escape_string($_GET['ovrigt'])             . "'"; } else { $ovrigt             = "NULL"; }

    // These cant be NULL
    $use_contact = $_GET['use_contact'];
    $video       = $_GET['video'];
    $foto        = $_GET['foto'];
    $hoppat      = $_GET['hoppat'];
 

    // Insert a row of information into the table.
    mysql_query("INSERT INTO tandem_pax (
                    presentkort,
                    use_contact,
                    pax_fornamn,
                    pax_efternamn,
                    pax_adress_1,
                    pax_adress_2,
                    pax_postnummer,
                    pax_ort,
                    pax_telefon,
                    pax_email,
                    pax_langd,
                    pax_vikt,
                    kontakt_fornamn,
                    kontakt_efternamn,
                    kontakt_adress_1,
                    kontakt_adress_2,
                    kontakt_postnummer,
                    kontakt_ort,
                    kontakt_telefon,
                    kontakt_email,
                    hoppat,
                    video,
                    foto,
                    giltigt_till,
                    betalat,
                    ovrigt,
                    tillagd)
                 VALUES(
                    '1',
                    $use_contact,
                    $pax_fornamn,
                    $pax_efternamn,
                    $pax_adress_1,
                    $pax_adress_2,
                    $pax_postnummer,
                    $pax_ort,
                    $pax_telefon,
                    $pax_email,
                    $pax_langd,
                    $pax_vikt,
                    $kontakt_fornamn,
                    $kontakt_efternamn,
                    $kontakt_adress_1,
                    $kontakt_adress_2,
                    $kontakt_postnummer,
                    $kontakt_ort,
                    $kontakt_telefon,
                    $kontakt_email,
                    $hoppat,
                    $video,
                    $foto,
                    $giltigt_till,
                    $betalat,
                    $ovrigt,
                    NOW())") or die(mysql_error());

    echo('ok');
  } 
  
  
  //--------------------------------------------------------------------
  // Edit 'presentkort
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'edit') {
  
    // Check if NULL
    if ($_GET['pax_fornamn'])        { $pax_fornamn        = "'" . mysql_escape_string($_GET['pax_fornamn'])        . "'"; } else { $pax_fornamn        = "NULL"; }
    if ($_GET['pax_efternamn'])      { $pax_efternamn      = "'" . mysql_escape_string($_GET['pax_efternamn'])      . "'"; } else { $pax_efternamn      = "NULL"; }
    if ($_GET['pax_adress_1'])       { $pax_adress_1       = "'" . mysql_escape_string($_GET['pax_adress_1'])       . "'"; } else { $pax_adress_1       = "NULL"; }
    if ($_GET['pax_adress_2'])       { $pax_adress_2       = "'" . mysql_escape_string($_GET['pax_adress_2'])       . "'"; } else { $pax_adress_2       = "NULL"; }
    if ($_GET['pax_postnummer'])     { $pax_postnummer     = "'" . mysql_escape_string($_GET['pax_postnummer'])     . "'"; } else { $pax_postnummer     = "NULL"; }
    if ($_GET['pax_ort'])            { $pax_ort            = "'" . mysql_escape_string($_GET['pax_ort'])            . "'"; } else { $pax_ort            = "NULL"; }
    if ($_GET['pax_telefon'])        { $pax_telefon        = "'" . mysql_escape_string($_GET['pax_telefon'])        . "'"; } else { $pax_telefon        = "NULL"; }
    if ($_GET['pax_email'])          { $pax_email          = "'" . mysql_escape_string($_GET['pax_email'])          . "'"; } else { $pax_email          = "NULL"; }
    if ($_GET['pax_langd'])          { $pax_langd          = "'" . mysql_escape_string($_GET['pax_langd'])          . "'"; } else { $pax_langd          = "NULL"; }
    if ($_GET['pax_vikt'])           { $pax_vikt           = "'" . mysql_escape_string($_GET['pax_vikt'])           . "'"; } else { $pax_vikt           = "NULL"; }
    if ($_GET['kontakt_fornamn'])    { $kontakt_fornamn    = "'" . mysql_escape_string($_GET['kontakt_fornamn'])    . "'"; } else { $kontakt_fornamn    = "NULL"; }
    if ($_GET['kontakt_efternamn'])  { $kontakt_efternamn  = "'" . mysql_escape_string($_GET['kontakt_efternamn'])  . "'"; } else { $kontakt_efternamn  = "NULL"; }
    if ($_GET['kontakt_adress_1'])   { $kontakt_adress_1   = "'" . mysql_escape_string($_GET['kontakt_adress_1'])   . "'"; } else { $kontakt_adress_1   = "NULL"; }
    if ($_GET['kontakt_adress_2'])   { $kontakt_adress_2   = "'" . mysql_escape_string($_GET['kontakt_adress_2'])   . "'"; } else { $kontakt_adress_2   = "NULL"; }
    if ($_GET['kontakt_postnummer']) { $kontakt_postnummer = "'" . mysql_escape_string($_GET['kontakt_postnummer']) . "'"; } else { $kontakt_postnummer = "NULL"; }
    if ($_GET['kontakt_ort'])        { $kontakt_ort        = "'" . mysql_escape_string($_GET['kontakt_ort'])        . "'"; } else { $kontakt_ort        = "NULL"; }
    if ($_GET['kontakt_telefon'])    { $kontakt_telefon    = "'" . mysql_escape_string($_GET['kontakt_telefon'])    . "'"; } else { $kontakt_telefon    = "NULL"; }
    if ($_GET['kontakt_email'])      { $kontakt_email      = "'" . mysql_escape_string($_GET['kontakt_email'])      . "'"; } else { $kontakt_email      = "NULL"; }
    if ($_GET['giltigt_till'])       { $giltigt_till       = "'" . mysql_escape_string($_GET['giltigt_till'])       . "'"; } else { $giltigt_till       = "NULL"; }
    if ($_GET['betalat'])            { $betalat            = "'" . mysql_escape_string($_GET['betalat'])            . "'"; } else { $betalat            = "NULL"; }
    if ($_GET['ovrigt'])             { $ovrigt             = "'" . mysql_escape_string($_GET['ovrigt'])             . "'"; } else { $ovrigt             = "NULL"; }

    // These cant be NULL
    $use_contact = $_GET['use_contact'];
    $video       = $_GET['video'];
    $foto        = $_GET['foto'];
    $hoppat      = $_GET['hoppat'];
 
    // Insert a row of information into the table.
    mysql_query("UPDATE tandem_pax SET 
                    use_contact        = $use_contact,
                    pax_fornamn        = $pax_fornamn,
                    pax_efternamn      = $pax_efternamn,
                    pax_adress_1       = $pax_adress_1,
                    pax_adress_2       = $pax_adress_2,
                    pax_postnummer     = $pax_postnummer,
                    pax_ort            = $pax_ort,
                    pax_telefon        = $pax_telefon,
                    pax_email          = $pax_email,
                    pax_langd          = $pax_langd,
                    pax_vikt           = $pax_vikt,
                    kontakt_fornamn    = $kontakt_fornamn,
                    kontakt_efternamn  = $kontakt_efternamn,
                    kontakt_adress_1   = $kontakt_adress_1,
                    kontakt_adress_2   = $kontakt_adress_2,
                    kontakt_postnummer = $kontakt_postnummer,
                    kontakt_ort        = $kontakt_ort,
                    kontakt_telefon    = $kontakt_telefon,
                    kontakt_email      = $kontakt_email,
                    hoppat             = $hoppat,
                    video              = $video,
                    foto               = $foto,
                    giltigt_till       = $giltigt_till,
                    betalat            = $betalat,
                    ovrigt             = $ovrigt,
                    modifierad         =  NOW()
                 WHERE id = '$_GET[id]'") or die(mysql_error());

    echo('ok');
  
  }


  //--------------------------------------------------------------------
  // Delete 'presentkort'
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'delete') {

    $id = $_GET['id'];

    mysql_query("DELETE FROM tandem_pax WHERE id = '$id'") or die(mysql_error());

    echo('ok');
  }




// Close database connection
mysql_close($connection);


?>
