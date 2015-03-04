//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************



//************************************************************************************
// AJAX
//************************************************************************************

  // Add ajax animation block
  $(document).ready(function() {
    ajax_anim();
  });

  function ajax_f(action, qstr, params) {

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
        if (action == 'get_event_days')    { highlight_f(ajaxRequest.responseText); }                  // Draw calendar with highlights
        if (action == 'list_day')          { list_day_f(params['date'], ajaxRequest.responseText); }   // 
        if (action == 'delete_time_popup') { delete_time_popup_f(ajaxRequest.responseText); }          // 
      }
    }

    // Now get the value from user and pass it to server script
    var queryString = "?C5_URL=" + CCM_REL + "&action=" + action + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/tandembokning/pilottider_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'is_scheduled') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'new_times') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'update_times') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'jump_hours') {
      return(ajaxRequest.responseText);
    }
    if (action == 'new_pilot_booking') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'update_pilot_booking') {
      return(ajaxRequest.responseText);
    }
  }



//************************************************************************************
// General
//************************************************************************************

  //-----------------------------------------------
  // Align so that all boxes have the same size
  //-----------------------------------------------

  function resize_reset_f() {
    console.log("reset");
    $('#step_2').css('width',     'auto');
    $('#step_2').css('min-width', '0');
    $('#step_3').css('width',     'auto');
    $('#step_3').css('min-width', '0');
  }

  function resize_set_f() {

    var p1 = $('#step_1').position().left;
    var p2 = $('#step_2').position().left;
    var p3 = $('#step_3').position().left;

    if (p2 <= p1) {
      $('#step_2').css('min-width',  $('#step_1').outerWidth());
      $('#step_2').css('min-height', '0');
    }
    else {
      $('#step_2').css('min-width',  '0');
      $('#step_2').css('min-height', $('#step_1').outerHeight());
    }
    
    if (p3 <= p1) {
      $('#step_3').css('min-width',  $('#step_1').outerWidth());
      $('#step_3').css('min-height', '0');
    }
    else {
      $('#step_3').css('min-width',  '0');
      $('#step_3').css('min-height', $('#step_1').outerHeight());
    }
  }

  var resize_callbacks = $.Callbacks();
  resize_callbacks.add(resize_reset_f);
  resize_callbacks.add(resize_set_f);

  $(window).resize(function() { resize_callbacks.fire(); });



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
    ajax_f('get_event_days', '&date='+date);
  }

  function highlight_f(event_dates) { 

    // Reset highlights
    cal_highlight_reset_f();
    
    // Highlight dates with events, different sets of dates are separated by ':'
    dates = event_dates.split(':'); 

    if (dates[0] != "") {cal_highlight_f(dates[0], 'hl_outer_green');  } // jumping
    if (dates[1] != "") {cal_highlight_f(dates[1], 'hl_inner_orange'); } // pilot
    if (dates[2] != "") {cal_highlight_f(dates[2], 'hl_inner_bold');   } // photographer
  }


  function select_handler_f(date) { 

    // Date also contains some other stuff. remove it
    date = date.split('_')[1];

    // Handle visibility of divs
    $('#step_3').hide();
    $('#step_2').show();

    // Call ajax function to show who is booked for current day
    ajax_f('list_day', '&date='+date, {'date' : date});

    // Reset name dropdown
    $('#name_dropdown').val('0');

    // Resize function
    resize_callbacks.fire();

  }



