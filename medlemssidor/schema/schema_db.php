<?php

// Connect to database and select "lfk_misc"
include("../../includes/db_connect.php");


//------------------------------------------------------------------
// Step 1. 
//------------------------------------------------------------------

  if ($_GET['action'] == 'get_event_days') {

    //------------------------------------------------
    // Query database
    // Dates with scheduled jumping
    //------------------------------------------------

    $date  = $_GET["date"];
  
    $query  = "SELECT datum FROM hopp_schema WHERE YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date') GROUP BY datum";
    $result = mysql_query($query) or die(mysql_error());
  
    // Create the ajax response string
    $response = "";
    while ($row = mysql_fetch_array($result)) {
      $response .= $row['datum'] .",";
    }
    $response  = substr($response, 0, -1); // Remove last ","


    //------------------------------------------------
    // Query database
    // Dates with scheduled tandems
    //------------------------------------------------

    $query  = "SELECT datum FROM tandem_schema_pilot AS t1 
                            JOIN tandem_schema_datum AS t2 ON t1.id_datum = t2.id 
               WHERE YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date') 
               GROUP BY datum";

    $result = mysql_query($query);
  
    // Add to the ajax response string.
    // The '|' is used to seperate 'hoppschema' and 'tandemschema'.
    $response .= "|";
    while ($row = mysql_fetch_array($result)) {
      $response .= $row['datum'] .",";
    }
    $response  = substr($response, 0, -1); // Remove last ","


    //------------------------------------------------
    // Query database
    // Dates with booked tandems
    //------------------------------------------------

    $query  = "SELECT datum 
               FROM   tandem_schema_pilot AS t1 
               JOIN   tandem_schema_datum AS t2 ON t1.id_datum = t2.id AND t1.id_pax != 0  
               WHERE  YEAR(datum) = YEAR('$date') AND MONTH(datum) = MONTH('$date') 
               GROUP BY datum";

    $result = mysql_query($query);
  
    // Add to the ajax response string.
    // The '|' is used to seperate 'hoppschema' and 'tandemschema'.
    $response .= "|";
    while ($row = mysql_fetch_array($result)) {
      $response .= $row['datum'] .",";
    }
    $response  = substr($response, 0, -1); // Remove last ","

    echo($response);
  } 


