//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


//************************************************************************************
// Global variables
//************************************************************************************

var g_change_hash_org = {};
var g_change_hash     = {};
var g_contact_hash    = {};



//************************************************************************************
// AJAX
//************************************************************************************

  // Add ajax animation block
  $(document).ready(function() {
    ajax_anim();
  });

  function ajax_f(action, qstr) {

    // Start loading animation
    $('#spinner').trigger('ajaxSend');

    // Ajax Request variable by browser
    ajax_request_f();

    // Create a function that will receive data sent from the server.
    // When data is received, call "draw_calendar_f()" and sent data as argument.
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {

        // Stop loading animation
        $('#spinner').trigger('ajaxStop');

        // Continue
        if (action == 'get_free_days')          { highlight_f(ajaxRequest.responseText);}          // Draw calendar with highlights
        if (action == 'get_free_times')         { list_free_times_f(ajaxRequest.responseText);}    // 
        if (action == 'get_all_pk')             { get_all_pk_f(ajaxRequest.responseText);}             // 
        if (action == 'get_photo_schedule')     { photo_schedule_f(ajaxRequest.responseText,$('#s2_form_time').val());}         // 
      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/tandembokning/ny_bokning_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'new_pax') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'get_pk') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'change_pax_pk') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'new_tandem_booking') {
      return(ajaxRequest.responseText);
    }

  }


  function ajax_mail_f() {

    // Ajax Request variable by browser
    ajax_request_f();

    // Send email
    ajaxRequest.open("GET", CCM_REL + "/tools/required/medlemssidor/tandembokning/ny_bokning_mail.php", false);
    ajaxRequest.send(null); 

  }






//************************************************************************************
// Common - Hide/show the different steps.
//************************************************************************************

  function sel_step_f(step) {
    if (step == '1') {
      $('#step_1').show();
      $('#step_2').hide();
      $('#step_3').hide();
      $('#tab_step_1').removeClass('tab_inactive').addClass('tab_active');
      $('#tab_step_2').removeClass('tab_active').addClass('tab_inactive');
      $('#tab_step_3').removeClass('tab_active').addClass('tab_inactive');
    } 
    else if (step == '2')  {
      $('#step_1').hide();
      $('#step_2').show();
      $('#step_3').hide();
      $('#tab_step_1').removeClass('tab_active').addClass('tab_inactive');
      $('#tab_step_2').removeClass('tab_inactive').addClass('tab_active');
      $('#tab_step_3').removeClass('tab_active').addClass('tab_inactive');
      // If called from Schema (rebook of tandem), get details.
      if ( typeof rebook_id !== 'undefined' && rebook_id ) {
        get_pk_f(rebook_id);
        //$('#s2_foto, #s2_video').trigger('click');
      }
      // Update photo schedule here as well. Needed if going from step 2 to step 1 and back..
      ajax_f('get_photo_schedule', '&date='+$('#s2_form_date').val());
    } 
    else if (step == '3') {
      $('#step_1').hide();
      $('#step_2').hide();
      $('#step_3').show();
      $('#tab_step_1').removeClass('tab_active').addClass('tab_inactive');
      $('#tab_step_2').removeClass('tab_active').addClass('tab_inactive');
      $('#tab_step_3').removeClass('tab_inactive').addClass('tab_active');
    } 
  }