//************************************************************************************
// Step 2.
//************************************************************************************

  function list_day_f(date, row) {

    // Hopp_schema and tandem_schema is seperated by '|'.
    schema = row.split('|'); 


    //----------------------------------------------
    // Schedule warning
    //----------------------------------------------

    // Ajax fetches the result as hash. mkhash parses the hash.
    var hoppHash  = eval( "mkhash(" + schema[0] + ")" ); 
    var tid_start = hoppHash['hoppning_start'] != '' ? hoppHash['hoppning_start'] : '';
    var tid_stop  = hoppHash['hoppning_stop']  != '' ? hoppHash['hoppning_stop']  : '';

    // Assert warning if no jumping is planned for selected day
    if (tid_start == '') {
      $('#schema_varning').html("<p>! Ingen hoppning shemalagd den här dagen. Du kan boka in tandempilot ändå men se till att fixa pilot och hoppledare</p>");
    }
    else {
      $('#schema_varning').html("");
    }


    //----------------------------------------------
    // List scheduled pilots for current day
    //----------------------------------------------

    // Creatade html string to insert into div.
    var html_table = "<p>Schemalagda piloter:</p>";

    // If someone is booked schema[1] will not be undefined
    if (schema[1] != '') {

      // Ajax fetches the result as hash. mkhash parses the hash.
      var tandemHash = eval( "mkhash(" + schema[1] + ")" ); 

      // Already booked pilots
      html_table += "<table class='table_list'> \
                       <tr> \
                         <th>Ta bort</th> \
                         <th>Tid</th> \
                         <th>Namn</th> \
                         <th>Bokad</th> \
                       </tr>";


      for (var i=1; i<200; i++) {    // 200 doesn't mean anything, it's just a large number
        
        // Break out of loop when no more hits
        if (typeof tandemHash['id_' + i] == 'undefined') {
          break;
        }
  
        // Some stuff if current time is booked with a pax
        if (tandemHash['bokad_' + i] != 0) {
          var bokad       = 'Ja'; 
          var bokad_color = '#FF0000'; 
        } 
        else { 
          var bokad       = 'Nej'; 
          var bokad_color = '#00FF00';
        }

        html_table += "<tr>";
        if (bokad == 'Ja') {
          html_table += "<td align='center'><span class='icon-remove icon-large icon-st-grey icon-st-shadow' onmouseover='Tip(tip_no_remove_time)' onmouseout='UnTip()'></span></td>";
        }
        else {
          html_table += "<td align='center'><span class='icon-remove icon-large icon-st-lightblue icon-st-shadow icon-st-click' onmouseover='Tip(tip_remove_time)' onmouseout='UnTip()' onclick=\"ajax_f('delete_time_popup','&tid=" + tandemHash['id_' + i] + "');\"></span></td>";
        }
        html_table += "  <td>" + tandemHash['tid_'  + i] + "</td>";
        html_table += "  <td>" + tandemHash['pilot_' + i] + "</td>";
        html_table += "  <td align='center' style='background-color: " + bokad_color + "; font-weight: bold;'>" + bokad + "</td>";
        html_table += "</tr>";
      }
      html_table += "</table>";

      // Insert table in div
      $('#booked_pilots').html(html_table);
    }        
    // If no pilot is booked on current date
    else {
      html_table += "Inga tandempiloter schemalagda";
      $('#booked_pilots').html(html_table);
    }


    // Date and jumping hours
    $('#hopptider').html(tid_start +" - "+ tid_stop);
    $('#datum').html(date);

    // Put selected date and jumping times in hidden form field
    $('#form_date').val(date);
    $('#form_start').val(tid_start);
    $('#form_stop').val(tid_stop);
       
  }


  //----------------------------------------------
  // Delete time. 
  //----------------------------------------------

  function delete_time_popup_f(row) { 

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + row + ")" ); 

    // Insert info in delete popup
    $('#delete_p_datum').html(myhash['datum']);
    $('#delete_p_tid').html(myhash['tid'].substr(0,5));
    $('#delete_p_pilot').html(myhash['pilot']);
    $('#delete_p_id').val(myhash['id']);
    $('#delete_p_date').val(myhash['datum']);

    // Show the popup
    $('#delete_time_popup, #overlay').popShow();
    $('#delete_time_popup').popCenter();
  }


  function delete_time_f(id, date) {

    ajax_f('delete_time', "&id="+id);

    // Update calendar
    ajax_f('get_event_days', '&date='+date);

    // Select same date again
    $('#outer_'+date).trigger('click');

    // Hide the popup
    $('#delete_time_popup, #overlay').popHide();
  }




  //----------------------------------------------
  // Decide what to do in step 3.
  // If selected pilot is already scheduled
  // today - udate, otherwhise add
  //----------------------------------------------

  function select_pilot_f(pilot) {

    // Check if scheduled
    var scheduled = ajax_f('is_scheduled', '&pilot='+pilot+'&date='+$('#form_date').val());
    
    // Add function to callback list to execute them in sequence
    var callbacks = $.Callbacks();
    callbacks.add(create_table_f);
    if (scheduled) {
      callbacks.add(update_times_f);
    }
    else {
      callbacks.add(new_times_f);
    }
    callbacks.fire(pilot);

  }




