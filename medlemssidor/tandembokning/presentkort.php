
<h1>Presentkort</h1>

<!-- Filter list -->
<div id='filter'>
  <fieldset id='filter_field'>
    <legend>Filtrera:</legend>
    <ul class='clean_list'>
      <li>
        <select name='filter_sel' id='filter_sel'>
          <option value='p_name'  >Pax: namn    </option>
          <option value='p_emeail'>Pax: email   </option>
          <option value='p_phone' >Pax: telefon </option>
          <option value='k_name'  >Kontakt: namn    </option>
          <option value='k_email' >Kontakt: email   </option>
          <option value='k_phone' >Kontakt: telefon </option>
        </select>
        <input type='text' style='width: 200px;' name='filter_inp' id='filter_inp' value=''/>
      </li>
      <li>
        <span>&nbsp;</span>
      </li>
      <li>
        <label class='f1'>Dölj förbrukade:</label>
        <input class='f2' type='checkbox' name='filter_jumped' id='filter_jumped' checked onclick="filter_cb_f();"/>
      </li>
      <li>
        <label class='f1'>Dölj bokade:</label>
        <input class='f2' type='checkbox' name='filter_booked'  id='filter_booked' onclick="filter_cb_f();"/>
      </li>
      <li>
        <label class='f1'>Dölj utgångna:</label>
        <input class='f2' type='checkbox' name='filter_expired' id='filter_expired' checked onclick="filter_cb_f();"/>
      </li>
    </ul>
  </fieldset>
</div>




<!-- New button -->
<div style='float: right;'>
  <button align='right' class='button' href='#' onclick="new_form_f();">Lägg till ny</button>
</div>


<!-- Main list -->
<div id='table_container'>
  <span>Antal: </span><span id='hits'></span>
  <table id='pk_table' class='table_list tablesorter'>
    <thead>
      <tr>
        <th>Nr</th>
        <th>Hoppat</th>
        <th>Bokad</th>
        <th>Pax</th>
        <th>Längd</th>
        <th>Vikt</th>
        <th>Video</th>
        <th>Foto</th>
        <th>T.o.m.</th>
        <th>Övrigt</th>
      </tr>
    </thead>
      <tbody id='pk_table_body'>
        <!-- Content added by ajax -->
      </tbody>
  </table>
</div>




<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>


<!-- -------------------------------- -->
<!-- Details                          -->
<!-- -------------------------------- -->

