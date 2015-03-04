<html>
<head>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<?php
define("DIR_REL", '/marten');
include("schema_head.php");
?>
</head>
<body>
<div id='content' style='clear: both;width: 100%;float: left;'>
<div id='header'>
  <h1>Schema</h1>
</div>
<!-- Print button -->
<div id='print'>
  <input id='print_button' type='button' class='button' value='Skriv ut' onclick="print_f();" />
</div>


<!-- Calendar -->
<div id='calendar_box'>
  <div id='calendar' class='cal_cont' >
  </div>
  <div id='calendar_info'>
    <table>
      <tr><td align='right' style='color:    #00AA00;'>Grön:   </td><td>Hoppning planerad</td></tr>
      <tr><td align='right' style='color:    #FF632F;'>Orange: </td><td>Tandem planerat</td></tr>
      <tr><td align='right' style='font-weight: bold;'>Fet:    </td><td>Tandem bokade</td></tr>
    </table>
  </div>
</div>

<!-- Info div -->
<div id='day_info'>
  <ul class='clean_list nowrap' id='day_info_ul'>
    <!-- Content added by ajax -->
  </ul>
</div>


<!-- Tandem div -->
<div id='tandem_info'>
  <ul id='tandem_info_list' class='clean_list tandem'>
    <!-- Content added by ajax -->
  </ul>
</div>


<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>

<!-- Jumped popup -->
<div id='jumped_popup' class='popup'>
  <!-- Submit -->
  <div id='div_submit'>
    <ul class='clean_list'>
      <li style='width: 100%;'>
        <p class='popup_h2'>Är du säker på att du vill markera som hoppat?</p>
        <p id='jumped_name'></p>
      </li>
      <li style='width: 100%'>
        <input id='mark_ok'    type='button' class='button' style='width: 100px;' value='Ok'     onclick="jumped_confirm_f($('#jumped_id').html());" />
        <input id='mark_abort' type='button' class='button' style='width: 100px;' value='Avbryt' onclick="$('#jumped_popup, #overlay').popHide(); $('#jumped_cb').prop('checked', false); " />
      </li>
    </ul>
  </div>
</div>
    

</div>
</body>
</html>
