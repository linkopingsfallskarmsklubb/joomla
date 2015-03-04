<?php

// Connect to database and select "lfk_misc"
include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");

//------------------------------------------------------------------
// Step 1.
//------------------------------------------------------------------

  //------------------------------------------------------------------
  // Get days with free times
  //------------------------------------------------------------------

  if ($_GET['action'] == 'get_free_days') {

    // Arguments
    $date = $_GET["date"];

    // Query database (tandem_schema).
    $query  = "SELECT datum FROM tandem_schema_pilot AS t1 
                            JOIN tandem_schema_datum AS t2 ON t1.id_datum = t2.id 
               WHERE YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date') 
               GROUP BY datum";
    $result = mysql_query($query);
  
    // Add to the ajax response string.
    $response = "";
    while ($row = mysql_fetch_array($result)) {
      $response .= $row['datum'] .",";
    }
    $response  = substr($response, 0, -1); // Remove last ","

    echo($response);
  }



  //------------------------------------------------------------------
  // Get free times for selected day
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'get_free_times') {
    
    // Arguments
    $date = $_GET['date']; 

    // Photographers
    $query  = "SELECT * FROM      tandem_schema_datum AS t1
                        JOIN      tandem_schema_foto  AS t2 ON t2.id_datum = t1.id 
                        JOIN      tandem_fotografer   AS t3 ON t3.id       = t2.id_fotograf 
               WHERE t1.datum      = '$date'";

    $result = mysql_query($query) or die(mysql_error());


    $arr = array();
    while ($row = mysql_fetch_array($result)) {

      $tid_start = substr($row['tid_start'],0,2) * 3600 + substr($row['tid_start'],3,2) * 60;
      $tid_stop  = substr($row['tid_stop'],0,2)  * 3600 + substr($row['tid_stop'],3,2)  * 60;

      for ($i=$tid_start; $i<=$tid_stop; $i = $i+30*60 ) {
        $arr[$row['id_fotograf'] ."_". date('H:i:s', $i-3600)] = $row['foto'] ."_". $row['video'];
      }
    }

    // Booked photographer times
    $query  = "SELECT * FROM      tandem_schema_datum AS t1
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum = t1.id 
                        JOIN      tandem_schema_foto  AS t3 ON t3.id_datum = t1.id AND t3.id_fotograf = t2.id_fotograf 
               WHERE  t1.datum        = '$date' AND
                     (t2.id_fotograf != 0     OR
                      t2.id_fotograf != NULL)
               ORDER BY t2.tid";

    $result = mysql_query($query) or die(mysql_error());

    // Remove booked times (60 min) from list of avaliable times
    while ($row = mysql_fetch_array($result)) {
      $remove = $row['id_fotograf'] ."_". $row['tid'];
      unset($arr[$remove]);
      $next30 = substr($row['tid'],0,2) * 3600 + substr($row['tid'],3,2) * 60 + substr($row['tid'],6,2) -3600 + 1800;
      $remove = $row['id_fotograf'] ."_". date('H:i:s',$next_30);
      unset($arr[$remove]);
    }


    // Query database (tandem_schema)
    $query  = "SELECT *,
                      t2.id       AS p_id_tid,
                      t2.id_pilot AS p_id_pilot,
                      t3.maxlangd AS p_maxlangd,
                      t3.maxvikt  AS p_maxvikt
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_piloter      AS t3 ON t2.id_pilot    = t3.id 
                        LEFT JOIN tandem_pax          AS t4 ON t2.id_pax      = t4.id 
               WHERE t1.datum='$date' ORDER BY t2.tid";
    $result = mysql_query($query) or die(mysql_error());
  

    // Add to the ajax response string.
    $i        = 1;
    $response = "['date','". $date ."'],";
    while ($row = mysql_fetch_array($result)) {
      $response .= "['bokad_".     $i ."','". $row['id_pax'] ."'],";
      $response .= "['tid_id_".    $i ."','". $row['p_id_tid'] ."'],";
      $response .= "['tid_".       $i ."','". substr($row['tid'],0,5) ."'],";
      $response .= "['pilot_".     $i ."','". $row['fornamn']    ." ". $row['efternamn'] ."'],";
      $response .= "['pilot_id_".  $i ."','". $row['p_id_pilot'] ."'],";
      $response .= "['maxlangd_".  $i ."','". $row['p_maxlangd'] ."'],";
      $response .= "['maxvikt_".   $i ."','". $row['p_maxvikt']  ."'],";

      $video       = 0;
      $foto        = 0;
      $foto_video  = 0;
      foreach (array_keys($arr) as $k) {
        $pattern = "/^(\\d+)_". $row['tid'] ."$/";
        if (preg_match($pattern, $k, $matches)) {
          $yada = $arr[$k];
          if (substr($yada,0,1) == 1) {
            $video = 1;
          }
          if (substr($yada,2,1) == 1) {
            $foto = 1;
          }
          
          if ((substr($yada,0,1) == 1) && (substr($yada,2,1) == 1)) {
            $foto_video = 1;
          }
          
        }
      }
      $response .= "['video_".      $i ."','". $video      ."'],";
      $response .= "['foto_".       $i ."','". $foto       ."'],";
      $response .= "['foto_video_". $i ."','". $foto_video ."'],";
      $i = $i +1;
    }
    $response = substr($response, 0, -1); // Remove last ","

    echo($response);
  }