<div id='details_popup' class='popup'>

  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#details_popup, #overlay').popHide();"></span>
  </div>
  
  <div class='popup_header'>
    <p class='popup_h1'>Detaljer</p>
  </div>
  
  <div class='popup_header_info'>
    <ul class='clean_list'>
      <li>
        <span class='c1 le1'>Presentkortnr:</span>
        <span class='c2 le3' id='det_pknr'></span>
      </li>
      <li>
        <span class='c1 le1'>Skapad:</span>
        <span class='c2 le3' id='det_tillagd'></span>
      </li>
      <li>
        <span class='c1 le1'>Modifierad:</span>
        <span class='c2 le3' id='det_modifierad'></span>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>
  
  <!-- Use contact -->
  <ul>
    <li>
      <label class='c1 le2'>Använd kontakt:</label>
      <span class='c2' name='det_use_contact' id='det_use_contact'></span>
    </li>
  </ul>
  <!-- Pax -->
  <div class='div_pax'>
    <ul class='clean_list'>
      <li>
        <h3>Pax</h3>
      </li>
      <li>
        <label class='c1'>Förnamn:</label>
        <span class='c2' id='det_pax_fornamn'></span>
      </li>
      <li>
        <label class='c1'>Efternamn:</label>
        <span class='c2' id='det_pax_efternamn'></span>
      </li>
      <li>
        <label class='c1'>Adress 1:</label>
        <span class='c2' id='det_pax_adress_1'></span>
      </li>
      <li>
        <label class='c1'>Adress 2:</label>
        <span class='c2' id='det_pax_adress_2'></span>
      </li>
      <li>
        <label class='c1'>P-nr / ort:</label>
        <div class='c2_wrapper'>
          <span class='c2 le2' id='det_pax_postnummer'></span>
          <span class='c2 le5' id='det_pax_ort'></span>
        </div>
      </li>
      <li>
        <label class='c1'>Telefon:</label>
        <span class='c2' id='det_pax_telefon'></span>
      </li>
      <li>
        <label class='c1'>Email:</label>
        <span class='c2' id='det_pax_email'></span>
      </li>
    </ul>
  </div>
      
  <!-- Kontakt -->
  <div class='div_kontakt'>
    <ul class='clean_list'>
      <li>
        <h3>Kontakt</h3>
      </li>
      <li>
        <label class='c1'>Förnamn:</label>
        <span class='c2' id='det_kontakt_fornamn'></span>
      </li>
      <li>
        <label class='c1'>Efternamn:</label>
        <span class='c2' id='det_kontakt_efternamn'></span>
      </li>
      <li>
        <label class='c1'>Adress 1:</label>
        <span class='c2' id='det_kontakt_adress_1'></span>
      </li>
      <li>
        <label class='c1'>Adress 2:</label>
        <span class='c2' id='det_kontakt_adress_2'></span>
      </li>
      <li>
        <label class='c1'>P-nr / ort:</label>
        <div class='c2_wrapper'>
          <span class='c2 le2' id='det_kontakt_postnummer'></span>
          <span class='c2 le5' id='det_kontakt_ort'></span>
        </div>
      </li>
      <li>
        <label class='c1'>Telefon:</label>
        <span class='c2' id='det_kontakt_telefon'></span>
      </li>
      <li>
        <label class='c1'>Email:</label>
        <span class='c2' id='det_kontakt_email'></span>
      </li>
    </ul>
  </div>

  <div class='divider'>
  </div>

  <div class='div_misc_1'>
    <ul class='clean_list'>
      <li>
        <label class='c1'>Längd:</label>
        <span class='c2 le1' id='det_pax_langd'></span><span>&nbsp;cm</span>
      </li>
      <li>
        <label class='c1'>Vikt:</label>
        <span class='c2 le1' id='det_pax_vikt'></span><span>&nbsp;kg</span>
      </li>
      <li>
        <label class='c1'>Video:</label>
        <span class='c2' id='det_video'></span>
      </li>
      <li>
        <label class='c1'>Foto:</label>
        <span class='c2' id='det_foto'></span>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>

  <div class='div_misc_2'>
    <ul class='clean_list'>
      <li>
        <label class='c1'>Hoppat:</label>
        <span class='c2' id='det_hoppat'></span>
      </li>
      <li>
        <label class='c1'>Betalat:</label>
        <span class='c2' id='det_betalat'></span><span>&nbsp;kr</span>
      </li>
      <li>
        <label class='c1'>Giltigt till:</label>
        <span class='c2' id='det_giltigt_till'></span>
      </li>
      <li>
        <label class='c1'>Övrigt:</label>
        <span class='c2' id='det_ovrigt'></span>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>

  <!-- Submit -->
  <div class='div_submit'>
    <ul class='clean_list'>
      <li style='width: 100%'>
        <input id='det_close'  name='det_close'  type='button' class='button' value='Stäng'  onclick="$('#details_popup, #overlay').popHide();" />
        <input id='det_edit'   name='det_edit'   type='button' class='button' value='Ändra'  onclick="edit_f();" />
        <input id='det_book'   name='det_book'   type='button' class='button' value='Boka'   onclick="book_f();" />
        <input id='det_delete' name='det_delete' type='button' class='button' value='Radera' onclick="delete_confirm_f();" />
      </li>
      </ul>
  </div>
  
</div>




<!-- -------------------------------- -->
<!--  Edit                            -->
<!-- -------------------------------- -->