//************************************************************************************
// Step 1.
//************************************************************************************


  //------------------------------------------------------------------------------------
  // Inititialize the calendar when page is loaded.
  //------------------------------------------------------------------------------------

  $(document).ready(function(){
    cal_draw_f({sel_cur : false});
  });


  function cal_user_nav_f(date) { 
    ajax_f('get_free_days', '&date='+date);
  }

  function highlight_f(event_dates) { 
    cal_highlight_f(event_dates, 'hl_outer_green');
  }

  function select_handler_f(date) { 

    // Date also contains some other stuff. remove it
    date = date.split('_')[1];

    // Show the available times div
    ajax_f('get_free_times', '&date='+date);

    // Show the available times div
    $('#s1_sel_pilot_wrapper').show();

    // Fix overflow
    s1_layout_f();

  }


  //----------------------------------------------
  // List free times for current day.
  //----------------------------------------------

  function list_free_times_f(row) {

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + row + ")" ); 

    //Date
    var date = myhash['date'];

    // Already booked pilots
    if (myhash['tid_1'] == undefined) {
      var html_table = "Inga lediga tider"
    }
    else {
      var html_table = "<table class='table_list'> \
                          <tr> \
                            <th>Boka       </th> \
                            <th>Tid        </th> \
                            <th>Namn       </th> \
                            <th>Maxlängd   </th> \
                            <th>Maxvikt    </th> \
                            <th>Video      </th> \
                            <th>Foto       </th> \
                            <th>Video+Foto </th> \
                          </tr>";

      for (var i=1; i<200; i++) {
          
        // Break out of loop when no more hits
        if (typeof myhash['tid_id_' + i] == 'undefined') {
          break;
        }
    
        if (myhash['bokad_' + i] == 0) {
          html_table += "<tr>";
          html_table += "  <td align='center'><span class='icon-shopping-cart icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"fill_s2_f('" + myhash['pilot_' + i ] + "','" + myhash['pilot_id_' + i ] + "','" + myhash['maxlangd_' + i ] + "','" + myhash['maxvikt_' + i ] + "','" + date + "','" + myhash['tid_' + i ] + "','" + myhash['tid_id_' + i ] + "'); sel_step_f('2');\"   onmouseover=\"Tip(s1_boka)\" onmouseout=\"UnTip()\"></span></td>";
          html_table += "  <td align='center'>" + myhash['tid_'      + i] + "</td>";
          html_table += "  <td>"                + myhash['pilot_'    + i] + "</td>";
          html_table += "  <td align='center'>" + myhash['maxlangd_' + i] + " cm</td>";
          html_table += "  <td align='center'>" + myhash['maxvikt_'  + i] + " kg</td>";
          if (myhash['video_'  + i] == 1) {
            html_table += "  <td align='center'><span class='icon-ok icon-large icon-st-green icon-st-shadow' onmouseover=\"Tip(s1_video)\" onmouseout=\"UnTip()\"></span></td>";
          }
          else {
            html_table += "  <td align='center'></td>";
          }
          if (myhash['foto_'  + i] == 1) {
            html_table += "  <td align='center'><span class='icon-ok icon-large icon-st-green icon-st-shadow' onmouseover=\"Tip(s1_foto)\" onmouseout=\"UnTip()\"></span></td>";
          }
          else {
            html_table += "  <td align='center'></td>";
          }
          if (myhash['foto_video_'  + i] == 1) {
            html_table += "  <td align='center'><span class='icon-ok icon-large icon-st-green icon-st-shadow' onmouseover=\"Tip(s1_video_foto)\" onmouseout=\"UnTip()\"></span></td>";
          }
          else {
            html_table += "  <td align='center'></td>";
          }
          html_table += "</tr>";
        }
      }
      html_table += "</table>";
    }

    // Insert table in div
    $('#booked_pilots').html(html_table);
    
  }



  //-----------------------------------------------------
  // Remove floats so that overflow works if necessary 
  //-----------------------------------------------------

  $(window).load(function()   { s1_layout_f(); });
  $(window).resize(function() { s1_layout_f(); });

  function s1_layout_f() { 

    $('#s1_calendar_wrapper').css('float','left');
    $('#s1_sel_pilot_wrapper').css('float','left');

    if ($('#content').width() < ($('#s1_calendar_wrapper').outerWidth() + $('#s1_sel_pilot_wrapper').outerWidth())) {
      $('#s1_calendar_wrapper').css('float','none');
      $('#s1_sel_pilot_wrapper').css('float','none');
    }

  };



