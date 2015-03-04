
<h1>Administrera <?php echo $header ?></h1>


<!-- Main list -->
<div id='main_list'>
  <div>
    <table class='table_list' id='main_table'>
      <thead>
        <tr>
          <th>Namn</th>
          <th>Licensnr</th>
        </tr>
      </thead>
      <tbody id='main_tbody'>
        <!-- Content added by Ajax -->
      </tbody>
    </table>
  </div>
  <div class='new_button'>
    <button class='button' href='#' onclick="new_f();">Ändra</button>
    </div>
</div>


<!-- Grey background when popup is visible -->
<div id='overlay' class='overlay'></div>


<!-- New -->
<div id='new_popup' class='popup'>

  <p id='new_header' class='popup_h1'>Lägg till / Ta bort</p>
  
  <div class='close'>
    <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#new_popup, #overlay').popHide();"></span>
  </div>

  <!-- Filter -->
  <div id='filter'>
    <fieldset id='filter_field'>
      <legend>Filtrera:</legend>
      <ul class='clean_list'>
        <li>
          <label class='f1'>Förnyade sedan:</label>
          <select name='filter_year' id='filter_year'>
            <!-- Content added by ajax -->
          </select>
        </li>
        <li>
          <span>&nbsp;</span>
        </li>
        <li>
          <label class='f1'>Endast LFK:</label>
          <input class='f2' type='checkbox' name='filter_lfk' id='filter_lfk' checked/>
        </li>
        <li>
          <label class='f1'>Endast markerade:</label>
          <input class='f2' type='checkbox' name='filter_marked' id='filter_marked'/>
        </li>
      </ul>
    </fieldset>

    <div id='new_submit_div'>
      <input class='button' type='submit' name='new_submit' id='new_submit' value='Spara'  onclick="new_submit_f();" />
      <input class='button' type='button' name='new_abort'  id='new_abort'  value='Avbryt' onclick="$('#new_popup, #overlay').popHide();" />
    </div>

  </div>


  <!-- Full list -->
  <div id='full_list'>
    <table class='table_list nowrap tablesorter' id='new_table' align='center'>
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Förnamn</th>
          <th>Efternamn</th>
          <th>Licensnr</th>
          <th>Klubb</th>
        </tr>
      </thead>
      <tbody id='new_tbody'>
        <!-- Content added by ajax -->
      </tbody>
    </table>
  </div>
</div>

