<?php
  // Connect to custom database
include($_SERVER["DOCUMENT_ROOT"] . DIR_REL . "/single_pages/includes/db_connect.php");
?>

<!-- Html Tooltips -->
<script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/tooltip/wz_tooltip.js"></script> 


<h1>Pilottider</h1>


<!-- ================ -->
<!-- Step 1.          -->
<!-- ================ -->

<!-- Ajax. Assert warning if no jumping is planned for selected day -->
<div id='schema_varning'>
</div>

<!-- Calendar -->
<div id='step_1'>
  <p><b>1. Välj datum</b></p>
  <div id='calendar' class='cal_cont' >
  </div>
  <div style='clear:both; margin-left: 20px;'>
    <table>
      <tr><td align='right' style='color: #00AA00;'>Grön:   </td><td>Hoppning planerad</td></tr>
      <tr><td align='right' style='color: #FF632F;'>Orange: </td><td>Tandempilot schemalagd</td></tr>
      <tr><td align='right' style='font-weight: bold;'>Fet: </td><td>Fotograf schemalagd</td></tr>
    </table>
  </div>
</div>


<!-- ================ -->
<!-- Step 2.          -->
<!-- ================ -->

<!-- Pilots -->
<div id='step_2'>
 
  <!-- Select pilot -->
  <?php
    // Query database
    $query = "SELECT * FROM tandem_piloter WHERE aktiv='1' ORDER BY fornamn"; 
    $result = mysql_query($query) or die(mysql_error());
  ?>
  <div id='select_pilot'>
    <p><b>2. Välj tandempilot</b></p>
    <select name="name_dropdown" id='name_dropdown' onchange="select_pilot_f(this.value);">
      <option value='0'>-- Välj tandempilot --</option>
      <?php
        while ($row = mysql_fetch_array($result)) {
          echo("<option value='".$row['id']."'>".$row['fornamn']." ".$row['efternamn']."</option>");
        }
      ?>
    </select>
  </div>

  <!-- Date, jumping hours -->
  <div id='day_info'>
    <ul class='clean_list'>
      <li><div class='c1 left'>Datum:</div>     <div class='c2 left' id='datum'></div></li>
      <li><div class='c1 left'>Hopptider:</div> <div class='c2 left' id='hopptider'></div></li>
      <li>&nbsp;</li>
    </ul>
  </div>

  <!-- Already booked pilots -->
  <div id='booked_pilots'>
    <!--Populated by AJAX -->
  </div>

</div>


<!-- Delete time -->

<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>

<!-- Delete popup -->
<div id='delete_time_popup' class='popup'>

  <p class='popup_h2'>Ta bort tid</p>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#delete_time_popup, #overlay').popHide();"></span>
  </div>
  <table>
    <tr><td width='100'>Datum: </td> <td id='delete_p_datum'></td></tr>
    <tr><td width='100'>Tid:   </td> <td id='delete_p_tid'>  </td></tr>
    <tr><td width='100'>Pilot: </td> <td id='delete_p_pilot'></td></tr>
    <tr><td>&nbsp;</td></tr>
  </table>
  <table width='100%'>
    <tr>
      <td align='right'><input class='button' type='button' name='delete_time_submit' id='delete_time_submit' value='Ta bort' onclick="delete_time_f($('#delete_p_id').val(), $('#delete_p_date').val());"></td>
      <td align='left'><input class='button' type='button' name='delete_time_abort'  id='delete_time_abort'  value='Avbryt'  onclick="$('#delete_time_popup, #overlay').popHide();"></td>
    </tr>
  </table>
  <input type='hidden' name='delete_p_id'   id='delete_p_id'   value=''/>
  <input type='hidden' name='delete_p_date' id='delete_p_date' value=''/>
</div>


<!-- ================ -->
<!-- Step 3.          -->
<!-- ================ -->

