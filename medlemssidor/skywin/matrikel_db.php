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
  // Fetch all members
  //------------------------------------------------

  else if ($_GET['action'] == 'get_all') {

    $year  = $_GET["year"];

    $query = "SELECT DISTINCT
                t1.InternalNo,
                t1.MemberNo,
                t1.FirstName,
                t1.LastName,
                t1.Emailaddress,
                t1.Club,
                t2.InternalNo,
                t2.Year
              FROM member        AS t1 
              JOIN memberlicense AS t2 
              WHERE 
                t1.Club       = 'LFK'          AND
                t2.Year       = '". $year  ."' AND
                t1.InternalNo = t2.InternalNo";


    $result = mysql_query($query) or die(mysql_error());

    $response = "";
    while ($row = mysql_fetch_array($result)) {
      $response .= "['MemberNo',     '".  $row['MemberNo']      ."'],";
      $response .= "['InternalNo',   '".  $row['InternalNo']    ."'],";
      $response .= "['FirstName',    '".  $row['FirstName']     ."'],";
      $response .= "['LastName',     '".  $row['LastName']      ."'],";
      $response .= "['Emailaddress', '".  $row['Emailaddress']  ."'],";
      $response .= "['Year',         '".  $row['Year']          ."']";
      $response .= "|";
    }
    $response = substr($response, 0, -1); // Remove last "|"
    
    echo($response);
  
  } 


  //------------------------------------------------
  // Get details for specific member
  //------------------------------------------------

  else if ($_GET['action'] == 'get_details') {

    $sffNr = $_GET["sffNr"];
    $year  = $_GET["year"];

    // Misc details
    $query = "SELECT DISTINCT
                t1.InternalNo,
                t1.MemberNo,
                t1.DateOfBirth,
                t1.FirstName,
                t1.LastName,
                t1.NickName,
                t1.Address1,
                t1.Address2,
                t1.Postcode,
                t1.Posttown,
                t1.Emailaddress,
                t1.Club,
                t1.Pilot,
                t1.Balance,
                t2.InternalNo,
                t2.Year
              FROM member         AS t1 
              JOIN memberlicense  AS t2 
              WHERE 
                t1.MemberNo   = '". $sffNr ."' AND
                t2.Year       = '". $year  ."' AND
                t1.InternalNo = t2.InternalNo";

    $result   = mysql_query($query)      or die(mysql_error());

    $response = "";
    while ($row = mysql_fetch_array($result)) {

      $intNr = $row['InternalNo'];

      $response .= "['InternalNo',   '".  $row['InternalNo']    ."'],";
      $response .= "['MemberNo',     '".  $row['MemberNo']      ."'],";
      $response .= "['DateOfBirth',  '".  $row['DateOfBirth']   ."'],";
      $response .= "['FirstName',    '".  $row['FirstName']     ."'],";
      $response .= "['LastName',     '".  $row['LastName']      ."'],";
      $response .= "['NickName',     '".  $row['NickName']      ."'],";
      $response .= "['Address1',     '".  $row['Address1']      ."'],";
      $response .= "['Address2',     '".  $row['Address2']      ."'],";
      $response .= "['Postcode',     '".  $row['Postcode']      ."'],";
      $response .= "['Posttown',     '".  $row['Posttown']      ."'],";
      $response .= "['Emailaddress', '".  $row['Emailaddress']  ."'],";
      $response .= "['Club',         '".  $row['Club']          ."'],";
      $response .= "['Pilot',        '".  $row['Pilot']         ."'],";
      $response .= "['Balance',      '".  $row['Balance']       ."'],";
      $response .= "['Year',         '".  $row['Year']          ."'],";
    }


    // Phone
    $query  = "SELECT * FROM memberphone WHERE InternalNo='". $intNr."'";
    $result = mysql_query($query)      or die(mysql_error());

    $PhoneHome = "-";
    $PhoneMob  = "-";
    $PhoneWork = "-";
    while ($row = mysql_fetch_array($result)) {
      if ($row['PhoneType'] == 'B') {
        $PhoneHome = $row['PhoneNo'];
      }
      else if ($row['PhoneType'] == 'M') {
        $PhoneMob = $row['PhoneNo'];
      }
      else if ($row['PhoneType'] == 'A') {
        $PhoneWork = $row['PhoneNo'];
      }
    }
    $response .= "['PhoneHome', '". $PhoneHome ."'],";
    $response .= "['PhoneMob',  '". $PhoneMob  ."'],";
    $response .= "['PhoneWork', '". $PhoneWork ."'],";


    // Instructor
    $query    = "SELECT * FROM memberinstruct WHERE InternalNo='". $intNr."' AND year='2011'";
    $result   = mysql_query($query) or die(mysql_error());

    $instr = "";
    while ($row = mysql_fetch_array($result)) {
      $instr .= $row['InstructType'] .", ";
    }
    if ($instr != "") {
      $response .= "['InstructType', '".  $instr ."']";
    }
    else {
      $response .= "['InstructType', '-']";
    }

    echo($response);

  }




// Close database connection
mysql_close($connection);

?>