//------------------------------------------------------------------
// Step 2. 
//------------------------------------------------------------------

  //------------------------------------------------------------------
  // Get all schedudeled personell
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'list_day') {


    $date     = $_GET["date"];
    $response = $date;


    //----------------------------------------
    // Query database - General info
    //----------------------------------------

    $sql        = "SELECT * FROM hopp_schema WHERE (datum = '". $date ."')";
    $result     = mysql_query($sql) or die(mysql_error());
    $match_rows = mysql_num_rows($result);
  
  
    // Add to the ajax response string.
    // The '|' is used to seperate 'hoppschema' and 'tandemschema'.
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['tid_start',   '".  substr($row['tid_start'],0,-3) ."'],";
        $response .= "['tid_stop',    '".  substr($row['tid_stop'],0,-3)  ."'],";
      }
      $response = substr($response, 0, -1); // Remove last ","
    }


    // Number of tandems
    $sql = "SELECT  t1.datum,
                    t2.id_datum,
                    t2.id_pilot,
                    t2.id_pax
            FROM tandem_schema_datum AS t1 
            JOIN tandem_schema_pilot AS t2 ON t2.id_datum = t1.id
            WHERE t1.datum = '". $date ."' AND t2.id_pax != 0";

    $result     = mysql_query($sql);
    $match_rows = mysql_num_rows($result);
    $response  .= "|['nr_tandems',   '". $match_rows ."']";


    // Personnel
    $sql    = "SELECT * FROM ( 
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      'hl' AS 'type'
               FROM hopp_schema    AS t1
               JOIN hopp_schema_hl AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer   AS t3 ON t3.id       = t2.id_namn
               WHERE t1.datum = '". $date ."'
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      'hm' AS 'type'
               FROM hopp_schema    AS t1
               JOIN hopp_schema_hm AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer   AS t3 ON t3.id       = t2.id_namn
               WHERE t1.datum = '". $date ."'
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      'manifest' AS 'type'
               FROM hopp_schema          AS t1
               JOIN hopp_schema_manifest AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer         AS t3 ON t3.id       = t2.id_namn
               WHERE t1.datum = '". $date ."'
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      'aff' AS 'type'
               FROM hopp_schema          AS t1
               JOIN hopp_schema_aff      AS t2 ON t2.id_datum = t1.id
               JOIN instruktorer         AS t3 ON t3.id       = t2.id_namn
               WHERE t1.datum = '". $date ."'
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      'pilot' AS 'type'
               FROM hopp_schema          AS t1
               JOIN hopp_schema_pilot    AS t2 ON t2.id_datum = t1.id
               JOIN piloter              AS t3 ON t3.id       = t2.id_namn
               WHERE t1.datum = '". $date ."'
             UNION ALL
               SELECT t1.datum,
                      t2.id_fotograf,
                      t2.id_pax,
                      t3.fornamn,
                      t3.efternamn,
                      'tandem_p' AS 'type'
               FROM tandem_schema_datum AS t1
               JOIN tandem_schema_pilot AS t2 ON t2.id_datum = t1.id
               JOIN tandem_piloter      AS t3 ON t3.id       = t2.id_pilot
               WHERE t1.datum = '". $date ."'
               GROUP BY t3.fornamn, t3.efternamn
             UNION ALL
               SELECT t1.datum,
                      t2.tid_start,
                      t2.tid_stop,
                      t3.fornamn,
                      t3.efternamn,
                      'tandem_f' AS 'type'
               FROM tandem_schema_datum AS t1
               JOIN tandem_schema_foto  AS t2 ON t2.id_datum = t1.id
               JOIN tandem_fotografer   AS t3 ON t3.id       = t2.id_fotograf
               WHERE t1.datum = '". $date ."' 
               GROUP BY t3.fornamn, t3.efternamn) AS thestuff";


    $result     = mysql_query($sql);
    $match_rows = mysql_num_rows($result);

    // Create the ajax response string
    $response .= "|";
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['type',        '".  $row['type']                   ."'],";
        $response .= "['fornamn',     '".  $row['fornamn']                ."'],";
        $response .= "['efternamn',   '".  $row['efternamn']              ."'],";
        $response .= "['tid_start',   '".  substr($row['tid_start'],0,-3) ."'],";
        $response .= "['tid_stop',    '".  substr($row['tid_stop'],0,-3)  ."']~";
      }
      $response = substr($response, 0, -1); // Remove last "~"
    }


    //----------------------------------------
    // Query database - Tandems
    //----------------------------------------

    $sql    = "SELECT t1.*,
                      t2.*, 
                      t3.fornamn             AS p_fornamn,
                      t3.efternamn           AS p_efternamn,
                      t4.fornamn             AS f_fornamn,
                      t4.efternamn           AS f_efternamn,
                      t5.id                  AS pax_id,
                      t5.pax_fornamn         AS pax_fornamn,
                      t5.pax_efternamn       AS pax_efternamn,
                      t5.pax_adress_1        AS pax_adress_1,
                      t5.pax_adress_2        AS pax_adress_2,
                      t5.pax_postnummer      AS pax_postnummer,
                      t5.pax_ort             AS pax_ort,
                      t5.pax_telefon         AS pax_telefon,
                      t5.pax_email           AS pax_email,
                      t5.pax_vikt            AS pax_vikt,
                      t5.pax_langd           AS pax_langd,
                      t5.kontakt_fornamn     AS kontakt_fornamn,
                      t5.kontakt_efternamn   AS kontakt_efternamn,
                      t5.kontakt_adress_1    AS kontakt_adress_1,
                      t5.kontakt_adress_2    AS kontakt_adress_2,
                      t5.kontakt_postnummer  AS kontakt_postnummer,
                      t5.kontakt_ort         AS kontakt_ort,
                      t5.kontakt_telefon     AS kontakt_telefon,
                      t5.kontakt_email       AS kontakt_email,
                      t5.ovrigt              AS pax_ovrigt         
                        FROM      tandem_schema_datum AS t1 
                        JOIN      tandem_schema_pilot AS t2 ON t2.id_datum = t1.id 
                        JOIN      tandem_piloter      AS t3 ON t3.id       = t2.id_pilot
                        LEFT JOIN tandem_fotografer   AS t4 ON t4.id       = t2.id_fotograf  
                        JOIN      tandem_pax          AS t5 ON t5.id       = t2.id_pax 
         WHERE (t1.datum = '". $date ."') ORDER BY t2.tid";

    $result     = mysql_query($sql);
    $match_rows = mysql_num_rows($result);
  
  
    // Add to the ajax response string.
    // The '|' is used to seperate 'hoppschema' and 'tandemschema'.
    $response .= "|";
    $i = 1;
    if ($match_rows != 0) {
      while ($row = mysql_fetch_array($result)) {
        $response .= "['tid_".                $i ."','".  substr($row['tid'],0,-3)   ."'],";
        $response .= "['p_fornamn_".          $i ."','".  $row['p_fornamn']          ."'],";
        $response .= "['p_efternamn_".        $i ."','".  $row['p_efternamn']        ."'],";
        $response .= "['f_fornamn_".          $i ."','".  $row['f_fornamn']          ."'],";
        $response .= "['f_efternamn_".        $i ."','".  $row['f_efternamn']        ."'],";
        $response .= "['b_ovrigt_".           $i ."','".  $row['ovrigt']             ."'],";
        $response .= "['pax_id_".             $i ."','".  $row['pax_id']             ."'],";
        $response .= "['pax_fornamn_".        $i ."','".  $row['pax_fornamn']        ."'],";
        $response .= "['pax_efternamn_".      $i ."','".  $row['pax_efternamn']      ."'],";
        $response .= "['pax_adress_1_".       $i ."','".  $row['pax_adress_1']       ."'],";
        $response .= "['pax_adress_2_".       $i ."','".  $row['pax_adress_2']       ."'],";
        $response .= "['pax_postnummer_".     $i ."','".  $row['pax_postnummer']     ."'],";
        $response .= "['pax_ort_".            $i ."','".  $row['pax_ort']            ."'],";
        $response .= "['pax_telefon_".        $i ."','".  $row['pax_telefon']        ."'],";
        $response .= "['pax_email_".          $i ."','".  $row['pax_email']          ."'],";
        $response .= "['pax_vikt_".           $i ."','".  $row['pax_vikt']           ."'],";
        $response .= "['pax_langd_".          $i ."','".  $row['pax_langd']          ."'],";
        $response .= "['kontakt_fornamn_".    $i ."','".  $row['kontakt_fornamn']    ."'],";
        $response .= "['kontakt_efternamn_".  $i ."','".  $row['kontakt_efternamn']  ."'],";
        $response .= "['kontakt_adress_1_".   $i ."','".  $row['kontakt_adress_1']   ."'],";
        $response .= "['kontakt_adress_2_".   $i ."','".  $row['kontakt_adress_2']   ."'],";
        $response .= "['kontakt_postnummer_". $i ."','".  $row['kontakt_postnummer'] ."'],";
        $response .= "['kontakt_ort_".        $i ."','".  $row['kontakt_ort']        ."'],";
        $response .= "['kontakt_telefon_".    $i ."','".  $row['kontakt_telefon']    ."'],";
        $response .= "['kontakt_email_".      $i ."','".  $row['kontakt_email']      ."'],";
        $response .= "['pax_ovrigt_".         $i ."','".  $row['pax_ovrigt']         ."'],";
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
// Step 3. 
//------------------------------------------------------------------

  //------------------------------------------------------------------
  // Set tandem to 'hoppat'
  //------------------------------------------------------------------

  else if ($_GET['action'] == 'jumped') {

    $id = $_GET["id"];

    $sql        = "UPDATE tandem_pax SET hoppat=1 WHERE id='". $id ."'";
    $result     = mysql_query($sql) or die(mysql_error());;
    $match_rows = mysql_affected_rows();

    echo($match_rows); 
  }




// Close database connection
mysql_close($connection);

?>