<!-- Select times -->
<div id='step_3'>
  
  <!-- New times -->
  <div id='s3_new'>
    
    <!-- Check boxes -->
    <div id='times'>
      <p id='s3_header_new'><b>3. Välj Tider</b></p>
      <p id='s3_header_update'><b>3. Uppdatera tider</b></p>
      <div>
        <table id='select_times_table'>
          <tbody id='select_times_tbody'>
            <!--Populated by AJAX -->
          </tbody>
        </table>
      </div>
      <div id='times_submit'>
        <input type='hidden' name='form_name'           id='form_name'  value='' />
        <input type='hidden' name='form_date'           id='form_date'  value='' />
        <input type='hidden' name='form_start'          id='form_start' value='' />
        <input type='hidden' name='form_stop'           id='form_stop'  value='' />
        <input type='button' name='new_pilot_submit'    id='new_pilot_submit'    class='button' value='Lägg till' onclick="submit_f('new')" />
        <input type='button' name='update_pilot_submit' id='update_pilot_submit' class='button' value='Uppdatera' onclick="submit_f('update')" />
      </div>
    </div>
    
    <!-- Box with time control -->
    <div id='change_times'>
  
      <!-- More/less times before -->
      <div id='times_before' class='cll'>
        <div class='left'>
          <span class='icon-plus-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' style='font-size: 1.5em' onmouseover='Tip(tip_more_before)' onmouseout='UnTip()' onclick="add_times_f('before');"></span>
        </div>
        <div class='right'>
          <span class='icon-minus-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' style='font-size: 1.5em' onmouseover='Tip(tip_less_before)' onmouseout='UnTip()' onclick="remove_times_f('before');"></span>
        </div>
      </div>
  
      <!-- Interval -->
      <div id='times_interval' class='cll'>
        <select name="interv_dropdown" id='interv_dropdown' onchange="check_cb_f($(this).val())" onmouseover='Tip(tip_interval)' onmouseout='UnTip()' style='width: 70px; padding: 0;'>
          <option value='30'>30 min</option>
          <option value='60'>60 min</option>
          <option value='90'>90 min</option>
          <option value='120'>120 min</option>
          <option value='150'>150 min</option>
          <option value='180'>180 min</option>
        </select>
      </div>
      
      <!-- Move selected times 30 minutes earlier/later -->
      <div id='times_move' class='cll'>
        <div class='left'>
          <span class='icon-arrow-left icon-large icon-st-lightblue icon-st-shadow icon-st-click' style='font-size: 1.5em' onmouseover='Tip(tip_move_back)' onmouseout='UnTip()' onclick="move_times_f('earlier');"></span>
        </div>
        <div class='right'>
          <span class='icon-arrow-right icon-large icon-st-lightblue icon-st-shadow icon-st-click' style='font-size: 1.5em' onmouseover='Tip(tip_move_forward)' onmouseout='UnTip()' onclick="move_times_f('later');"></span>
        </div>
      </div>
          
      <!-- More/less times after -->
      <div id='times_after' class='cll'>
        <div class='left'>
          <span class='icon-plus-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' style='font-size: 1.5em' onmouseover='Tip(tip_more_after)' onmouseout='UnTip()' onclick="add_times_f('after');"></span>
        </div>
        <div class='right'>
          <span class='icon-minus-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' style='font-size: 1.5em' onmouseover='Tip(tip_less_after)' onmouseout='UnTip()' onclick="remove_times_f('after');"></span>
        </div>
      </div>
    </div>
  </div>
  
</div>


<!-- ================ -->
<!-- Instructions     -->
<!-- ================ -->

<div id='info' class='info'>
  <p>Här lägger piloter in de tider som de vill hoppa. Välj först datum genom att klicka i kalendern, välj sedan pilot, välj därefter de tider du vill hoppa.</p>
  <p>De förvalda tiderna motsvar det intervall som varje pilot har valt på sidan "Piloter & Fotografer". Man kan flytta samtliga tider framåt/bakåt i 30-miutersintervall genom att klicka på vänsterpil/högerpil. Man kan även visa fler tider som ligger utanför den planerade hoppningen genom att trycka på plus/minusknapparna.</p>
  <p>Vill man inte längre vara registrerad på en tid så kan man ta bort den. Systemet tillåter inte att man tar bort en tid där man har ett tandem inbokat. Vill man ändå göra det måste man först boka om tandemet.</p>
  <p>Genom att välja en tandempilot som redan är schemalagd på aktuell dag kan man ändra dess tider.</p>
  <p>På sidan "Scheman" kan man se sitt schema över de tider man har registrerat samt sina bokningar. </p>
  <p></p>
</div>





<?php
  // Return to Concrete5 database session
  $db = Loader::db(null, null, null, null, true);
?>
