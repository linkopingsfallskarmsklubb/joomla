
<!-- Html Tooltips -->
<script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/tooltip/wz_tooltip.js"></script> 

<?php
  // Connect to custom database
  include($_SERVER["DOCUMENT_ROOT"] . DIR_REL . "/single_pages/includes/db_connect.php");
?>


<h1>Fotograftider</h1>

<!-- ---------------- -->
<!-- Step 1.          -->
<!-- ---------------- -->

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
      <tr><td align='right' style='color: #FF632F;'>Orange: </td><td>Fotograf schemalagd</td></tr>
      <tr><td align='right' style='font-weight: bold;'>Fet: </td><td>Tandempilot schemalagd</td></tr>
    </table>
  </div>
</div>

<!-- ---------------- -->
<!-- Step 2.          -->
<!-- ---------------- -->

<!-- Photographers -->
<div id='step_2'>
 
  <!-- Select photographer -->
  <?php
    // Query database
    $query = "SELECT * FROM tandem_fotografer WHERE aktiv='1' ORDER BY fornamn"; 
    $result = mysql_query($query) or die(mysql_error());
  ?>

  <div id='select_photographer'>
    <p><b>2. Välj fotograf</b></p>
    <select class='dropdown' name='name_dropdown' id='name_dropdown' onchange="list_times_f(this.value);">
      <option value='0'>-- Välj fotograf --</option>
      <?php
        while ($row = mysql_fetch_array($result)) {
          echo("<option value='".$row['id']."'>".$row['fornamn']." ".$row['efternamn']."</option>");
        }
      ?>
    </select>
  </div>

  <!-- Ajax. Already booked photographers -->
  <div id='booked_photographers'>
  </div>

  <!-- Ajax. Already booked photographers -->
  <div id='booked_photographers_times'>
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
    <tr><td width='100'>Datum:    </td> <td id='delete_f_date'></td></tr>
    <tr><td width='100'>Tid:      </td> <td id='delete_f_time'>  </td></tr>
    <tr><td width='100'>Fotograf: </td> <td id='delete_f_name'></td></tr>
    <tr><td>&nbsp;</td></tr>
    <input type='hidden' name='delete_f_id' id='delete_f_id' value=''/>
  </table>
  <table width='100%'>
    <tr>
      <td align='right'><input class='button' type='button' name='del_photo_booking_submit' id='del_photo_booking_submit' value='Ta bort' onclick="delete_time_f($('#delete_f_id').val(), $('#delete_f_date').html());"></td>
      <td align='left'><input class='button' type='button' name='del_photo_booking_avbryt' id='del_photo_booking_avbryt' value='Avbryt'  onclick="$('#delete_time_popup, #overlay').popHide();"></td>
    </tr>
  </table>
</div>


<!-- ---------------- -->
<!-- Step 3.          -->
<!-- ---------------- -->

<!-- Select times, 'select_times_table' is populated by AJAX -->
<div id='step_3'>

  <!-- Dropdown boxess -->
  <div id='times'>
    <p><b>3. Välj Tider</b></p>
    <form action='' name='new_photo_booking' id='new_photo_booking'>
      <div id='select_times_table'>
        <table>
          <tr><td>Starttid: </td><td>
          <select class='dropdown' name='start_dropdown' id='start_dropdown'>
            <?php
              $current = 0;
              while ($current <= 86400) {
                $hour = floor($current / 3600);
                $min  = ($current % 3600) / 60;
                $hour = ($hour < 10) ? ("0". $hour) : $hour;
                $min  = ($min  < 10) ? ("0". $min)  : $min;
                echo("<option value='$hour:$min'>$hour:$min</option>");
                $current = $current + 1800;
              }
            ?>
          </select>
          </td></tr>
          <tr><td>Sluttid: </td><td>
          <select class='dropdown' name='stop_dropdown' id='stop_dropdown'>
            <?php
              $current = 0;
              while ($current <= 86400) {
                $hour = floor($current / 3600);
                $min  = ($current % 3600) / 60;
                $hour = ($hour < 10) ? ("0". $hour) : $hour;
                $min  = ($min  < 10) ? ("0". $min)  : $min;
                echo("<option value='$hour:$min'>$hour:$min</option>");
                $current = $current + 1800;
              }
            ?>
          </select>
          </td></tr>
          <tr><td>Fritext: </td><td><input type='text' name='form_ovrigt'  id='form_ovrigt'  value=''/></td></tr>
        </table>
      </div>
      <div id='times_submit'>
        <input type='hidden' name='form_id_name' id='form_id_name' value='' />
          <input type='hidden' name='form_date'    id='form_date'    value='' />
          <input type='hidden' name='form_wday'    id='form_wday'    value='' /> <!-- Only used to pass variable to this function-->
          <input type='hidden' name='form_start'   id='form_start'   value='' /> <!-- Only used to pass variable to this function-->
          <input type='hidden' name='form_stop'    id='form_stop'    value='' /> <!-- Only used to pass variable to this function-->
          <input class='button' type='submit' name='new_photo_submit'    id='new_photo_submit'    value='Lägg till' onclick="submit_f(this.form.id, 'new')" />
          <input class='button' type='submit' name='update_photo_submit' id='update_photo_submit' value='Uppdatera' onclick="submit_f(this.form.id, 'update')" />
      </div>
    </form>
  </div>

</div>


<!-- Illegal time popup popup -->
<div id='illegal_time_popup' class='popup' style='display: none;'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="revert_times_f();"></span>
  </div>
  <div>
    <p>Tandem är inbokat utanför den valda tiden.</br>Du måste först boka av tandemet.</p>
  </div>
  <div style="text-align: center">
    <span"><input class='button' type='button' name='illegal_time_ok' id='illegal_time_ok' value='Ok' onclick="revert_times_f();"></span>
  </div>
</div>

<!-- ---------------- -->
<!-- Instructions     -->
<!-- ---------------- -->

<div id='info' class='info'>
  <p>Här lägger fotografer in de tider som de vill filma. Välj först datum genom att klicka i kalendern, välj sedan fotograf. Välj därefter start- och sluttid för den dagen.</p>
  <p>Systemet tillåter inte att man tar bort en post där tandem finns inbokade under tidsperioden. Vill man det så måste man kontakta tandembokaren så att en ny fotograf kan bokas in.</p>
</div>


<?php
  // Return to Concrete5 database session
  $db = Loader::db(null, null, null, null, true);
?>
