
<!-- Html Tooltips -->
<script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/tooltip/wz_tooltip.js"></script> 



<!-- ======================================================= -->
<!-- Special handling if called with argument from 'schema'  -->
<!-- ======================================================= -->

<?php
 if ($_GET['rebook_id']) {
   $rebook_id = $_GET['rebook_id'];
   echo("<script> var rebook_id = ". $rebook_id ."</script>");
 }
?>


<h1>Ny bokning</h1>

<!-- ================ -->
<!-- Tabs             -->
<!-- ================ -->

<div id='tab_step_1' class='tab tab_active'>   <div><b>Step 1</b></div><div class='tab_info'><i>Datum &amp; tid</i></div></div>
<div id='tab_step_2' class='tab tab_inactive'> <div><b>Step 2</b></div><div class='tab_info'><i>Kontaktuppgifter</i></div></div>
<div id='tab_step_3' class='tab tab_inactive'> <div><b>Step 3</b></div><div class='tab_info'><i>Kvittens</i></div></div> 


<!-- ================ -->
<!-- Step 1.          -->
<!-- ================ -->

<div id='step_1'>
  
  <!-- Calendar -->
  <div id='s1_calendar_wrapper'>
    <p><b>1. Välj datum</b></p>
    <div id='calendar' class='cal_cont' >
      <!-- Populated by Ajax -->
    </div>
    <div id='calendar_info'>
      <table>
        <tr><td align='right' style='color: #00AA00;'>Grön:   </td><td>Lediga tider</td></tr>
      </table>
    </div>
  </div>
  
  <!-- Pilots -->
  <div id='s1_sel_pilot_wrapper'>
    <p><b>2. Välj tid</b></p>
    <div id='booked_pilots'>
      <!-- Populated by Ajax -->
    </div>
  </div>

</div>



<!-- ================ -->
<!-- Step 2.          -->
<!-- ================ -->

<!-- User info -->
<?php
  $u = new User();
  if ($u->isRegistered()) { 
    $userName = $u->getUserName();
  }
?>


