<h1>Tandempiloter & Fotografer</h1>

    <!-- Tandempiloter -->
    <div id='pilots'>
      <div>
        <h5>Registrerade tandempiloter</h5>
      </div>
      <div>
        <table class='table_list' id='p_table'>
          <thead>
            <tr>
              <th>Ändra       </th>
              <th>Aktiv       </th>
              <th>Namn        </th>
              <th>Max längd   </th>
              <th>Max vikt    </th>
              <th>Tid mellan  </th>
            </tr>
          </thead>
          <tbody>
            <!-- Content added by Ajax -->
          </tbody>
        </table>
      </div>
      <div class='new_button'>
        <button class='button' href='#' onclick="new_pilot_f();">Lägg till ny</button>
      </div>
    </div>

    <!-- Fotografer -->
    <div id='photographers'>
      <div>
        <h5>Registrerade fotografer</h5>
      </div>
      <div>
        <table class='table_list' id='f_table'>
          <thead>
            <tr>
              <th>Ändra    </th>
              <th>Aktiv    </th>
              <th>Namn     </th>
              <th>Video    </th>
              <th>Foto     </th>
            </tr>
          </thead>
          <tbody>
            <!-- Content added by Ajax -->
          </tbody>
        </table>
      </div>
      <div class='new_button'>
        <button class='button' href='#' onclick="new_photo_f();">Lägg till ny</button>
      </div>
    </div>


  <!-- ------------------------- -->
  <!-- Popups -->
  <!-- ------------------------- -->
  
  <!-- Grey background when popup is visible -->
  <div id='overlay' class='overlay'></div>


  <!-- New pilot -->
  <div id='new_pilot' class='popup'>
    <p class='popup_h1'>Lägg till ny tandempilot</p>
    <div class='close'>
      <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#new_pilot, #overlay').popHide();"></span>
    </div>
    <table align='center'>
      <tr><td>Certnummer:    </td> <td><input type='text' style='width: 80px;'  name='new_pilot_certnr'     id='new_pilot_certnr'     value='' /></td></tr>
      <tr><td>Förnamn:       </td> <td><input type='text' style='width: 150px;' name='new_pilot_fornamn'    id='new_pilot_fornamn'    value='' /></td></tr>
      <tr><td>Efternamn:     </td> <td><input type='text' style='width: 150px;' name='new_pilot_efternamn'  id='new_pilot_efternamn'  value='' /></td></tr>
      <tr><td>Maxlängd:      </td> <td><input type='text' style='width: 80px;'  name='new_pilot_maxlangd'   id='new_pilot_maxlangd'   value='' /></td></tr>
      <tr><td>Maxvikt:       </td> <td><input type='text' style='width: 80px;'  name='new_pilot_maxvikt'    id='new_pilot_maxvikt'    value='' /></td></tr>
      <tr><td>Tid mellan hopp:</td>
          <td>
            <select style='width: 80px;' name='new_pilot_tid_mellan' id='new_pilot_tid_mellan'>
              <option value='30'  >30 min  </option>
              <option value='60'  >60 min  </option>
              <option value='90'  >90 min  </option>
              <option value='120' >120 min </option>
            </select>
          </td>
      </tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan='2' align='center'>
          <input class='button' type='submit' name='new_pilot_submit' id='new_pilot_submit' value='Lägg till' onclick="new_pilot_submit_f();">
          <input class='button' type='button' name='new_pilot_avbryt' id='new_pilot_avbryt' value='Avbryt' onclick="$('#new_pilot, #overlay').popHide();">
        </td>
      </tr>
    </table>
  </div>


  <!-- New photographer -->
  <div id='new_photo' class='popup'>
    <p class='popup_h1'>Lägg till ny fotograf</p>
    <div class='close'>
      <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#new_photo, #overlay').popHide();"></span>
    </div>
    <table align='center'>
      <tr><td>Certnummer: </td> <td><input type='text'     style='width: 70px;'  name='new_photo_certnr'     id='new_photo_certnr'    value='' /></td></tr>
      <tr><td>Förnamn:    </td> <td><input type='text'     style='width: 150px;' name='new_photo_fornamn'    id='new_photo_fornamn'   value='' /></td></tr>
      <tr><td>Efternamn:  </td> <td><input type='text'     style='width: 150px;' name='new_photo_efternamn'  id='new_photo_efternamn' value='' /></td></tr>
      <tr><td>Foto:       </td> <td><input type='checkbox'                       name='new_photo_foto'       id='new_photo_foto'               /></td></tr>
      <tr><td>Video:      </td> <td><input type='checkbox'                       name='new_photo_video'      id='new_photo_video'              /></td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan='2' align='center'>
          <input class='button' type='submit' name='new_photo_submit' id='new_photo_submit' value='Lägg till' onclick="new_photo_submit_f();">
          <input class='button' type='button' name='new_photo_abort'  id='new_photo_abort'  value='Avbryt'    onclick="$('#new_photo, #overlay').popHide();">
        </td>
      </tr>
    </table>
  </div>


  <!-- Edit pilot -->
  <div id='edit_pilot' class='popup'>
    <p class='popup_h1'>Ändra tandempilot</p>
    <div class='close'>
      <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#edit_pilot, #overlay').popHide();"></span>
    </div>
    <table align='center'>
      <tr><td>Certnummer:     </td> <td><input type='text' style='width: 80px;'  name='ed_pilot_certnr'      id='ed_pilot_certnr'      value='' /></td></tr>
      <tr><td>Förnamn:        </td> <td><input type='text' style='width: 150px;' name='ed_pilot_fornamn'     id='ed_pilot_fornamn'     value='' /></td></tr>
      <tr><td>Efternamn:      </td> <td><input type='text' style='width: 150px;' name='ed_pilot_efternamn'   id='ed_pilot_efternamn'   value='' /></td></tr>
      <tr><td>Maxlängd:       </td> <td><input type='text' style='width: 80px;'  name='ed_pilot_maxlangd'    id='ed_pilot_maxlangd'    value='' /></td></tr>
      <tr><td>Maxvikt:        </td> <td><input type='text' style='width: 80px;'  name='ed_pilot_maxvikt'     id='ed_pilot_maxvikt'     value='' /></td></tr>
      <tr><td>Tid mellan hopp:</td>
          <td>
            <select class='dropdown' style='width: 80px;' name='ed_pilot_tid_mellan' id='ed_pilot_tid_mellan'>
              <option value='30'  >30 min  </option>
              <option value='60'  >60 min  </option>
              <option value='90'  >90 min  </option>
              <option value='120' >120 min </option>
            </select>
          </td>
      </tr>
      <tr><td>Aktiv:</td> <td><input type='checkbox' name='ed_pilot_aktiv' id='ed_pilot_aktiv' /></td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan='2' align='center'>
          <input class='button' type='submit' name='ed_pilot_submit' id='ed_pilot_submit' value='Uppdatera' onclick="edit_pilot_submit_f();">
          <input class='button' type='button' name='ed_pilot_abort'  id='ed_pilot_abort'  value='Avbryt'    onclick="$('#edit_pilot, #overlay').popHide();">
        </td>
      </tr>
    </table>
    <input type='hidden' name='ed_pilot_id' id='ed_pilot_id' value=''/>
  </div>


  <!-- Edit photographer -->
  <div id='edit_photo' class='popup'>
    <p class='popup_h1'>Ändra fotograf</p>
    <div class='close'>
      <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#edit_photo, #overlay').popHide();"></span>
    </div>
    <table align='center' border='0' width='350'>
      <tr><td>Certnummer: </td> <td><input type='text'     style='width: 70px;'  name='ed_photo_certnr'     id='ed_photo_certnr'      value='' /></td></tr>
      <tr><td>Förnamn:    </td> <td><input type='text'     style='width: 150px;' name='ed_photo_fornamn'    id='ed_photo_fornamn'     value='' /></td></tr>
      <tr><td>Efternamn:  </td> <td><input type='text'     style='width: 150px;' name='ed_photo_efternamn'  id='ed_photo_efternamn'   value='' /></td></tr>
      <tr><td>Video:      </td> <td><input type='checkbox'                       name='ed_photo_video'      id='ed_photo_video'                /></td></tr>
      <tr><td>Foto:       </td> <td><input type='checkbox'                       name='ed_photo_foto'       id='ed_photo_foto'                 /></td></tr>
      <tr><td>Aktiv:      </td> <td><input type='checkbox'                       name='ed_photo_aktiv'      id='ed_photo_aktiv'                /></td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan='2' align='center'>
          <input class='button' type='submit' name='ed_photo_submit' id='ed_photo_submit' value='Uppdatera' onclick="edit_photo_submit_f();">
          <input class='button' type='button' name='ed_photo_abort'  id='ed_photo_abort'  value='Avbryt'    onclick="$('#edit_photo, #overlay').popHide();">
        </td>
      </tr>
    </table>
    <input type='hidden' name='ed_photo_id' id='ed_photo_id' value=''/>
  </div>




