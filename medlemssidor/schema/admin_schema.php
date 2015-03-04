<html>
<head>
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../themes/lfk/main.css" />
  <link rel="stylesheet" type="text/css" href="../../includes/fonticons/css/font-awesome.css" />
<?php
define("DIR_REL", '/marten');
include("admin_schema_head.php");
?>
</head>
<body>

<h1>Administrera schema</h1>

<!-- Calendar -->
<div id='calendar_box'>
  <div id='calendar' class='cal_cont' >
  </div>
  <div id='calendar_info'>
    <table>
      <tr><td align='right' style='color: #00AA00;'>Grön:   </td><td>Schemalagda dagar</td></tr>
    </table>
  </div>
</div>


<div id='step_1'>
  <p>Börja genom att välja datum.</p>
</div>


<!-- Form -->
<div id='step_2' class='form_div'>
  <form autocomplete=off action='' name='new_day_form' id='new_day_form'>
    <ul class='clean_list' id='ul_1'>
      
      <!-- Date -->
      <li id='li_1'>
        <label style="float:left; width:100px;">Datum:</label>
        <input type='text' style='width: 80px;' name='datum' id='datum' value=''/>
      </li>

      <!-- Time start -->
      <li id='li_2'>
        <label style="float:left; width:100px;">Start:</label>
        <input type='text' class='time' tabindex='10' style='width: 40px;' name='tid_start' id='tid_start' value=''/>
      </li>

      <!-- Time stop -->
      <li id='li_3'>
        <label style="float:left; width:100px;">Slut:</label>
        <input type='text' class='time' tabindex='20' style='width: 40px;' name='tid_stop' id='tid_stop' value=''/>
      </li>
          
      <!-- Spacer -->
      <li id='li_4'>
        &nbsp;
      </li>

      <!-- Pilot -->
      <li id='li_5'>
        <div class='c1'>
          <label>Pilot:</label>
        </div>
        <div class='c2'>
          <div class='c21'>
            <input type='text' class='name'      name='pil_1'           id='pil_1'           tabindex='30' data-complete="pil_names" />
          </div>
          <div class='c22'>
            <input type='text' class='time'      name='pil_tid_start_1' id='pil_tid_start_1' tabindex='31' />
            <input type='text' class='time'      name='pil_tid_stop_1'  id='pil_tid_stop_1'  tabindex='32' />
            <input type='text' class='id pil_id' name='pil_id_1'        id='pil_id_1'                     />
            <span class='icon-plus  icon-st-lightblue icon-st-shadow icon-st-click' onClick="duplicate_f($(this).closest('li').attr('id'));"></span> 
            <span class='icon-minus icon-st-lightblue icon-st-shadow icon-st-click' onClick="remove_f($(this).closest('li').attr('id'));"  style='display: none;'></span> 
          </div>
        </div>
      </li>

      <!-- Hoppledare -->
      <li id='li_6'>
        <div class='c1'>
          <label style="float:left; width:100px;">Hoppledare:</label>
        </div>
        <div class='c2'>
          <div class='c21'>
            <input type='text' class='name'     name='hl_1'           id='hl_1'           tabindex='50' data-complete="hl_names" />
          </div>
          <div class='c22'>
            <input type='text' class='time'     name='hl_tid_start_1' id='hl_tid_start_1' tabindex='51' />
            <input type='text' class='time'     name='hl_tid_stop_1'  id='hl_tid_stop_1'  tabindex='52' />
            <input type='text' class='id hl_id' name='hl_id_1'        id='hl_id_1'        />
            <span class='icon-plus  icon-st-lightblue icon-st-shadow icon-st-click' onClick="duplicate_f($(this).closest('li').attr('id'));"></span> 
            <span class='icon-minus icon-st-lightblue icon-st-shadow icon-st-click' onClick="remove_f($(this).closest('li').attr('id'));"  style='display: none;'></span> 
          </div>
        </div>
      </li>

      <!-- Manifestor -->
      <li id='li_7'>
        <div class='c1'>
          <label style="float:left; width:100px;">Manifestor:</label>
        </div>
        <div class='c2'>
          <div class='c21'>
            <input type='text' class='name'      name='man_1'           id='man_1'            tabindex='70' data-complete="man_names" />
          </div>
          <div class='c22'>
            <input type='text' class='time'      name='man_tid_start_1' id='man_tid_start_1' tabindex='71' />
            <input type='text' class='time'      name='man_tid_stop_1'  id='man_tid_stop_1'  tabindex='72' />
            <input type='text' class='id man_id' name='man_id_1'        id='man_id_1'        />
            <span class='icon-plus  icon-st-lightblue icon-st-shadow icon-st-click' onClick="duplicate_f($(this).closest('li').attr('id'));"></span> 
            <span class='icon-minus icon-st-lightblue icon-st-shadow icon-st-click' onClick="remove_f($(this).closest('li').attr('id'));"  style='display: none;'></span> 
          </div>
        </div>
      </li>

      <!-- Hoppmästare -->
      <li id='li_8'>
        <div class='c1'>
          <label style="float:left; width:100px;">Hoppmästare:</label>
        </div>
        <div class='c2'>
          <div class='c21'>
            <input type='text' class='name'     name='hm_1'           id='hm_1'           tabindex='90' data-complete="hm_names" />
          </div>
          <div class='c22'>
            <input type='text' class='time'     name='hm_tid_start_1' id='hm_tid_start_1' tabindex='91' />
            <input type='text' class='time'     name='hm_tid_stop_1'  id='hm_tid_stop_1'  tabindex='92' />
            <input type='text' class='id hm_id' name='hm_id_1'        id='hm_id_1'        />
            <span class='icon-plus  icon-st-lightblue icon-st-shadow icon-st-click' onClick="duplicate_f($(this).closest('li').attr('id'));"></span> 
            <span class='icon-minus icon-st-lightblue icon-st-shadow icon-st-click' onClick="remove_f($(this).closest('li').attr('id'));"  style='display: none;'></span> 
          </div>
        </div>
      </li>

      <!-- AFF -->
      <li id='li_9'>
        <div class='c1'>
          <label style="float:left; width:100px;">AFF:</label>
        </div>
        <div class='c2'>
          <div class='c21'>
            <input type='text' class='name'      name='aff_1'           id='aff_1'           tabindex='110' data-complete="aff_names" />
          </div>
          <div class='c22'>
            <input type='text' class='time'      name='aff_tid_start_1' id='aff_tid_start_1' tabindex='111' />
            <input type='text' class='time'      name='aff_tid_stop_1'  id='aff_tid_stop_1'  tabindex='112' />
            <input type='text' class='id aff_id' name='aff_id_1'        id='aff_id_1'        />
            <span class='icon-plus  icon-st-lightblue icon-st-shadow icon-st-click' onClick="duplicate_f($(this).closest('li').attr('id'));"></span> 
            <span class='icon-minus icon-st-lightblue icon-st-shadow icon-st-click' onClick="remove_f($(this).closest('li').attr('id'));"  style='display: none;'></span> 
          </div>
        </div>
      </li>
      
      <!-- Spacer -->
      <li id='li_10'>
        &nbsp;
      </li>
      
      <!-- Kommentar -->
      <li id='li_11'>
        <div class='c1'>
          <label>Kommentar:</label>
        </div>
        <div class='c2'>
          <div class='c21'>
            <input type='text' class='name' tabindex='130'  name='kommentar' id='kommentar' value=''/>
          </div>
          <div class='c22'>
            &nbsp;
          </div>
        </div>
      </li>
      
      <!-- Spacer -->
      <li id='li_12'>
        &nbsp;
      </li>
      
      <!-- Submit -->
      <li id='li_13'>
        <div class='c1'>
          <label>&nbsp;</label>
        </div>
        <div  class='c2'>
          <input id='submit' type='button' class='button' tabindex='140' value='Lägg till' onclick="submit_day_f(this.form.id)" />
          <input id='remove' type='button' class='button' tabindex='141' value='Ta bort dag' onclick="remove_day_confirm_f($('#datum').val());" />
        </div>
      </li>

      <!-- Hidden forms (nr of each type)-->
      <li id='li_14'>
        <input type='hidden' name='nrof_pil' id='nrof_pil' value=''/>
        <input type='hidden' name='nrof_hl'  id='nrof_hl'  value=''/>
        <input type='hidden' name='nrof_hm'  id='nrof_hm'  value=''/>
        <input type='hidden' name='nrof_aff' id='nrof_aff' value=''/>
        <input type='hidden' name='nrof_man' id='nrof_man' value=''/>
      </li>
      
    </ul>
  </form>
</div>


<div id='step_3'>
  <h2>Klart</h2>
  <p>Fortsätt genom att välja ett nytt datum.</p>
</div>



<!-- -------------------------- -->
<!-- Popups                     -->
<!-- -------------------------- -->

<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>

<!-- Delete popup -->
<div id='delete_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#delete_popup, #overlay').popHide();"></span>
  </div>
  <div>
    <ul class='clean_list'>
      <li style='width: 100%'>
        <p class='popup_h2'>Är du säker på att du vill ta bort:</p>
        <p id='del_date'></p>
      </li>
      <li style='width: 100%'>
        <input id='delete_ok'    type='button' class='button' style='width: 100px;' value='Ok'     onclick="remove_day_f($('#del_date').html());" />
        <input id='delete_abort' type='button' class='button' style='width: 100px;' value='Avbryt' onclick="$('#delete_popup, #overlay').popHide();" />
      </li>
    </ul>
  </div>
</div>

<script src="admin_schema_scripts.js"></script>
</body>
</html>