//************************************************************************************
// Step 2.
//************************************************************************************


  //--------------------------------------------------
  // Fill misc info
  //--------------------------------------------------

  function fill_s2_f(pilot, pilot_id, pilot_length, pilot_weight, date, time, time_id) {
    $('#s2_form_pilot_id').val(pilot_id);
    $('#s2_form_pilot_length').val(pilot_length);
    $('#s2_form_pilot_weight').val(pilot_weight);
    $('#s2_form_date').val(date);
    $('#s2_form_time').val(time);
    $('#s2_form_time_id').val(time_id);
    $('#s2_info_pilot_length').html("&nbsp;&nbsp;(max " + pilot_length + "cm)");
    $('#s2_info_pilot_weight').html("&nbsp;&nbsp;(max " + pilot_weight + "kg)");
    $('#s2_info_date').html(date);
    $('#s2_info_time').html(time);
    $('#s2_info_pilot').html(pilot);
  }


  //--------------------------------------------------
  // Hide / show stuff depending on payment method.
  // Variable 'pk' is used further down when
  // submitting the form.
  //--------------------------------------------------

  $(document).ready(function(){
    /*
    if (rebook_id) {
      ajax_f('get_pk', '&id='+rebook_id);
      //$('#s2_foto, #s2_video').trigger('click');
    }
    */      
    $('#s2_betalningssatt').change(function() {
      if ($('#s2_betalningssatt').val() == 'Presentkort') { 
        $('#s2_pk_select, #s2_pk_valid').show();

        $('.pk_disable').addClass('disabled');
        $('.pk_disable input').attr('disabled', true);
        $('#s2_pax').addClass('disabled');
        $('#s2_pax').find('input').each(function() {$(this).attr('disabled', true)});
      }
      else if ($('#s2_betalningssatt').val() == 'Postgiro') {
        $('#s2_pk_select, #s2_pk_valid').hide();
        $('#s2_pk_nr').val('');
        $('#s2_giltigt_tom').html('-');
        $('#s2_betalat').html('-');      
      }
      else if ($('#s2_betalningssatt').val() == 'På plats') {
        $('#s2_pk_select, #s2_pk_valid').hide();
        $('#s2_pk_nr').val(''); 
        $('#s2_giltigt_tom').html('-');
        $('#s2_betalat').html('-');      
      }
    });
    $('#s2_betalningssatt').trigger('change');
  });


  //----------------------------------------------
  // Bind 'tab' and 'return' to pk nr field
  //----------------------------------------------

  $(function(){ 
    $('#container').on('keyup', '#s2_pk_nr', function(event) { 
      if (event.which == 13) {
        check_pk_f($(this).val());
      }
    });
    $('#container').on('keydown', '#s2_pk_nr', function(event) { 
      if (event.which == 9) {
        check_pk_f($(this).val());
      }
    });
  });



  //----------------------------------------------
  // Get 'presentkort' details
  // When user enters a number in 'Presentkort nr'
  //----------------------------------------------

  // If pax is already booked at a different time - issue a warning
  function check_pk_f(id) {

    // Ajax - get details
    var row = ajax_f('get_pk', '&id='+id);

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + row + ")" ); 

    // Show warning if pax is already booked at a different time
    if (myhash['bokad']) {
      $('#s2_rebook_popup, #overlay').popShow();
      $('#s2_rebook_popup').popCenter();
      $('#s2_rebook_id').val(id);
    }
    else {
      get_pk_f(id);
    }
  }
  function check_cont_f(cont) {
    $('#s2_rebook_popup, #overlay').popHide(); 
    if (cont) {
      get_pk_f($('#s2_rebook_id').val());
    }
    else {
      $('#s2_pk_nr').val('');
    }
  }


  // Get all details
  function get_pk_f(id) {

    // Ajax - get details
    var row = ajax_f('get_pk', '&id='+id);

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + row + ")" ); 

    // Set field value
    $('#s2_video').prop('checked', (myhash['video'] == 1) ? true : false);
    $('#s2_foto').prop('checked',  (myhash['foto']  == 1) ? true : false);
    $('#s2_giltigt_tom').html(myhash['giltigt_till']);
    $('#s2_betalat').html(myhash['betalat']);
    $('#s2_ovrigt').val(myhash['ovrigt']);
    $('#s2_pk_nr').val(myhash['id']);

    $('#s2_pax_fornamn').val(myhash['pax_fornamn']);
    $('#s2_pax_efternamn').val(myhash['pax_efternamn']);
    $('#s2_pax_adress_1').val(myhash['pax_adress_1']);
    $('#s2_pax_adress_2').val(myhash['pax_adress_2']);
    $('#s2_pax_postnummer').val(myhash['pax_postnummer']);
    $('#s2_pax_ort').val(myhash['pax_ort']);
    $('#s2_pax_telefon').val(myhash['pax_telefon']);
    $('#s2_pax_email').val(myhash['pax_email']);
    $('#s2_pax_langd').val(myhash['pax_langd']);
    $('#s2_pax_vikt').val(myhash['pax_vikt']);

    $('#s2_use_contact').prop('checked', (myhash['use_contact'] == 1) ? true : false);
    ena_contact_form_f();
    g_contact_hash = {}; // Clear contact hash
    $('#s2_kontakt_fornamn').val(myhash['kontakt_fornamn']);
    $('#s2_kontakt_efternamn').val(myhash['kontakt_efternamn']);
    $('#s2_kontakt_adress_1').val(myhash['kontakt_adress_1']);
    $('#s2_kontakt_adress_2').val(myhash['kontakt_adress_2']);
    $('#s2_kontakt_postnummer').val(myhash['kontakt_postnummer']);
    $('#s2_kontakt_ort').val(myhash['kontakt_ort']);
    $('#s2_kontakt_telefon').val(myhash['kontakt_telefon']);
    $('#s2_kontakt_email').val(myhash['kontakt_email']);

    // Update hash with original values
    update_current_f();

    // Update photographer schedule.
    // The schedule is updated after Foto/Video is selected so we know if photo is needed as well.
    ajax_f('get_photo_schedule', '&date='+$('#s2_form_date').val());
  }

  function update_current_f() {
    $('#s2_p2, #s2_pax, #s2_kontakt, #s2_p1_right').find('input').each(function(){
      if ($(this).attr('type') == 'checkbox') {
        var cb_val = $(this).prop('checked') == true ? 1 : 0; 
        g_change_hash_org[$(this).attr('name')] = cb_val;
      }
      else {
        g_change_hash_org[$(this).attr('name')] = $(this).val();
      }
    });
  }






  //---------------------------------------------------------
  // Get all 'presentkort' details
  // When user hits the button to bring up all 'presentkort'
  //---------------------------------------------------------

  function get_all_pk_f(rows) {

    // Disable background scrolling
    $('body').css('overflowY', 'hidden');

    // Entries are separated by '|'
    rows = rows.split('|'); 

    // Create html table rows to insert into table that is already defiened.
    var html_table = "";
    for (row in rows) {

      // Ajax fetches the result as hash. mkhash parses the hash.
      var myhash = eval( "mkhash(" + rows[row] + ")" ); 

      // Icon instead of '1' or '0'.
      var video = "";
      var foto  = "";
      var bokad = "";

      if (myhash['bokad'])        { bokad = "<span class='icon-ok icon-large icon-st-green icon-st-shadow icon-st-click'></span>"; }
      if (myhash['video'] == '1') { video = "<span class='icon-ok icon-large icon-st-green icon-st-shadow icon-st-click'></span>"; }
      if (myhash['foto']  == '1') { foto  = "<span class='icon-ok icon-large icon-st-green icon-st-shadow icon-st-click'></span>"; }

      if (myhash['bokad']) {
        html_table += "<tr>";
      }
      else {
        html_table += "<tr class='tr_hover' onclick=\"$('#s2_pk_popup, #overlay').toggle(); get_pk_f(" + myhash['id'] + "); window.scroll(0,0); $('body').css('overflowY', 'auto'); \">";
      }
        html_table += "<td class='pk_list_pk_nr'>"  + myhash['id']               + "</td> \
                       <td align='center'>"         + bokad                      + "</td> \
                       <td >"                       + myhash['giltigt_till']     + "</td> \
                       <td >"                       + myhash['betalat']          + "</td> \
                       <td align='center'>"         + video                      + "</td> \
                       <td align='center'>"         + foto                       + "</td> \
                       <td class='space'></td> \
                       <td class='pk_list_namn'>"   + myhash['pax_fornamn'] + " " + myhash['pax_efternamn'] + "</td> \
                       <td class='pk_list_tel'>"    + myhash['pax_telefon']      + "</td> \
                       <td class='pk_list_email'>"  + myhash['pax_email']        + "</td> \
                       <td >"                       + myhash['pax_langd']        + "</td> \
                       <td >"                       + myhash['pax_vikt']         + "</td> \
                       <td class='space'></td> \
                       <td >"                       + myhash['kontakt_fornamn']  + " " + myhash['kontakt_efternamn'] + "</td> \
                       <td class='pk_list_tel'>"    + myhash['kontakt_telefon']  + "</td> \
                       <td class='pk_list_email'>"  + myhash['kontakt_email']    + "</td> \
                       <td class='space'></td> \
                       <td>"                        + myhash['ovrigt']           + "</td> \
                     </tr>";

    }

    // Insert table rows.
    $('#s2_pk_list').html(html_table);


    // Show the popup
    $('#overlay').show();
    $('#s2_pk_popup').popShow();
    $('#s2_pk_popup').popCenter();

  }


  //---------------------------------------------------------
  // Filter 'presentkort' details
  //---------------------------------------------------------

  $(document).ready(function(){

    // Write on keyup event of keyword input element
    $("#pk_filter_inp").keyup(function(){

      // Lock width of popup window   
      $('#s2_pk_popup').css('width', $('#s2_pk_popup').width());

      // When value of the input is not blank
      if( $(this).val() != "") {
        // Show only matching TR, hide rest of them
        $("#s2_pk_list>tr").hide();
        if ($("#pk_filter_sel").val() == 'Namn') {
          $(".pk_list_namn:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
        else if ($("#pk_filter_sel").val() == 'Email') {
          $(".pk_list_email:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
        else if ($("#pk_filter_sel").val() == 'Telefon') {
          $(".pk_list_tel:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
        else if ($("#pk_filter_sel").val() == 'pk_nr') {
          $(".pk_list_pk_nr:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
      }
      else {
        // When there is no input or clean again, show everything back
        $("#s2_pk_list>tr").show();
      }
    });
  });

  // jQuery expression for case-insensitive filter
  $.extend($.expr[":"], {
    "contains-ci": function(elem, i, match, array) {
      return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
  });


  //----------------------------------------------
  // Photographer schedule. This is a mess !!!
  //----------------------------------------------

  function photo_schedule_f(row, sel_time) {


    // If phot or vide is selected, show photographer dropdown. If not, return
    if ($('#s2_foto').is(':checked') || $('#s2_video').is(':checked')) {
      $('#s2_foto_sel').show();
    }
    else {
      $('#s2_foto_sel').hide();
      return;
    }

    // Different sets of dates are separated by '|'
    row = row.split('|'); 

    // Remove colon from time
    sel_time = sel_time.substr(0,2) + sel_time.substr(3,2)

    // Ajax fetches the result as hash. mkhash parses the hash.
    var nofFotoHash    = eval( "mkhash(" + row[0] + ")" ); 
    var nof_fotografer = nofFotoHash['nof_fotografer'];
    var fotoHash       = eval( "mkhash(" + row[1] + ")" ); 

    // Clear the dropdown box.
    $('#s2_foto_dropdown').html('');

    var html_table;

    html_table  = "<table class='table_list' style='text-align: center;'>";
    html_table += "  <tr>";
    html_table += "    <th>Tid</th>";
    for (var i=1; i<=nof_fotografer; i++) {
      var vf_str = "";

      if ((fotoHash['video_' + i] == 1) && (fotoHash['foto_' + i] == 1)) { 
        vf_str = "(V,F)";
      }
      else if (fotoHash['video_' + i] == 1) {
        vf_str = "(V)";
      }
      else if (fotoHash['foto_' + i] == 1) {
        vf_str = "(F)";
      }

      html_table += "  <th align='center'>" + fotoHash['foto_fornamn_' + i] + " " + fotoHash['foto_efternamn_' + i] + "<br>" + vf_str + "</th>";
    }
    html_table += "  </tr>";


    var current       =  7*60*60;
    var current_hum   = '07:00';
    var current_hum_m = '00';
    var j             = 1;

    for (var i=1; i<29; i++) {

      html_table += "<tr style='line-height: 0.9em;' id='slot_" + current_hum + "'>";

      if (current_hum_m == '00') {
        html_table += "  <td class='t00' align='center'>" + current_hum + "</td>";
      }
      else {
        html_table += "  <td class='t30' align='center'>" + current_hum + "</td>";
      }

      for (var j=1; j<=nof_fotografer; j++) {
        if ((current_hum >= fotoHash['tid_start_' + j]) && (current_hum <= fotoHash['tid_stop_' + j])) {
          if (current_hum_m == '00') {
            html_table += "  <td class='t00' id='slot_" + current_hum + "_" + fotoHash['foto_id_' + j] + "'></td>";
          }
          else {
            html_table += "  <td class='t30' id='slot_" + current_hum + "_" + fotoHash['foto_id_' + j] + "'></td>";
          }
        }
        else {
          if (current_hum_m == '00') {
            html_table += "  <td class='t00 photo_schedule_unavail' id='slot_" + current_hum + "_" + fotoHash['foto_id_' + j] + "'></td>";
          }
          else {
            html_table += "  <td class='t30 photo_schedule_unavail' id='slot_" + current_hum + "_" + fotoHash['foto_id_' + j] + "'></td>";
          }
        }
      }
      html_table += "  </tr>";
      
      // Time parsing
      current       = current + 30*60;
      current_hum_h = parseInt(current/3600) % 24;
      current_hum_m = parseInt(current/60)   % 60;
      current_hum_h = (current_hum_h < 10) ? ("0" + current_hum_h) : ("" + current_hum_h);
      current_hum_m = (current_hum_m < 10) ? ("0" + current_hum_m) : ("" + current_hum_m);
      current_hum   = current_hum_h + current_hum_m;
    }
    html_table += "</table>";

    // Add to div
    $('#photo_schedule').html(html_table);


    // ---- Mark booked photographer times as unavailable ----

    // Ajax fetches the result as hash. mkhash parses the hash.
    timesHash = eval( "mkhash(" + row[2] + ")" ); 

    // For every scheduled (booked or not booked) time.
    for (var i=1; i<200; i++) {  // 200 is just a large number
      // Break out of loop when no more hits
      if (typeof timesHash['tid_' + i] == 'undefined') {
        break;
      }
      // If booked
      if (timesHash['id_' + i] != '') {
        // Create variables for table cell id (current time and next 30 minutes)
        var half_1_id = "slot_" + timesHash['tid_' + i] + "_" + timesHash['id_' + i];
        var half_2_id;
        if (timesHash['tid_' + i].substr(2,2) == '00') {
          half_2_id = timesHash['tid_' + i].substr(0,2) + "30";
        }
        else {
          half_2_id = (parseInt(timesHash['tid_' + i].substr(0,2))+1) + "00";
          if (timesHash['tid_' + i].substr(0,2).length < 2) half_2_id = "0" + half_2_id;
        }
        half_2_id = "slot_" + half_2_id + "_" + timesHash['id_' + i];
        // Add booked pilot in table. Change class of <td> to ...booked
        $('#'+half_1_id).html("P: " + timesHash['pilot_' + i]);
        $('#'+half_1_id).addClass('photo_schedule_booked half_1')
        $('#'+half_2_id).addClass('photo_schedule_booked half_2')
        // Add pilot id to current <td> id.
        $('#'+half_1_id).attr('id', half_1_id + '_' + timesHash['pilot_id_' + i]);
      }
    }


    // ---- Mark available photographers for selected time ----
    photoHash = eval( "mkhash(" + row[1] + ")" ); 

    // Create full hour time variables
    var sel_time_split = [sel_time.substr(0,2), sel_time.substr(2,2)];
    var next_30;
    if (sel_time_split[1] == '00') {
      next_30 = sel_time_split[0] + "30";
    }
    else {
      next_30 = (parseInt(sel_time_split[0],10)+1) + "00";
      if (sel_time_split[0].length == 1) { next_30 = "0" + next_30; }
    } 

    // For every photographer scheduled for today
    for (var j=1; j<=nof_fotografer; j++) {
      // Create variables for table cell id (current time and next 30 minutes)
      var sel_half_1_id = "slot_" + sel_time + "_" + photoHash['foto_id_' + j];
      var sel_half_2_id = "slot_" + next_30  + "_" + photoHash['foto_id_' + j];
      // If not unavailable (i.e. available) ...as within working hours
      if (! ($('#'+sel_half_1_id).hasClass('photo_schedule_unavail') || $('#'+sel_half_2_id).hasClass('photo_schedule_unavail')) ) {
        // If not already booked
        if ($('#'+sel_half_1_id).length && $('#'+sel_half_2_id).length) {
          // Check photographer capabilites against requirement
          var capable = true;
          if ( $('#s2_foto').is(':checked')  && (photoHash['foto_'  + j] == 0) ) { capable = false; }
          if ( $('#s2_video').is(':checked') && (photoHash['video_' + j] == 0) ) { capable = false; }
          // Mark time if photographer is capable
          if (capable) {
            // Add photographer to dropdown list
            $('#s2_foto_dropdown').append("<option id='f_dd_"+ photoHash['foto_id_' + j] +"' value='"+ photoHash['foto_id_' + j] +"'>"+ photoHash['foto_fornamn_' + j] + " " + photoHash['foto_efternamn_' + j] + "</option>");
            // Add some classes and onclick event to table cell
            $('#'+sel_half_1_id).addClass("photo_schedule_avail half_1 pId_" + photoHash['foto_id_' + j]);
            $('#'+sel_half_2_id).addClass("photo_schedule_avail half_2 pId_" + photoHash['foto_id_' + j]);
            $('body').on('click','#'+sel_half_1_id, function(){ $('#s2_foto_dropdown').val(photoHash['foto_id_' + $(this).index()]); $('#s2_ph_sched_popup, #overlay').hide(); });
            $('body').on('click','#'+sel_half_2_id, function(){ $('#s2_foto_dropdown').val(photoHash['foto_id_' + $(this).index()]); $('#s2_ph_sched_popup, #overlay').hide(); });

          }
        }
      }
    }


    // ---- Higlight full hour when hovered ----
    //$("td.photo_schedule_avail").mouseenter(function() {
    $('body').on('mouseenter', 'td.photo_schedule_avail', function(){ 
      var allClasses =  $(this).attr("class");
      //var allClasses =  $('td.photo_schedule_avail').attr("class");
      var pClass     = allClasses.match(/pId_[0-9]+/);
      $("td."+pClass).addClass("photo_schedule_avail_hover") 
    });

    //$("td.photo_schedule_avail").mouseleave(function() {
    $('body').on('mouseleave', 'td.photo_schedule_avail', function(){ 
      var allClasses =  $(this).attr("class");
      //var allClasses =  $('td.photo_schedule_avail').attr("class");
      var pClass     = allClasses.match(/pId_[0-9]+/);
      $("td."+pClass).removeClass("photo_schedule_avail_hover") 
    });


    // ---- Get most appropriate photographer ----

    var sel_pilot_id = $('#s2_form_pilot_id').val();
    var booked       = $('.photo_schedule_booked');
    var avail        = $('.photo_schedule_avail');
    var availArr     = new Array();


    // Default photografer
    for (var k=0; k<avail.length; k++) {
      availArr.push(avail[k].id.split('_')[2]);
    }
    $('#s2_foto_dropdown').val(availArr[0]);


    // Change photographer if there is a more appropriate one
    if (booked.length > 0) {
      for (var j=0; j<booked.length; j++) {
        var cur_booked = booked[j].id.split('_');
        var time       = cur_booked[1];
        var photo_id   = cur_booked[2];
        var pilot_id   = cur_booked[3];
        var hit        = false;
        if ($.inArray(photo_id, availArr) !== -1) {
          if (sel_pilot_id == pilot_id) {
            $('#s2_foto_dropdown').val(photo_id);
            hit = true;
          }
          if ((time > sel_time) && (hit == true)) {
            break;
          }
        }
      }
    }

  }


  //----------------------------------------------
  // Enabe/disable contact form
  //----------------------------------------------

  $(document).ready(function() {
    $('#s2_use_contact').change(function() {
      ena_contact_form_f();
      fill_contact_form_f();
    });
  });


  function ena_contact_form_f() {
    if ($('#s2_use_contact').is(':checked')) {
      $('#s2_kontakt').removeClass("disabled");
      $('#s2_kontakt input').attr('disabled', false);
      $('#s2_pax_telefon').removeClass('required inp_error');
    }
    else {
      $('#s2_kontakt').addClass("disabled");
      $('#s2_kontakt input').removeClass("inp_error");
      $('#s2_kontakt input').attr('disabled', true);
      $('#s2_pax_telefon').addClass('required');
    }
  }

  function fill_contact_form_f() {
    $('#s2_kontakt').find('input').each(function() { 
      if ($('#s2_use_contact').is(':checked')) {
        $(this).val(g_contact_hash[$(this).attr('id')]);
      }
      else {
        g_contact_hash[$(this).attr('id')] = $(this).val(); 
        $(this).val("");
      }
    });
  }



  //----------------------------------------------
  // Presentkort details changed.
  // Show option to update presentkort database.
  //----------------------------------------------

  $(document).ready(function() {
    $('#s2_p2, #s2_pax, #s2_kontakt, #s2_p1_right').find('input').each(function(){
      $(this).change(function() {
        if ($('#s2_betalningssatt').val() == 'Presentkort') { 
          var l = $(this).attr('name');
          if ($(this).attr('type') == 'checkbox') {
            var cb_val = $(this).prop('checked') == true ? 1 : 0; 
            if (cb_val != eval('g_change_hash_org.' + l)) {
              g_change_hash[$(this).attr('name')] = cb_val;
            }
            else {
              delete g_change_hash[$(this).attr('name')];
            }
          }
          else {
            if ($(this).val() != eval('g_change_hash_org.' + l)) {
              g_change_hash[$(this).attr('name')] = $(this).val();
            }
            else {
              delete g_change_hash[$(this).attr('name')];
            }
          }

          if (empty_hash(g_change_hash)) {
            $('#s2_change_li').hide();
            $('#s2_submit_li').show();
          }
          else {
            $('#s2_change_li').show();
            $('#s2_submit_li').hide();
          }

          function empty_hash(obj) {
            var name;
            for (name in obj) {
              if (obj.hasOwnProperty(name)) {
                return false;
              }
            }
            return true;
          }
        }
      });
    });
  });



  //----------------------------------------------
  // Abort changes and reload pk details
  //----------------------------------------------

  function s2_change_abort_f() {

    // Show the submit button, Hide update button
    $('#s2_change').hide();
    $('#s2_submit').show();

    // Reload original details
    get_pk_f($('#s2_pk_nr').val());
  }



  //----------------------------------------------
  // Submit "booking"
  //----------------------------------------------

  function s2_submit_f(type) {

    // Betalningssätt
    // Pax / kontakt

    // --- Validate ---
    var error     = false;

    // Required fields
    $('#step_2 .required').each(function() {
      if (! $(this).is(':disabled') && $(this).is(':visible')) { 
        if ($(this).val() == "") {
          $(this).addClass("inp_error");
          error = true;
        }
        else {
          $(this).removeClass("inp_error");
        }
      }
    });

    // Check that length and weight only contains digits
    var intRegex  = /^\d+$/;
    var dateRegex = /^\d\d\d\d\-[0-1][1-9]-[0-3][0-9]$/;
    if (! intRegex.test($('#s2_pax_vikt').val()) && ($('#s2_pax_vikt').val() != "")) {
      $('#s2_pax_vikt').addClass("inp_error");
      error = true;
    }
    if (! intRegex.test($('#s2_pax_langd').val()) && ($('#s2_pax_langd').val() != "")) {
      $('#s2_pax_langd').addClass("inp_error");
      error = true;
    }

    // Check if length and height are valid for selected pilot
    if ($('#s2_pax_langd').val() > $('#s2_form_pilot_length').val()) {
      $('#s2_pax_langd').addClass("inp_error");
      error = true;
    }
    if ($('#s2_pax_vikt').val() > $('#s2_form_pilot_weight').val()) {
      $('#s2_pax_vikt').addClass("inp_error");
      error = true;
    }

    // Continue if no errors
    if (! error) {
      // --- No changes. Submit booking form. ---
      if (type == 'book') {

        // No presenkort, add pax details to database
        if ($('#s2_betalningssatt').val() != 'Presentkort') { 
          var qstr = "";
          $('#step_2').find('input').each(function() {
            if ($(this).attr('type') == 'checkbox') {
              var cb_val = $(this).prop('checked') == true ? 1 : 0;
              qstr +=  "&"+ $(this).attr('name') +"="+ cb_val; 
            }
            else if ($(this).val() != "") {
              qstr +=  "&"+ $(this).attr('name') +"="+ $(this).val(); 
            }
          });
          $('#s2_pk_nr').val(ajax_f('new_pax', qstr));
        }

        // Do the booking
        var qstr = "&" + $('#s2_form_time_id').attr('name')  + "=" + $('#s2_form_time_id').val() +
                   "&" + $('#s2_pk_nr').attr('name')         + "=" + $('#s2_pk_nr').val() +
                   "&" + $('#s2_b_ovrigt').attr('name')      + "=" + $('#s2_b_ovrigt').val() +
                   "&" + $('#s2_bokare').attr('name')        + "=" + $('#s2_bokare').val();

        if ($('#s2_foto_dropdown').val().match(/^\d+$/)) {
          qstr +=  "&" + $('#s2_foto_dropdown').attr('name') + "=" + $('#s2_foto_dropdown').val();
        }

        var result = ajax_f('new_tandem_booking', qstr);

        //Go to step 3
        receipt_f(result);
      }

      // --- Changes were made. First update db. ---
      else if (type == 'update') {
        var qstr = "&id=" + $('#s2_pk_nr').val();
        for (key in g_change_hash) {
          if (g_change_hash.hasOwnProperty(key)) {
            var value = eval('g_change_hash.' + key);
            qstr += "&" + key.substring(3) + "=" + value; // remove 's2_' from name
          }
        }
        var result = ajax_f('change_pax_pk', qstr);
        if (result) {
          $('#s2_change_li').hide();         // Hide update button
          $('#s2_submit_li').show();         // Show the submit button
          update_current_f();                // Update current value hash
        }
        else {
          alert("Something went wrong:" + result);
        }
      }
    }
  }

  //--------------------------------------------------
  // Alignment
  //--------------------------------------------------

  //$(window).resize(function()  { resize_f(); });
  //$(document).ready(function() { resize_f(); });

  function resize_f() {
    var s2_width = $('.c1').outerWidth()*2 + $('.c2').outerWidth()*2 + $('.s2_hspacer').outerWidth();
    var cont = $('#step_1').innerWidth() - $('#step_2').css('padding').replace('px','')*2;
    var padding = (cont - s2_width) / 2;
    $('#s2_p1').css('padding-left', padding);
    $('#s2_p2').css('padding-left', padding);
  }



//************************************************************************************
// Step 3.
//************************************************************************************

function receipt_f(status) {
  status = trim(status);
  sel_step_f('3');
  if (status == "OK") {
    var foto_str = "-";
    if ( $('#s2_video').is(':checked') ) {
      if ( $('#s2_foto').is(':checked') ) {
        foto_str = " (Video & foto)";
      }
      else {
        foto_str = " (Video)";
      }
    }
    else if ( $('#s2_foto').is(':checked') ) {
      foto_str = " (Foto)";
    }

    $('#s3_date').html($('#s2_info_date').html());
    $('#s3_time').html($('#s2_info_time').html());
    $('#s3_pilot').html($('#s2_info_pilot').html());
    $('#s3_photo').html($("#s2_foto_dropdown option[value='"+ $("#s2_foto_dropdown").val() +"']").text() + foto_str);

    ajax_mail_f();

  }
  else {
    $('#step_3').html(status);
  }
}

//************************************************************************************
// Tooltips
//************************************************************************************


  //---------------------
  // Step 1
  //---------------------

  var s1_boka        = "Boka tid";
  var s1_video       = "Video tillgängligt";
  var s1_foto        = "Foto tillgängligt";
  var s1_video_foto  = "Video & Foto tillgängligt";


  //---------------------
  // Step 2
  //---------------------

  var s2_pk_fyll    = "Fyll från nummer";
  var s2_pk_lista   = "Lista alla presentkort";
  var s2_fotoschema = "Visa fotografschema";


  var ovrigt  = "Kopplat till bokning. <br><u>Inte</u> till pax.";
  var update  = "Du har ändrat kontaktuppgifterna. <br>Klicka här för att uppdatera dem i databasen.";
  var discard = "Ångra uppdateringen av kontaktuppgifter.";
  var pax     = "Pax är fallskärmssvenska för Passagerare. <br><br> \
                 Namn och vikt är obligatoriska uppgifter. Om tandemhoppet är en present och <br> \
                 ni inte vill att vi kontaktare \"paxet\" så kan ni fylla i fullständiga uppgifter <br> \
                 under \"Kontakt\" här till höger.";