<div id='step_2'>
 
  <!-- Header -->
  <div class='s2_form'>
    <h2>Ange uppgifter</h2>
  </div> 
        
  <!-- Info -->
  <div class='s2_form right'>
    <ul class='clean_list'>
      <li>    
        <span id='s2_info_date'></span>
      </li>    
      <li>    
        <span id='s2_info_time'></span>
      </li>    
      <li>    
        <span id='s2_info_pilot'></span>
      </li>    
    </ul>
  </div> 

  <!-- Separator-->
  <div class='s2_form wide'>
    <hr/>
  </div> 

  <!-- Payment etc -->
  <div id='s2_p1'>
    <div id='s2_p1_left' class='s2_form'>
      <ul class='clean_list'>
        <li>    
          <label class='c1'>Bokare:</label>
          <input class='c2' type='text' name='s2_bokare' id='s2_bokare' value='<?php echo($userName); ?>' />
        </li>
        <li>    
          <label class='c1'>Betalningssätt:</label>
          <select class='c2' name='s2_betalningssatt' id='s2_betalningssatt' tabindex='1'>
            <option value='Presentkort'>Presentkort</option>
            <option value='Postgiro'>Postgiro</option>
            <option value='På plats'>På plats</option>
          </select>
        </li>
        <li id='s2_pk_select'>    
          <label class='c1'>Presentkort nr:</label>
          <input class='c2_p required' type='text' name='s2_pk_nr' id='s2_pk_nr' tabindex='2' value='' />
          <span class='icon-share-alt icon-large icon-st-lightblue icon-st-shadow icon-st-click' onmouseover='Tip(s2_pk_fyll)'  onmouseout='UnTip()' onclick="check_pk_f($('#s2_pk_nr').val());" ></span>
          <span class='icon-list-alt  icon-large icon-st-lightblue icon-st-shadow icon-st-click' onmouseover='Tip(s2_pk_lista)' onmouseout='UnTip()' onclick="ajax_f('get_all_pk', ''); window.scroll(0,0); $('body').css('overflow', 'hidden');"></span>
        </li>    
        <li id='s2_pk_valid' style='display: none;'>
          <label class='c1'>Giltigt till:</label>
          <div class='c2' id='s2_giltigt_tom'>-</div>
        </li>
        <li>
          <label class='c1'>Betalat:</label>
          <div class='c2' id='s2_betalat'>-</div>
        </li>
      </ul>
    </div>
    
    <!-- Photo, misc -->
    <div id='s2_p1_right' class='s2_form'>
      <ul class='clean_list'>
        <li>    
          <label class='c1 pk_disable'>Video:</label> 
          <input class='c2 pk_disable' type='checkbox' name='s2_video' id='s2_video' onclick="ajax_f('get_photo_schedule', '&date='+$('#s2_form_date').val());" value=''/>
        </li>
        <li>    
          <label class='c1 pk_disable'>Foto:</label> 
          <input class='c2 pk_disable' type='checkbox' name='s2_foto' id='s2_foto' onclick="ajax_f('get_photo_schedule', '&date='+$('#s2_form_date').val());" value=''/>
        </li>
        <li id='s2_foto_sel' style='display: none;'>    
          <label class='c1'>Fotograf:</label> 
          <select class='c2' name='s2_foto_dropdown' id='s2_foto_dropdown'>
            <option>Välj video/foto</option>
            <!-- Populated by Ajax -->
          </select>
          <span class='icon-info-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onmouseover='Tip(s2_fotoschema)' onmouseout='UnTip()' onclick="$('#overlay').show(); $('#s2_ph_sched_popup').popShow(); $('#s2_ph_sched_popup').popCenter();"></span>
        </li>
        <li>
          <label class='c1' onmouseover='Tip(ovrigt)' onmouseout='UnTip()'>Övrigt:</label>
          <input class='c2' type='text' name='s2_b_ovrigt' id='s2_b_ovrigt' value=''/>
        </li>
      </ul>
    </div>
  </div>


  <!-- Separator-->
  <div class='s2_form wide cr'>
    <hr/>
  </div> 

  <!-- Use contact -->
  <div id='s2_p2'>

    <label class='c1 pk_disable'>Använd kontakt:</label>
    <input class='c2 pk_disable' type='checkbox' name='s2_use_contact' id='s2_use_contact' />

    <!-- Pax -->
    <div class='s2_form cr' id='s2_pax' >
      <h3>Pax</h3>
      <ul class='clean_list'>
        <li>
          <label class='c1'>Förnamn:</label>
          <input class='c2 required' type='text' tabindex='3' name='s2_pax_fornamn'        id='s2_pax_fornamn' value=''/>
        </li>
        <li>
          <label class='c1'>Efternamn:</label>
          <input class='c2 required' type='text' tabindex='4' name='s2_pax_efternamn'      id='s2_pax_efternamn' value=''/>
        </li>
        <li>
          <label class='c1'>Adress 1:</label>
          <input class='c2' type='text' tabindex='5' name='s2_pax_adress_1'       id='s2_pax_adress_1' value=''/>
        </li>
        <li>
          <label class='c1'>Adress 2:</label>
          <input class='c2' type='text' tabindex='6' name='s2_pax_adress_2'       id='s2_pax_adress_2' value=''/>
        </li>
        <li>
          <label class='c1'>P-nr / ort:</label>
          <div class='c2_d'>
            <input class='c2_p' type='text' tabindex='7' name='s2_pax_postnummer'     id='s2_pax_postnummer' value=''/>
            <input class='c2_o required' type='text' tabindex='8' name='s2_pax_ort'   id='s2_pax_ort'        value=''/>
          </div>
        </li>
        <li>
          <label class='c1'>Telefon:</label>
          <input class='c2 required' type='text' tabindex='9' name='s2_pax_telefon' id='s2_pax_telefon' value=''/>
        </li>
        <li>
          <label class='c1'>Email:</label>
          <input class='c2' type='text' tabindex='10' name='s2_pax_email'           id='s2_pax_email' value=''/>
        </li>
        <li>
          <label class='c1'>Längd:</label>
          <div class='c2_d'>
            <input class='c2_p required' type='text' tabindex='11'  name='s2_pax_langd'    id='s2_pax_langd' value=''/>
            <div class='left'> cm </div>
            <div class='left' id='s2_info_pilot_length'></div>
          </div>
        </li>
        <li>
          <label class='c1'>Vikt:</label>
          <div class='c2_d'>
            <input class='c2_p required' type='text' tabindex='12' name='s2_pax_vikt'      id='s2_pax_vikt' value=''/>
            <div class='left'> kg </div>
            <div class='left' id='s2_info_pilot_weight'></div>
          </div>
        </li>
        <li>
          <label class='c1'>Pax övrigt:</label>
          <input class='c2'     type='text' tabindex='21' name='s2_ovrigt'        id='s2_ovrigt' value=''/>
        </li>
      </ul>
    </div>

    <!-- Kontakt -->
    <div class='s2_form disabled' id='s2_kontakt'>
      <h3>Kontakt</h3>
      <ul class='clean_list'>
        <li>
          <label class='c1'>Förnamn:</label>
          <input class='c2 required' type='text' tabindex='13' name='s2_kontakt_fornamn'    id='s2_kontakt_fornamn' value='' disabled/>
        </li>
        <li>
          <label class='c1'>Efternamn:</label>
          <input class='c2 required' type='text' tabindex='14' name='s2_kontakt_efternamn'  id='s2_kontakt_efternamn' value='' disabled/>
        </li>
        <li>
          <label class='c1'>Adress 1:</label>
          <input class='c2' type='text' tabindex='15' name='s2_kontakt_adress_1'   id='s2_kontakt_adress_1' value='' disabled/>
        </li>
        <li>
          <label class='c1'>Adress 2:</label>
          <input class='c2' type='text' tabindex='16' name='s2_kontakt_adress_2'   id='s2_kontakt_adress_2' value='' disabled/>
        </li>
        <li>
          <label class='c1'>P-nr / ort:</label>
          <div class='c2_d'>
            <input class='c2_p' type='text' tabindex='17' name='s2_kontakt_postnummer' id='s2_kontakt_postnummer' value='' disabled/>
            <input class='c2_o' type='text' tabindex='18' name='s2_kontakt_ort'        id='s2_kontakt_ort' value='' disabled/>
          </div>
        </li>
        <li>
          <label class='c1'>Telefon:</label>
          <input class='c2 required' type='text' tabindex='19' name='s2_kontakt_telefon'    id='s2_kontakt_telefon' value='' disabled/>
        </li>
        <li>
          <label class='c1'>Email:</label>
          <input class='c2' type='text' tabindex='20' name='s2_kontakt_email'      id='s2_kontakt_email' value='' disabled/>
        </li>
      </ul>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class='s2_form cr wide'>
    <hr/>
    <ul class='clean_list'>
      <li class='center' id='s2_submit_li'>
        <input class='button' type='submit' name='s2_new_time' id='s2_new_time' value=' &larr; Byt tid' onclick="sel_step_f('1')" />
        <input class='button' type='button' name='s2_abort'    id='s2_abort'    value='Avbryt'          onclick="window.location = ''" />
        <input class='button' type='submit' name='s2_submit'   id='s2_submit'   value='Boka &rarr;'     onclick="s2_submit_f('book')" />
      </li>
      <li class='center' id='s2_change_li' style='display: none;'>
        <input class='button' type='submit' name='s2_change_submit' id='s2_pk_change_submit' value='Uppdatera' onmouseover='Tip(update)'  onmouseout='UnTip()' onclick="s2_submit_f('update')" />
        <input class='button' type='button' name='s2_change_abort'  id='s2_pk_change_abort'  value='Ångra'     onmouseover='Tip(discard)' onmouseout='UnTip()' onclick="s2_change_abort_f();" />
      </li>
    </ul>
  </div>

  <input type='hidden' name='s2_form_pilot_id'     id='s2_form_pilot_id'     value='' />
  <input type='hidden' name='s2_form_pilot_weight' id='s2_form_pilot_weight' value='' />
  <input type='hidden' name='s2_form_pilot_length' id='s2_form_pilot_length' value='' />
  <input type='hidden' name='s2_form_time_id'      id='s2_form_time_id'      value='' />
  <input type='hidden' name='s2_form_date'         id='s2_form_date'         value='' />
  <input type='hidden' name='s2_form_time'         id='s2_form_time'         value='' />