<div id='edit_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#edit_popup, #overlay').popHide();"></span>
  </div>
  
  <div class='popup_header'>
    <p class='popup_h1'>Ändra</p>
  </div>
  
  <div class='popup_header_info'>
    <ul class='clean_list'>
      <li>
        <span class='c1 le1'>Presentkortnr:</span>
        <span class='c2 le3' id='edit_pknr'></span>
      </li>
      <li>
        <span class='c1 le1'>Skapad:</span>
        <span class='c2 le3' id='edit_tillagd'></span>
      </li>
      <li>
        <span class='c1 le1'>Modifierad:</span>
        <span class='c2 le3' id='edit_modifierad'></span>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>
  
  <!-- Use contact -->
  <label class='c1 le2'>Använd kontakt:</label>
  <input class='c2' type='checkbox' name='edit_use_contact' id='edit_use_contact' />

  <!-- Pax -->
  <div class='div_pax'>
    <ul class='clean_list'>
      <li>
        <h3>Pax</h3>
      </li>
      <li>
        <label class='c1'>Förnamn:</label>
        <input class='c2 required' type='text' name='edit_pax_fornamn' id='edit_pax_fornamn' value=''/>          
      </li>
      <li>
        <label class='c1'>Efternamn:</label>
        <input class='c2 required' type='text' name='edit_pax_efternamn' id='edit_pax_efternamn'/>
      </li>
      <li>
        <label class='c1'>Adress 1:</label>
        <input class='c2' type='text' name='edit_pax_adress_1' id='edit_pax_adress_1'/>
      </li>
      <li>
        <label class='c1'>Adress 2:</label>
        <input class='c2' type='text' name='edit_pax_adress_2' id='edit_pax_adress_2'/>
      </li>
      <li>
        <label class='c1'>P-nr / ort:</label>
        <div class='c2_wrapper'>
          <input class='c2 le2' type='text' class='pnr' name='edit_pax_postnummer' id='edit_pax_postnummer'/>
          <input class='c2 le5 required' type='text' class='ort' name='edit_pax_ort' id='edit_pax_ort'/>
        </div>
      </li>
      <li>
        <label class='c1'>Telefon:</label>
        <input class='c2 required' type='text' name='edit_pax_telefon' id='edit_pax_telefon'/>
      </li>
      <li>
        <label class='c1'>Email:</label>
        <input class='c2' type='text' name='edit_pax_email' id='edit_pax_email'/>
      </li>
    </ul>
  </div>
      
  <!-- Kontakt -->
  <div class='div_kontakt disabled'>
    <ul class='clean_list'>
      <li>
        <h3>Kontakt</h3>
      </li>
      <li>
        <label class='c1'>Förnamn:</label>
        <input class='c2 required' type='text' name='edit_kontakt_fornamn' id='edit_kontakt_fornamn' disabled/>
      </li>
      <li>
        <label class='c1'>Efternamn:</label>
        <input class='c2 required' type='text' name='edit_kontakt_efternamn' id='edit_kontakt_efternamn' disabled/>
      </li>
      <li>
        <label class='c1'>Adress 1:</label>
        <input class='c2' type='text' name='edit_kontakt_adress_1' id='edit_kontakt_adress_1' disabled/>
      </li>
      <li>
        <label class='c1'>Adress 2:</label>
        <input class='c2' type='text' name='edit_kontakt_adress_2' id='edit_kontakt_adress_2' disabled/>
      </li>
      <li>
        <label class='c1'>P-nr / ort:</label>
        <div class='c2_wrapper'>
          <input class='c2 le2' type='text' class='pnr' name='edit_kontakt_postnummer' id='edit_kontakt_postnummer' disabled/>
          <input class='c2 le5' type='text' class='ort' name='edit_kontakt_ort' id='edit_kontakt_ort' disabled/>
        </div>
      </li>
      <li>
        <label class='c1'>Telefon:</label>
        <input class='c2 required' type='text' name='edit_kontakt_telefon' id='edit_kontakt_telefon' disabled/>
      </li>
      <li>
        <label class='c1'>Email:</label>
        <input class='c2' type='text' name='edit_kontakt_email' id='edit_kontakt_email' disabled/>
      </li>
    </ul>
  </div>

  <div class='divider'>
  </div>

  <div class='div_misc_1'>
    <ul class='clean_list'>
      <li>
        <label class='c1'>Längd:</label>
        <input class='c2 le1 required' type='text' name='edit_pax_langd' id='edit_pax_langd'/><span>&nbsp;cm</span><span id='edit_pax_langd_error' class='error'></span>
      </li>
      <li>
        <label class='c1'>Vikt:</label>
        <input class='c2 le1 required' type='text' name='edit_pax_vikt' id='edit_pax_vikt'/><span>&nbsp;kg</span><span id='edit_pax_vikt_error' class='error'></span>
      </li>
      <li>
        <label class='c1'>Video:</label>
        <input class='c2' type='checkbox' name='edit_video' id='edit_video'/>
      </li>
      <li>
        <label class='c1'>Foto:</label>
        <input class='c2' type='checkbox' name='edit_foto' id='edit_foto'/>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>

  <div class='div_misc_2'>
    <ul class='clean_list'>
      <li>
        <label class='c1'>Hoppat:</label>
        <input class='c2' type='checkbox' name='edit_hoppat' id='edit_hoppat'/>
      </li>
      <li>
        <label class='c1'>Betalat:</label>
        <input class='c2 le3 required' type='text' name='edit_betalat' id='edit_betalat'/><span>&nbsp;kr</span>
      </li>
      <li>
        <label class='c1'>Giltigt till:</label>
        <input class='c2 le3 required' type='text' name='edit_giltigt_till' id='edit_giltigt_till'/>
      </li>
      <li>
        <label class='c1'>Övrigt:</label>
        <textarea class='c2' rows='2' name='edit_ovrigt' id='edit_ovrigt'></textarea>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>

  <!-- Submit -->
  <div class='div_submit'>
    <ul class='clean_list'>
      <li style='width: 100%'>
        <input class='button' type='submit' name='edit_submit' id='edit_submit' value='Spara'   onclick="edit_submit_f();"/>
        <input class='button' type='button' name='edit_abort'  id='edit_abort'  value='Avbryt'  onclick="edit_abort_f();"/>
      </li>
    </ul>
  </div>
  
  <!-- Error -->
  <div id='edit_error'>
  </div>

