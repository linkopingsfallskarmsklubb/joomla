<!-- Global Javascript and PHP variables -->
<?php
  $u = new User();
  if ($u && $u->isLoggedIn ()) {
    $ui           = UserInfo::getByID($u->getUserID());
    $G_NAME       = $ui->getAttribute('FirstName') ." ". $ui->getAttribute('LastName');
    $G_USERNAME   = $ui->getUserName();
    $G_SFF_NR     = $ui->getAttribute('LicensNummer');?>
    <script>
      var G_NAME       = '<?=$G_NAME?>';
      var G_USERNAME   = '<?=$G_USERNAME?>';
      var G_SFF_NR     = '<?=$G_SFF_NR?>';
    </script> <?php
  }
  else {
    $G_LOGGED_IN  = 0; ?>
    <script>
      var G_LOGGED_IN  =  <?=$G_LOGGED_IN?>;
    </script> <?php
  }
?>

<h1>Hoppkonto</h1>

<div id='summary_div'>
  <table id='summary_table'>
    <tr>
      <td>Saldo:</td><td id='balance'></td>
    </tr>
    <tr>
      <td>Uppdaterad:</td><td id='updated'></td>
    </tr>
  </table>
</div>

<div id='account_div'>
  <table id='table' class='table_list'>
    <thead>
      <tr>
        <th class='list_header'>Typ     </th>
        <th class='list_header'>Orsak   </th>
        <th class='list_header'>Datum   </th>
        <th class='list_header'>Belopp  </th>
        <th class='list_header'>Saldo   </th>
        <th class='list_header'>Fritext </th>
      </tr>
    </thead>
    <tbody>
      <!-- Content added by ajax -->
    </tbody>
  </table>
</div>

        
