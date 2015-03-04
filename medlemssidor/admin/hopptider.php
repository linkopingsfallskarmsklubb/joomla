 
   <h1>Konfigurera hopptider</h1>

    <!-- Form -->
    <div id='jump_hours' class='form_div'>
      <form autocomplete=off action='' name='form_jump_hours' id='form_jump_hours'>
        <ul class='clean_list'>

          <!-- Header -->
          <li>
              <div class='c1'>
                <label>&nbsp;</label>
              </div>
              <div class='c2 time'>
                <label>Start</label>
                <label>Stop</label>
              </div>
          </li>

          <!-- Monday -->
          <li>
              <div class='c1'>
                <label>Måndag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_1' id='tid_start_1' tabindex='1' />
                <input type='text' class='time' name='tid_stop_1'  id='tid_stop_1'  tabindex='2' />
              </div>
          </li>

          <!-- Tuesday -->
          <li>
              <div class='c1'>
                <label>Tisdag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_2' id='tid_start_2' tabindex='3' />
                <input type='text' class='time' name='tid_stop_2'  id='tid_stop_2'  tabindex='4' />
              </div>
          </li>

          <!-- Wednesday -->
          <li>
              <div class='c1'>
                <label>Onsdag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_3' id='tid_start_3' tabindex='5' />
                <input type='text' class='time' name='tid_stop_3'  id='tid_stop_3'  tabindex='6' />
              </div>
          </li>

          <!-- Thursday -->
          <li>
              <div class='c1'>
                <label>Torsdag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_4' id='tid_start_4' tabindex='7' />
                <input type='text' class='time' name='tid_stop_4'  id='tid_stop_4'  tabindex='8' />
              </div>
          </li>

          <!-- Friday -->
          <li>
              <div class='c1'>
                <label>Fredag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_5' id='tid_start_5' tabindex='9' />
                <input type='text' class='time' name='tid_stop_5'  id='tid_stop_5'  tabindex='10' />
              </div>
          </li>

          <!-- Saturday -->
          <li>
              <div class='c1'>
                <label>Lördag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_6' id='tid_start_6' tabindex='11' />
                <input type='text' class='time' name='tid_stop_6'  id='tid_stop_6'  tabindex='12' />
              </div>
          </li>

          <!-- Sunday -->
          <li>
              <div class='c1'>
                <label>Söndag:</label>
              </div>
              <div class='c2 time'>
                <input type='text' class='time' name='tid_start_7' id='tid_start_7' tabindex='13' />
                <input type='text' class='time' name='tid_stop_7'  id='tid_stop_7'  tabindex='14' />
              </div>
          </li>

          <!-- Spacer -->
          <li>
            &nbsp;
          </li>

          <!-- Submit -->
          <li>
            <div class='c1'>
              <label>&nbsp;</label>
            </div>
            <div class='c2'>
              <input id='submit' type='button' class='button' tabindex='15' value='Uppdatera' onclick="submit_f(this.form.id)" />
            </div>
          </li>


        </ul>
      </form>
    </div>



    <!-- ================ -->
    <!-- Instructions     -->
    <!-- ================ -->

    <div id='info' class='info'>
      <p>Här kan man konfigurera hopptider för olika dagar. Tiderna kommer användas som defaultvärde när man lägger in nya hoppdagar etc.<br />Man kan alltid överrida dessa tider senare om man vill.</p>
    </div>
