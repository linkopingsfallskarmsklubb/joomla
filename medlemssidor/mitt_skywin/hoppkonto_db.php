<?php
  
  //------------------------------------------------
  // Connect to Skywin database
  //------------------------------------------------

  $db = "lfk_skywin";
  include($_SERVER["DOCUMENT_ROOT"] . $_GET['C5_URL'] . "/single_pages/includes/db_connect.php");


  //------------------------------------------------
  // Fetch all
  //------------------------------------------------


  if ($_GET['action'] == 'get_all') {
    
    $query      = "SELECT Balance, AccountNo FROM member WHERE MemberNo = ". $_GET[sff_nr];
    $result     = mysql_query($query) or die(mysql_error());
    $row        = mysql_fetch_array($result) or die(mysql_error());
    $response   = $row['Balance'] . "|";
    $account_no = $row['AccountNo'];
    
    $query     = "SELECT LastUpd FROM trans WHERE AccountNo = ". $row['AccountNo'] ." ORDER BY LastUpd DESC LIMIT 1";
    $result    = mysql_query($query) or die(mysql_error());
    $row       = mysql_fetch_array($result) or die(mysql_error());
    $response .= substr($row['LastUpd'],0,10) . "|";
    

    $query   = "SELECT TransType, AccountType, RegDate, Amount, Balance, Comment FROM trans WHERE AccountNo = ". $account_no ." ORDER BY RegDate DESC";
    $result  = mysql_query($query) or die(mysql_error());

    while ($row = mysql_fetch_array($result)) {
      $response .= "['TransType',   '".         $row['TransType']     ."'],";
      $response .= "['AccountType', '".         $row['AccountType']   ."'],";
      $response .= "['RegDate',     '".  substr($row['RegDate'],0,10) ."'],";
      $response .= "['Amount',      '".         $row['Amount']        ."'],";
      $response .= "['Balance',     '".         $row['Balance']       ."'],";
      $response .= "['Comment',     '".         $row['Comment']       ."']|";
    }
    $response = substr($response, 0, -1); // Remove last "|"

    echo($response);
  }



  // Close database connection
  mysql_close($connection);


?>