//------------------------------------------------------------------
// Step 2.
//------------------------------------------------------------------


  //------------------------------------------------------------------
  // 
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'get_photo_name_from_id') {
  
    // Arguments
    $id = $_GET["id"];

    $query  = "SELECT fornamn, efternamn FROM tandem_fotografer WHERE id = '$id'";
    $result = mysql_query($query) or die(mysql_error());
    $row    = mysql_fetch_array($result);

    echo($row['fornamn'] ." ". $row['efternamn']);
  }


  //------------------------------------------------------------------
  // Get photographer schedule
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'get_photo_schedule') {

    // Arguments
    $date = $_GET["date"];

    // --- Number of photographers ---
    $query  = "SELECT *,
                      t2.id          AS f_id,
                      t2.id_fotograf AS f_id_fotograf
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_foto  AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_fotografer   AS t3 ON t2.id_fotograf = t3.id 
               WHERE t1.datum='$date'";

    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);

    // Create the ajax response string.
    $response = "['nof_fotografer','". $match_rows ."']";



    // --- ---
    $query  = "SELECT *,
                      t2.id          AS f_id,
                      t2.id_fotograf AS f_id_fotograf,
                      t4.id_fotograf AS f_bokad
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_foto  AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_fotografer   AS t3 ON t2.id_fotograf = t3.id 
                        LEFT JOIN tandem_schema_pilot AS t4 ON t2.id_datum    = t4.id_datum 
               WHERE t1.datum='$date' GROUP BY t2.id_fotograf ORDER BY t2.id_fotograf";


    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);

    // Add to the ajax response string.
    // The '|' is used to seperate queries in respons string
    $i         = 1;
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['foto_id_".        $i ."','". $row['f_id_fotograf'] ."'],";
        $response .= "['foto_fornamn_".   $i ."','". $row['fornamn'] ."'],";
        $response .= "['foto_efternamn_". $i ."','". $row['efternamn'] ."'],";
        $response .= "['tid_start_".      $i ."','". substr($row['tid_start'],0,5) ."'],";
        $response .= "['tid_stop_".       $i ."','". substr($row['tid_stop'],0,5) ."'],";
        $response .= "['video_".          $i ."','". $row['video'] ."'],";
        $response .= "['foto_".           $i ."','". $row['foto'] ."'],";
        $i = $i +1;
      }
      $response = substr($response, 0, -1); // Remove last ","

    }



    // --- Booked photographer times ---
    $query  = "SELECT t1.*,
                      t2.*,
                      t3.id      AS p_id,
                      t3.fornamn AS p_fornamn,
                      t5.id      AS f_id,
                      t5.fornamn AS f_fornamn
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum    = t1.id 
                        JOIN      tandem_piloter      AS t3 ON t2.id_pilot    = t3.id
                        LEFT JOIN tandem_fotografer   AS t5 ON t2.id_fotograf = t5.id 
               WHERE t1.datum='$date'";


    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);

    // Add to the ajax response string.
    // The '|' is used to seperate queries in respons string
    $i         = 1;
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['id_".        $i ."','". $row['f_id']            ."'],";
        $response .= "['tid_".       $i ."','". substr($row['tid'],0,2) . substr($row['tid'],3,2) ."'],";
        $response .= "['pilot_".     $i ."','". $row['p_fornamn']       ."'],";
        $response .= "['pilot_id_".  $i ."','". $row['p_id']            ."'],";
        $response .= "['fotograf_".  $i ."','". $row['f_fornamn']       ."'],";
        $i = $i +1;
      }
      $response = substr($response, 0, -1); // Remove last ","

    }
    echo($response);
  }


  //------------------------------------------------------------------
  // Get single 'presentkort'
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'get_pk') {
  
    // Arguments
    $id = $_GET["id"];

    // Query database
    $query      = "SELECT tandem_pax.*, tandem_schema_pilot.id_pax AS bokad FROM tandem_pax LEFT JOIN tandem_schema_pilot ON tandem_pax.id = tandem_schema_pilot.id_pax WHERE tandem_pax.id = $id";
    $result     = mysql_query($query) or die(mysql_error());
    $row        = mysql_fetch_array($result);

    // Create the ajax response string
    $response  = "";
    $response .= "['use_contact',        '". $row['use_contact']        ."'],";
    $response .= "['pax_fornamn',        '". $row['pax_fornamn']        ."'],";
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
    $response .= "['video',              '". $row['video']              ."'],";
    $response .= "['foto',               '". $row['foto']               ."'],";
    $response .= "['bokad',              '". $row['bokad']               ."'],";
    $response .= "['giltigt_till',       '". $row['giltigt_till']       ."'],";
    $response .= "['betalat',            '". $row['betalat']            ."'],";
    $response .= "['ovrigt',             '". $row['ovrigt']             ."'],";
    $response .= "['tillagd',            '". $row['tillagd']            ."'],";
    $response .= "['id',                 '". $row['id']                 ."']";

    echo($response);

  }



  //------------------------------------------------------------------
  // Get all 'presentkort'
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'get_all_pk') {
  
    // Query database
    $query      = "SELECT tandem_pax.*, tandem_schema_pilot.id_pax AS bokad FROM tandem_pax LEFT JOIN tandem_schema_pilot ON tandem_pax.id = tandem_schema_pilot.id_pax";
    $result     = mysql_query($query) or die(mysql_error());
    $match_rows = mysql_num_rows($result);

    // Create the ajax response string
    $response  = "";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
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
        $response .= "['video',              '". $row['video']              ."'],";
        $response .= "['foto',               '". $row['foto']               ."'],";
        $response .= "['bokad',              '". $row['bokad']              ."'],";
        $response .= "['giltigt_till',       '". $row['giltigt_till']       ."'],";
        $response .= "['betalat',            '". $row['betalat']            ."'],";
        $response .= "['ovrigt',             '". $row['ovrigt']             ."'],";
        $response .= "['tillagd',            '". $row['tillagd']            ."'],";
        $response .= "['id',                 '". $row['id']                 ."']|";
      }
      $response = substr($response, 0, -1); // Remove last "|"
    }
    echo($response);

  }


  //--------------------------------------------------------------------
  // New 'presentkort
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'new_pax') {

    // Check if NULL
    if ($_GET['name'])                  { $name                  = "'" . mysql_escape_string($_GET['name'])                  . "'"; } else { $name                  = "NULL"; }
    if ($_GET['s2_pax_fornamn'])        { $s2_pax_fornamn        = "'" . mysql_escape_string($_GET['s2_pax_fornamn'])        . "'"; } else { $s2_pax_fornamn        = "NULL"; }
    if ($_GET['s2_pax_efternamn'])      { $s2_pax_efternamn      = "'" . mysql_escape_string($_GET['s2_pax_efternamn'])      . "'"; } else { $s2_pax_efternamn      = "NULL"; }
    if ($_GET['s2_pax_adress_1'])       { $s2_pax_adress_1       = "'" . mysql_escape_string($_GET['s2_pax_adress_1'])       . "'"; } else { $s2_pax_adress_1       = "NULL"; }
    if ($_GET['s2_pax_adress_2'])       { $s2_pax_adress_2       = "'" . mysql_escape_string($_GET['s2_pax_adress_2'])       . "'"; } else { $s2_pax_adress_2       = "NULL"; }
    if ($_GET['s2_pax_postnummer'])     { $s2_pax_postnummer     = "'" . mysql_escape_string($_GET['s2_pax_postnummer'])     . "'"; } else { $s2_pax_postnummer     = "NULL"; }
    if ($_GET['s2_pax_ort'])            { $s2_pax_ort            = "'" . mysql_escape_string($_GET['s2_pax_ort'])            . "'"; } else { $s2_pax_ort            = "NULL"; }
    if ($_GET['s2_pax_telefon'])        { $s2_pax_telefon        = "'" . mysql_escape_string($_GET['s2_pax_telefon'])        . "'"; } else { $s2_pax_telefon        = "NULL"; }
    if ($_GET['s2_pax_email'])          { $s2_pax_email          = "'" . mysql_escape_string($_GET['s2_pax_email'])          . "'"; } else { $s2_pax_email          = "NULL"; }
    if ($_GET['s2_pax_langd'])          { $s2_pax_langd          = "'" . mysql_escape_string($_GET['s2_pax_langd'])          . "'"; } else { $s2_pax_langd          = "NULL"; }
    if ($_GET['s2_pax_vikt'])           { $s2_pax_vikt           = "'" . mysql_escape_string($_GET['s2_pax_vikt'])           . "'"; } else { $s2_pax_vikt           = "NULL"; }
    if ($_GET['s2_kontakt_fornamn'])    { $s2_kontakt_fornamn    = "'" . mysql_escape_string($_GET['s2_kontakt_fornamn'])    . "'"; } else { $s2_kontakt_fornamn    = "NULL"; }
    if ($_GET['s2_kontakt_efternamn'])  { $s2_kontakt_efternamn  = "'" . mysql_escape_string($_GET['s2_kontakt_efternamn'])  . "'"; } else { $s2_kontakt_efternamn  = "NULL"; }
    if ($_GET['s2_kontakt_adress_1'])   { $s2_kontakt_adress_1   = "'" . mysql_escape_string($_GET['s2_kontakt_adress_1'])   . "'"; } else { $s2_kontakt_adress_1   = "NULL"; }
    if ($_GET['s2_kontakt_adress_2'])   { $s2_kontakt_adress_2   = "'" . mysql_escape_string($_GET['s2_kontakt_adress_2'])   . "'"; } else { $s2_kontakt_adress_2   = "NULL"; }
    if ($_GET['s2_kontakt_postnummer']) { $s2_kontakt_postnummer = "'" . mysql_escape_string($_GET['s2_kontakt_postnummer']) . "'"; } else { $s2_kontakt_postnummer = "NULL"; }
    if ($_GET['s2_kontakt_ort'])        { $s2_kontakt_ort        = "'" . mysql_escape_string($_GET['s2_kontakt_ort'])        . "'"; } else { $s2_kontakt_ort        = "NULL"; }
    if ($_GET['s2_kontakt_telefon'])    { $s2_kontakt_telefon    = "'" . mysql_escape_string($_GET['s2_kontakt_telefon'])    . "'"; } else { $s2_kontakt_telefon    = "NULL"; }
    if ($_GET['s2_kontakt_email'])      { $s2_kontakt_email      = "'" . mysql_escape_string($_GET['s2_kontakt_email'])      . "'"; } else { $s2_kontakt_email      = "NULL"; }
    if ($_GET['s2_betalningssatt'])     { $s2_betalningssatt     = "'" . mysql_escape_string($_GET['s2_betalningssatt'])     . "'"; } else { $s2_betalningssatt     = "NULL"; }
    if ($_GET['s2_betalat'])            { $s2_betalat            = "'" . mysql_escape_string($_GET['s2_betalat'])            . "'"; } else { $s2_betalat            = "NULL"; }
    if ($_GET['s2_ovrigt'])             { $s2_ovrigt             = "'" . mysql_escape_string($_GET['s2_ovrigt'])             . "'"; } else { $s2_ovrigt             = "NULL"; }

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
                    betalningssatt,
                    betalat,
                    ovrigt,
                    tillagd)
                 VALUES(
                    '0',
                    '$_GET[use_contact]',
                    $s2_pax_fornamn,
                    $s2_pax_efternamn,
                    $s2_pax_adress_1,
                    $s2_pax_adress_2,
                    $s2_pax_postnummer,
                    $s2_pax_ort,
                    $s2_pax_telefon,
                    $s2_pax_email,
                    $s2_pax_langd,
                    $s2_pax_vikt,
                    $s2_kontakt_fornamn,
                    $s2_kontakt_efternamn,
                    $s2_kontakt_adress_1,
                    $s2_kontakt_adress_2,
                    $s2_kontakt_postnummer,
                    $s2_kontakt_ort,
                    $s2_kontakt_telefon,
                    $s2_kontakt_email,
                    '0',
                    '$_GET[s2_video]',
                    '$_GET[s2_photo]',
                    $s2_betalningssatt,
                    $s2_betalat,
                    $s2_ovrigt,
                     NOW())") or die(mysql_error());

    // Return the newly created ID
    echo(mysql_insert_id());
  }


  //--------------------------------------------------------------------
  // Change tandem_pax
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'change_pax_pk') {
    $id = $_GET['id'];
    foreach($_GET as $key => &$val) {
      if (($key != 'action') && ($key != 'C5_URL') && ($key != 'id')) {
        mysql_query("UPDATE tandem_pax SET $key = '$val' WHERE id = '$id'") or die("Query: " . mysql_error());
      }
    }
    echo(true);
  } 


  //--------------------------------------------------------------------
  // New tandem booking
  //--------------------------------------------------------------------

  else if ($_GET['action'] == 'new_tandem_booking') {

    // Check if NULL
    if ($_GET['s2_foto_dropdown']) { $s2_foto_dropdown = "'" . mysql_escape_string($_GET['s2_foto_dropdown']) . "'"; } else { $s2_foto_dropdown = "NULL"; }
    if ($_GET['s2_b_ovrigt'])      { $s2_b_ovrigt      = "'" . mysql_escape_string($_GET['s2_b_ovrigt'])      . "'"; } else { $s2_b_ovrigt      = "NULL"; }

    mysql_query("UPDATE 
                   tandem_schema_pilot
                 SET
                   id_fotograf = $s2_foto_dropdown,
                   id_pax      = '$_GET[s2_pk_nr]',
                   bokare      = '$_GET[s2_bokare]',
                   ovrigt      = $s2_b_ovrigt
                 WHERE
                   id = '$_GET[s2_form_time_id]'") or die(mysql_error()) ; 

    echo("OK");

  } 


//************************************************************************************

// Close database connection
mysql_close($connection);


?>