</div>


<!-- Hidden form. When changes are made to pk details in this form will be filled by javascript-->
<div id='s2_update_form' style='display: none;'>
  <form action='' method='post' id='s2_form_change' name='s2_form_change' enctype='multipart/form-data' onsubmit="return false">
    <div id='s2_update_form_sub'></div>
  </form>
</div>






<!-- ================ -->
<!-- Step 3.          -->
<!-- ================ -->

<div id='step_3'>
  
  <div style='float: left; padding: 0 40px 40px 40px;'>
    <table>
      <tr><td colspan='2'><h2>Bokningen är genomförd</h2></td></tr>
      <tr><td width='100px' >&nbsp;       </td><td>&nbsp;        </td></tr>
      <tr><td>Datum:       </td><td id='s3_date'> </td></tr>
      <tr><td>Tid:         </td><td id='s3_time'> </td></tr>
      <tr><td>Tandempilot: </td><td id='s3_pilot'></td></tr>
      <tr><td>Fotograf:    </td><td id='s3_photo'></td></tr>
    </table>
  </div>
  
</div>



<!-- ================ -->
<!-- Popups           -->
<!-- ================ -->

<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>


<!-- Pax already booked -->
<div id='s2_rebook_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#s2_ph_sched_popup, #overlay').popHide(); window.scroll(0,0); $('body').css('overflowY', 'auto');"></span>
  </div>
  <ul class='clean_list'>
    <li>
      <p class='popup_h1'>Redan bokad</p>
      <p>Paxet är redan bokat på en annan tid. Om du väljer att fortsätta <br/>kommer den tiden att avbokas och den här tiden bokas in istället.</p>
    </li>
    <li class='center'>
      <input type='hidden' id='s2_rebook_id' />
      <input class='button' style='width: 80px;' type='submit' name='s2_rebook_continue' id='s2_rebook_continue' value='Fortsätt' onclick="check_cont_f(1);" />
      <input class='button' style='width: 80px;' type='button' name='s2_rebook_abort'    id='s2_rebook_abort'    value='Avbryt'   onclick="check_cont_f(0);" />
    </li>
  </ul>
