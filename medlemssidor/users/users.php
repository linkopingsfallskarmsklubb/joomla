<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

  <!-- Html Tooltips -->
  <script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/tooltip/wz_tooltip.js"></script> 

<h1>Användare</h1>

<!-- Filter list -->
<div id='filter'>
  <fieldset id='filter_field'>
    <legend>Filtrera:</legend>
    <select name='filter_sel' id='filter_sel'>
      <option value='name'    >Namn        </option>
      <option value='username'>Användarnamn</option>
      <option value='group'   >Grupp       </option>
    </select>
    <input type='text' style='width: 200px;' name='filter_inp' id='filter_inp' value=''/>
  </fieldset>
</div>

<!-- User listing -->
<div id='list'>
  <table id='table' class="table_list tablesorter nowrap">
    <thead>
      <tr>
        <th><span  data-icon="&#x21dd;"></span>Ändra</th>
        <th>Användarnamn</th>
        <th>Namn</th>
        <th>Senaste inlogg.</th>
        <th>Inloggningar</th>
        <th>Aktiv</th>
        <th>Grupper</th>
      </tr>
    </thead>
    <tbody>
      <!-- Content added by AJAX -->
    </tbody>
  </table>
</div>


<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>

<!-- Details popup -->
<div id='details_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#details_popup, #overlay').popHide();"></span>
  </div>
  <!-- Details -->
  <div id='div_details'>
    <ul class='clean_list'>
      <!-- Heading -->
      <li>
        <p class='popup_h1'>Detaljer</p>
      </li>
      <!-- Användarnamn -->
      <li>
        <div class='c1'>
          <label>Användarnamn:</label>
        </div>
        <div class='c2'>
          <span id='det_username'></span>
        </div>
      </li>
      <!-- Namn -->
      <li>
        <div class='c1'>
          <label>Namn:</label>
        </div>
        <div class='c2'>
          <span id='det_name'></span>
        </div>
      </li>
      <!-- Certnummer -->
      <li>
        <div class='c1'>
          <label>Certnr:</label>
        </div>
        <div class='c2'>
          <span id='det_sff_nr'></span>
        </div>
      </li>
      <!-- Registrerad -->
      <li>
        <div class='c1'>
          <label>Registrerad:</label>
        </div>
        <div class='c2'>
          <span id='det_registered'></span>
        </div>
      </li>
      <!-- Senaste inloggningen -->
      <li>
        <div class='c1'>
          <label>Senaste inlogning:</label>
        </div>
        <div class='c2'>
          <span id='det_last_login'></span>
        </div>
      </li>
      <!-- Senaste online -->
      <li>
        <div class='c1'>
          <label>Senast online:</label>
        </div>
        <div class='c2'>
          <span id='det_last_online'></span>
        </div>
      </li>
      <!-- Antal inloggningar -->
      <li>
        <div class='c1'>
          <label>Antal inlogningar:</label>
        </div>
        <div class='c2'>
          <span id='det_nr_logins'></span>
        </div>
      </li>
    </ul>
    <!-- Concrete ID -->
    <input type='hidden' id='conc_id' name='con_id' value='' />
  </div>
      
  <!-- Misc -->
  <div id='div_misc'>
    <ul class='clean_list'>
      <!-- Heading -->
      <li>
        <p class='popup_h1'>Misc</p>
      </li>
      <!-- Aktiv -->
      <li>
        <div class='c1'>
          <label>Aktiv:</label>
        </div>
        <div class='c2'>
          <input type='checkbox' tabindex='20' name='det_active' id='det_active' value=''/>
        </div>
      </li>
    </ul>
  </div>

  <!-- Groups -->
  <div id='div_groups'>
    <ul class='clean_list' id='ul_groups'>
      <!-- Content added by AJAX -->
    </ul>
  </div>
  
  <!-- Submit -->
  <div id='div_submit'>
    <ul class='clean_list'>
      <li style='width: 100%'>
        <input id='submit' type='button' class='button' style='width: 100px;' value='Uppdatera' onclick="submit_f();" />
        <input id='delete' type='button' class='button' style='width: 100px;' value='Ta bort'   onclick="delete_confirm_f($('#det_username').html(), $('#det_name').html());" />
        <input id='abort'  type='button' class='button' style='width: 100px;' value='Avbryt'    onclick="$('#details_popup, #overlay').popHide();" />
      </li>
    </ul>
  </div>
    
</div>



<!-- Delete popup -->
<div id='delete_popup' class='popup'>
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#delete_popup, #overlay').popHide();"></span>
  </div>
  <div>
    <ul class='clean_list'>
      <li style='width: 100%'>
        <p class='popup_h2'>Är du säker på att du vill ta bort:</p>
        <p id='del_username'></p>
        <p id='del_name'></p>
      </li>
      <li style='width: 100%'>
        <input id='delete_ok'    type='button' class='button' style='width: 100px;' value='Ok'     onclick="delete_f();" />
        <input id='delete_abort' type='button' class='button' style='width: 100px;' value='Avbryt' onclick="$('#delete_popup, #overlay').popHide();" />
      </li>
    </ul>
  </div>
</div>
  