</div>



<!-- -------------------------------- -->
<!--  New                             -->
<!-- -------------------------------- -->

<div id='new_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#new_popup, #overlay').popHide();"></span>
  </div>
  
  <div class='popup_header'>
    <p class='popup_h1'>Nytt presentkort</p>
  </div>
  
  <!-- Use contact -->
  <div class='c1 le2'>
    <label>Använd kontakt:</label>
  </div>
  <div class='c2'>
    <input type='checkbox' name='new_use_contact' id='new_use_contact' />
  </div>

  <!-- Pax -->
  <div class='div_pax'>
    <ul class='clean_list'>
      <li>
        <h3>Pax</h3>
      </li>
      <li>
        <label class='c1' id='yada' for='new_pax_fornamn'>Förnamn:</label>
        <input class='c2 pax required' type='text' name='new_pax_fornamn' id='new_pax_fornamn' value=''/>          
      </li>
      <li>
        <label class='c1'>Efternamn:</label>
        <input class='c2 pax required' type='text' name='new_pax_efternamn' id='new_pax_efternamn'/>
      </li>
      <li>
        <label class='c1'>Adress 1:</label>
        <input class='c2 pax' type='text' name='new_pax_adress_1' id='new_pax_adress_1'/>
      </li>
      <li>
        <label class='c1'>Adress 2:</label>
        <input class='c2 pax' type='text' name='new_pax_adress_2' id='new_pax_adress_2'/>
      </li>
      <li>
        <label class='c1'>P-nr / ort:</label>
        <div class='c2_wrapper'>
          <input class='c2 le2 pax'          type='text' name='new_pax_postnummer' id='new_pax_postnummer'/>
          <input class='c2 le5 pax required' type='text' name='new_pax_ort'        id='new_pax_ort'/>
        </div>
      </li>
      <li>
        <label class='c1'>Telefon:</label>
        <input class='c2 pax required' type='text' name='new_pax_telefon' id='new_pax_telefon'/>
      </li>
      <li>
        <label class='c1'>Email:</label>
        <input class='c2 pax' type='text' name='new_pax_email' id='new_pax_email'/>
      </li>
    </ul>
  </div>
      
  <!-- Kontakt -->
  <div class='div_kontakt disabled'>
    <ul class='clean_list'>
      <li>
        <h3>Kontakt</h3>
      </li>
      <li>
        <label class='c1'>Förnamn:</label>
        <input class='c2 contact required' type='text' name='new_kontakt_fornamn' id='new_kontakt_fornamn' disabled/>
      </li>
      <li>
        <label class='c1'>Efternamn:</label>
        <input class='c2 contact required' type='text' name='new_kontakt_efternamn' id='new_kontakt_efternamn' disabled/>
      </li>
      <li>
        <label class='c1'>Adress 1:</label>
        <input class='c2 contact' type='text' name='new_kontakt_adress_1' id='new_kontakt_adress_1' disabled/>
      </li>
      <li>
        <label class='c1'>Adress 2:</label>
        <input class='c2 contact' type='text' name='new_kontakt_adress_2' id='new_kontakt_adress_2' disabled/>
      </li>
      <li>
        <label class='c1'>P-nr / ort:</label>
        <div class='c2_wrapper'>
          <input class='c2 le2 contact' type='text' name='new_kontakt_postnummer' id='new_kontakt_postnummer' disabled/>
          <input class='c2 le5 contact' type='text' name='new_kontakt_ort'        id='new_kontakt_ort' disabled/>
        </div>
      </li>
      <li>
        <label class='c1'>Telefon:</label>
        <input class='c2 contact required' type='text' name='new_kontakt_telefon' id='new_kontakt_telefon' disabled/>
      </li>
      <li>
        <label class='c1'>Email:</label>
        <input class='c2 contact' type='text' name='new_kontakt_email' id='new_kontakt_email' disabled/>
      </li>
    </ul>
  </div>

  <div class='divider'>
  </div>

  <div class='div_misc_1'>
    <ul class='clean_list'>
      <li>
        <label class='c1'>Längd:</label>
        <input class='c2 le1 required' type='text' name='new_pax_langd' id='new_pax_langd'/><span>&nbsp;cm</span><span id='new_pax_langd_error' class='error'></span>
      </li>
      <li>
        <label class='c1'>Vikt:</label>
        <input class='c2 le1 required' type='text' name='new_pax_vikt' id='new_pax_vikt'/><span>&nbsp;kg</span><span id='new_pax_vikt_error' class='error'></span>
      </li>
      <li>
        <label class='c1'>Video:</label>
        <input class='c2' type='checkbox' name='new_video' id='new_video'/>
      </li>
      <li>
        <label class='c1'>Foto:</label>
        <input class='c2' type='checkbox' name='new_foto' id='new_foto'/>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>

  <div class='div_misc_2'>
    <ul class='clean_list'>
      <li>
        <label class='c1'>Hoppat:</label>
        <input class='c2' type='checkbox' name='new_hoppat' id='new_hoppat'/>
      </li>
      <li>
        <label class='c1'>Betalat:</label>
        <input class='c2 le3 required' type='text' name='new_betalat' id='new_betalat'/><span>&nbsp;kr</span>
      </li>
      <li>
        <label class='c1'>Giltigt till:</label>
        <input class='c2 le3 required' type='text' name='new_giltigt_till' id='new_giltigt_till'/><span id='new_giltigt_till_error' class='error'></span>
      </li>
      <li>
        <label class='c1'>Övrigt:</label>
        <textarea class='c2' rows='2' name='new_ovrigt' id='new_ovrigt'></textarea>
      </li>
    </ul>
  </div>
  
  <div class='divider'>
  </div>

  <!-- Submit -->
  <div class='div_submit'>
    <ul class='clean_list'>
      <li style='width: 100%'>
        <input class='button' type='submit' name='new_submit' id='new_submit' value='Spara'   onclick="new_submit_f();"/>
        <input class='button' type='button' name='new_abort'  id='new_abort'  value='Avbryt'  onclick="new_abort_f();" />
      </li>
      </ul>
  </div>

  <!-- Error -->
  <div id='new_error'>
  </div>
  
</div>



<!-- -------------------------------- -->
<!--  Delete                          -->
<!-- -------------------------------- -->

<div id='delete_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#delete_popup, #overlay').popHide();"></span>
  </div>
  <p class='popup_h1'>Radera presentkort</p>
  <table align='center' border='0'>
    <tr><td colspan='2'>Är du säker på att du vill radera:</td></tr>
    <tr><td>Nr:</td><td id='del_pknr'></td></tr>
    <tr><td>Pax:</td><td id='del_pk_namn'></td></tr>
    <tr><td>&nbsp;</td></tr>
  </table>
  <table align='center' border='0'>
    <tr>
      <td><input class='button' type='submit' name='submit' id='del_pk'       value='Radera' onclick="delete_f($('#del_pknr').html());"/></td>
      <td><input class='button' type='button' name='abort'  id='del_pk_abort' value='Avbryt' onclick="$('#delete_popup, #overlay').popHide();"/></td>
    </tr>
  </table>
  <input type='hidden' name='delete_pilot_id' id='delete_pilot_id'/>
</div>