</div>

<!-- Photo schedule -->
<div id='s2_ph_sched_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#s2_ph_sched_popup, #overlay').popHide(); window.scroll(0,0); $('body').css('overflowY', 'auto');"></span>
  </div>
  <div class='left'>
    <p class='popup_h1'>Fotografschema</p>
  </div>
  <div id='photo_schedule' style='clear: both; margin-top: 40px;'>
    <!-- Content will be filled by Ajax -->
  </div>
</div>




<!-- Presentkort - The big list -->
<div id='s2_pk_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#s2_pk_popup, #overlay').popHide(); window.scroll(0,0); $('body').css('overflowY', 'auto');"></span>
  </div>
  <div id='s2_pk_wrapper'>
    <div style='float: left;'>
      <p class='popup_h1'>Presentkort</p>
    </div>
    <div id='s2_pk_filter'>
      <fieldset>
        <legend>Filtrera:</legend>
        <select name='pk_filter_sel' id='pk_filter_sel'>
          <option value='Namn'      >Namn      </option>
          <option value='Telefon'   >Telefon   </option>
          <option value='Email'     >Email     </option>
          <option value='PK-nummer' >PK-nummer </option>
        </select>
        <input type='text' style='width: 200px;' name='pk_filter_inp' id='pk_filter_inp' value=''/>
      </fieldset>
    </div>
    <div id='s2_pk_content'>
      <table class='table_list nowrap' id='s2_pk_table'>
        <thead>
          <tr>
            <th colspan='6' align='center' >Presentkort</th>
            <th class='space'>                         </th>
            <th colspan='5' align='center' >Pax        </th>
            <th class='space'>                         </th>
            <th colspan='3' align='center' >Kontakt    </th>
            <th class='space'>                         </th>
            <th colspan='1' align='center' >-          </th>
          </tr>
          <tr>
            <th >Nr           </th>
            <th >Bokad        </th>
            <th >Giltigt till </th>
            <th >Betalat      </th>
            <th >Video        </th>
            <th >Foto         </th>
            <th class='space'></th>
            <th >Namn         </th>
            <th >Telefon      </th> 
            <th >Email        </th> 
            <th >Längd        </th>
            <th >Vikt         </th>
            <th class='space'></th>
            <th >Namn         </th>
            <th >Telefon      </th> 
            <th >Email        </th> 
            <th class='space'></th>
            <th>Övrigt       </th>
          </tr>
        </thead>
        <tbody id='s2_pk_list'>
          <!-- Content will be filled by Ajax -->
        </tbody>
      </table>
    </div>
  </div>
</div>