//************************************************************************************
// Step 3.
//************************************************************************************

  //----------------------------------------------
  // Helper functions
  //----------------------------------------------

  // --- Create the checkbox table ---

  function create_table_f() {
    var html = '';
    for (var h=0; h<24; h++) {
      hh    = h < 10 ? '0'+h : h; 
      html += "<tr id='h"+h+"' name='' class=''><td";
      html += "  <td><input type='checkbox' id='cb_"+ hh +"_00' name='cb_"+ hh +"_00' class='cb' value='"+ hh +"_00'>"+ hh +":00</td>";
      html += "  <td><input type='checkbox' id='cb_"+ hh +"_30' name='cb_"+ hh +"_30' class='cb' value='"+ hh +"_30'>"+ hh +":30</td>";
      html += "</tr>";
    }
    html += "</table>";

    $('#select_times_tbody').html(html);
  }  


  // --- Jumping start/stop times. ---

  // If jumping is scheduled, use scheduled times.
  // Otherwhise get default times from database.
  function get_start_stop_f() {
    var start;
    var stop;
    if ($('#form_start').val() != '') {
      start = $('#form_start').val();
      stop  = $('#form_stop').val();
    }
    else {
      // Get date from hidden form and reformat (mo=1, su=7)
      var wd = new Date($('#form_date').val()).getDay();
      wd = (wd == 0) ? 7 : wd;

      // Get default jump hours from database
      var start_stop = ajax_f('jump_hours', '&wd='+wd);
      var myhash     = eval( "mkhash(" + start_stop + ")" ); 
      var start      = myhash['start_time'];
      var stop       = myhash['stop_time'];
    }
    return {'start' : start, 'stop' : stop};
  }


  //-------------------------------------------------
  // Add new time checkboxes.
  // Tandem pilot is NOT already scheduled this day.
  //-------------------------------------------------

  function new_times_f(pilot) {

    // Put selected pilot in hidden from feed
    $('#form_name').val(pilot);

    // Ajax fetches the result as hash. mkhash parses the hash.
    var row    = ajax_f('new_times', '&pilot='+pilot);
    var myhash = eval( "mkhash(" + row + ")" ); 

    // Check default times
    var inc = myhash['inc'];
    $('#interv_dropdown').val(inc);
    check_cb_f(inc);

    // Show step 3 and resize boxes
    $('#step_3').show();
    $('#times_move').show();
    $('#times_interval').show();
    $('#s3_header_new').show();
    $('#s3_header_update').hide();
    $('#new_pilot_submit').show();
    $('#update_pilot_submit').hide();
    resize_callbacks.fire();
  }


  //----------------------------------------------
  // Add update time checkboxes.
  // Tandem pilot is already scheduled this day.
  //----------------------------------------------

  function update_times_f(pilot) {

    // Show step 3.
    // Hide move times controls
    // Disable submit button (enabled when changes are made)
    // Resize boxes
    $('#step_3').show();
    $('#s3_header_new').hide();
    $('#s3_header_update').show();
    $('#new_pilot_submit').hide();
    $('#update_pilot_submit').show();
    $('#update_pilot_submit').attr('disabled','disabled');
    resize_callbacks.fire();

    // Put selected pilot in hidden form field
    $('#form_name').val(pilot);

    // Get date from hidden form field
    var date = $('#form_date').val();

    // Ajax fetches the result as hash. mkhash parses the hash.
    var row    = ajax_f('update_times', '&datum='+date+'&pilot='+pilot);
    var rows   = row.split('|'); 
    var myhash = eval( "mkhash(" + rows[0] + ")" ); 

    // Check all times where the tandem pilot is scheduled.
    // If a specific time is booked with pax, prevent changes.
    for (var i=1; i<=rows[1]; i++) {
      var cb_sel = 'cb_' + myhash['tid_'+i].substr(0,2) +"_"+ myhash['tid_'+i].substr(3,2); // Reformat to 'cb_hh_mm'
      $('#'+cb_sel).prop("checked", true);
      $('#'+cb_sel).addClass('checked');
      $('#'+cb_sel).closest('td').addClass('checked');
      if (myhash['pax_'+i] != 0) {
        $('#'+cb_sel).attr('disabled','1');
        $('#'+cb_sel).addClass('booked');
        $('#'+cb_sel).closest('td').addClass('booked');
      }
    }

    // If any pax is booked - hide interval selector and move buttons
    if ($('.booked')[0]) {
      $('#times_interval').hide();
      $('#times_move').hide();
    }

    // Get jumping start stop times
    var start_stop = get_start_stop_f();
    var start      = start_stop.start;
    var stop       = start_stop.stop;

    // Hide checkboxes that are outside of jumping hours
    var id_first = $('#times :checkbox:checked').eq(0).attr('id');
    var id_last  = $('#times :checkbox:checked').eq(-1).attr('id');
    var t_first  = new Date("1/1/1970 " + id_first.substr(3,2) +":"+ id_first.substr(6,2));
    var t_last   = new Date("1/1/1970 " + id_last.substr(3,2)  +":"+ id_last.substr(6,2));
    var t_start  = new Date("1/1/1970 " + start);
    var t_stop   = new Date("1/1/1970 " + stop);

    $('#times :checkbox:even').each(function() {
        var t_curr = new Date("1/1/1970 " + $(this).val().substr(0,2)+":"+$(this).val().substr(3,2));
        if ( (t_curr <= t_start) && (t_curr <= t_first) ) {
          $(this).closest('tr').prev().hide();
        }
        if ( (t_curr >= t_stop) && (t_curr > t_last) ) {
          $(this).closest('tr').hide();
        }
    });


    // Enable/disable button
    $(document).on('change', '.cb', function(event) {
      $('#update_pilot_submit').removeAttr('disabled');
    });

  }



  // --- Check the checkboxes. ---

  function check_cb_f(inc) {
  
    // Get jumping start stop times
    var start_stop = get_start_stop_f();
    var start      = start_stop.start;
    var stop       = start_stop.stop;

    var start_index  = $('.cb').index($('#cb_'+ start.substr(0,2) +'_'+ start.substr(3,2))) -1;
    var start_offset = start_index % (inc/30);

    // First uncheck all checkboxes and remove class 'checked'
    $('.cb').prop("checked", false);
    $('.cb').removeClass("checked");
    $('.cb').closest('td').removeClass("checked");

    // For each chekbox
    $('.cb').each(function(i) {
        
      // Check the checkboxes
      if (! ((i-start_offset)%(inc/30))) {
        $(this).prop("checked", true);
        $(this).addClass('checked');
        $(this).closest('td').addClass('checked');
      }
      
      // Hide checkboxes that are outside of jumping hours
      var t_curr  = new Date("1/1/1970 " + $(this).val().substr(0,2)+":"+$(this).val().substr(3,2));
      var t_start = new Date("1/1/1970 " + start);
      var t_stop  = new Date("1/1/1970 " + stop);
      if (t_curr < t_start) {
        $(this).closest('tr').prev().hide();
      }
      if (t_curr >= t_stop) {
        $(this).closest('tr').hide();
      }
    });
  }



  //----------------------------------------------
  // Add/remove classes when checkbox is clicked
  //----------------------------------------------

  $(document).on('click', '.cb', function () {
    if ( $(this).attr('checked') ) {
      $(this).addClass("checked");
      $(this).closest('td').addClass("checked");
    }
    else {
      $(this).removeClass("checked");
      $(this).closest('td').removeClass("checked");
    }
  });



  //----------------------------------------------
  // Add more time checkboxes
  //----------------------------------------------

  function add_times_f(direction) {
    if (direction == 'before') {
      $('#select_times_tbody tr:visible').eq(0).prev().show();
    }
    else {
      $('#select_times_tbody tr:visible').eq(-1).next().show();
    }
  }


  //----------------------------------------------
  // Remove time checkboxes
  //----------------------------------------------

  function remove_times_f(direction) {
    if (direction == 'before') {
      var tr = $('#select_times_tbody tr:visible').eq(0);
    }
    else {
      var tr = $('#select_times_tbody tr:visible').eq(-1);
    }
    if (! tr.find('td').hasClass('booked')) {
      tr.hide();
    }
  }


  //----------------------------------------------
  // Move selected times 30 minutes earlier/later
  //----------------------------------------------

  function move_times_f(direction) {

    var form       = $('#times :checkbox');
    var check_next = false;

    // Loop direction
    if (direction == 'earlier') {
      form.reverse().each( function() { sub_move_times_f($(this).attr('id')); } );
    }
    else if (direction == 'later') {
      form.each( function() { sub_move_times_f($(this).attr('id')); } );
    }

    // The job - set/unset checkbox
    function sub_move_times_f(id) {
      if ($('#'+id).attr('checked')) {
        if (check_next == false) {
          $('#'+id).prop("checked", false);
          $('#'+id).removeClass("checked");
          $('#'+id).closest('td').removeClass("checked");
        }
        check_next = true;
      }
      else {
        if (check_next == true) {
          $('#'+id).prop("checked", true);
          $('#'+id).addClass("checked");
          $('#'+id).closest('td').addClass("checked");
        }
        check_next = false;
      }
    }
  }



  //----------------------------------------------
  // Submit
  //----------------------------------------------

  function submit_f(how) {

    // Create the query string from all input fields
    var date = $('#form_date').val();
    var qstr = "";
    $('#times input').each(function() {
        // Only include checked and visible checkboxes
        if ($(this).is(':checkbox')) {
          if ($(this).is(':visible')) {
            if ($(this).attr('checked')) {
              qstr += "&" + $(this).attr('id') +"="+ $(this).val().substr(0,2)+":"+$(this).val().substr(3,2);
            }
          }
        }
        else {
          qstr += "&" + $(this).attr('id') +"="+ $(this).val();
        }
    });


    // Insert data into database
    if (how == 'new') {
      var status = ajax_f('new_pilot_booking', qstr);
    }
    else if (how == 'update') {
      var status = ajax_f('update_pilot_booking', qstr);
    }

    // When done, Update calendar.
    if (status) {
      $('#step_2').hide();                       // Hide step 2
      $('#step_3').hide();                       // Hide step 3
      ajax_f('get_event_days', '&date='+date);   // Update calendar
    }
    else {
      alert("Något gick fel! \n\n" + status);
    }

  }




//************************************************************************************
// Tooltips
//************************************************************************************


  //---------------------
  // Step 2
  //---------------------

  var tip_remove_time    = "Ta bort tid";
  var tip_no_remove_time = "Tiden är bokad.<br>Boka om tandemet först.";

  //---------------------
  // Step 3
  //---------------------

  var tip_more_before    = "Fler tidigare tider.";
  var tip_less_before    = "Färre tidiga tider.";
  var tip_move_forward   = "Flytta fram alla tider 30 minuter.";
  var tip_move_back      = "Flytta bak alla tider 30 minuter.";
  var tip_more_after     = "Fler senare tider.";
  var tip_less_after     = "Färre sena tider.";
  var tip_interval       = "Ändra tid mellan hopp.";
