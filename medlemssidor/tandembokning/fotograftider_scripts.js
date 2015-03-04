//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


//************************************************************************************
// Global varables
//************************************************************************************

  var gl_photo        = [];
  var gl_photo_booked = [];
  var gl_photo_id     = "";
  var gl_type         = "";
  var gl_start        = "";
  var gl_stop         = "";


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
        if (action == 'get_event_days')   { highlight_f(ajaxRequest.responseText);}                 // Draw calendar with highlights
        if (action == 'list_day')         { list_day_f(params['date'], ajaxRequest.responseText);}  // 
        if (action == 'delete_time_info') { delete_time_info_f(ajaxRequest.responseText);}          // 
        if (action == 'delete_time')      { }                                                       // 
      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/tandembokning/fotograftider_db.php" + queryString, false);
    ajaxRequest.send(null); 


    // Caller specific stuff
    if (action == 'new_photo_booking') {
      return(ajaxRequest.responseText);
    }
    if (action == 'update_photo_booking') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'jump_hours') {
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
    cal_draw_f();
  });

  function cal_user_nav_f(date) { 
    ajax_f('get_event_days', '&date='+date);
  }

  function highlight_f(event_dates) { 

    // Highlight dates with events, different sets of dates are separated by ':'
    var dates = event_dates.split(':'); 

    if (dates[0] != "") {cal_highlight_f(dates[0], 'hl_outer_green');  } // jumping
    if (dates[1] != "") {cal_highlight_f(dates[1], 'hl_inner_bold');   } // pilot
    if (dates[2] != "") {cal_highlight_f(dates[2], 'hl_inner_orange'); } // photographer
  }


  function select_handler_f(date) { 

    // Date also contains some other stuff. remove it
    var date = date.split('_')[1];

    // Handle visibility of divs
    $('#step_3').hide();
    $('#step_2').show();

    // Call ajax function to show who is booked for current day
    ajax_f('list_day', '&date='+date, {'date' : date});

    // Resize function
    resize_callbacks.fire();

    // Reset name dropdown
    $('#name_dropdown').val('0');

  }



//************************************************************************************
// Step 2.
//************************************************************************************

  function list_day_f(date, row) {

    // Separate ajax response
    var schema            = row.split('|'); 
    var schema_hoppning   = schema[0];
    var schema_fotografer = schema[1];
    var schema_bokningar  = schema[2];

    //----------------------------------------------
    // Schedule warning
    //----------------------------------------------

    // Ajax fetches the result as hash. mkhash parses the hash.
    var hoppHash = eval( "mkhash(" + schema_hoppning + ")" ); 
    
    // Assert warning if no jumping is planned for selected day
    if (hoppHash['hoppning_start'] == '') {
      $('#schema_varning').html("<p>! Ingen hoppning shemalagd den här dagen. Du kan boka in fotograf ändå men se till att fixa pilot och hoppledare</p>");
    }
    else {
      $('#schema_varning').html("");
    }

    //----------------------------------------------
    // Put selected dates etc in hidden form field
    //----------------------------------------------

    var d = new Date(date);
    $('#form_date').val(date);
    $('#form_wday').val(d.getDay());
    $('#form_start').val(hoppHash['hoppning_start']);
    $('#form_stop').val(hoppHash['hoppning_stop']);


    //----------------------------------------------
    // List scheduled photographers for current day
    //----------------------------------------------

    // Creatade html string to insert into div.
    var html_table_1 = "<p><b>Fotografer</b></p> ";

    // If someone is scheduled 'schema_fotografer' will be defined
    if (schema_fotografer != '') {

      // Ajax fetches the result as hash. mkhash parses the hash.
      var fotoHash = eval( "mkhash(" + schema_fotografer + ")" ); 

      // Already scheduled photographers
      html_table_1 += " <table class='table_list'> \
                          <tr> \
                            <th>Ta bort</th> \
                            <th>Från</th> \
                            <th>Till</th> \
                            <th>Namn</th> \
                          </tr>";


      // Reset global variables
      gl_photo        = [];
      gl_photo_booked = [];

      // Loop through each photographer
      for (var i=1; i<200; i++) {    // 200 doesn't mean anything, it's just a large number
        
        // Break out of loop when no more hits
        if (typeof fotoHash['id_' + i] == 'undefined') {
          break;
        }
  
        // Some stuff if current time is booked with a pax
        html_table_1 += "<tr>";
        if (fotoHash['bokad_' + i] != 0) {
          html_table_1 += "<td class='list' align='center' onmouseover='Tip(tip_no_delete);' onmouseout='UnTip();'><span class='icon-remove icon-large icon-st-grey icon-st-shadow'></span></td>";
        }
        else {
          html_table_1 += "<td class='list' align='center' onmouseover='Tip(tip_delete);' onmouseout='UnTip();'><span class='icon-remove icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"ajax_f('delete_time_info','&id=" + fotoHash['id_' + i] + "');\"></span></td>";
        }
        html_table_1 += "  <td class='list'>" + fotoHash['tid_start_' + i] + "</td>";
        html_table_1 += "  <td class='list'>" + fotoHash['tid_stop_'  + i] + "</td>";
        html_table_1 += "  <td class='list'>" + fotoHash['fotograf_'  + i] + "</td>";
        html_table_1 += "</tr>";

        if (fotoHash['kommentar_' + i] != '') {
          html_table_1 += "<tr><td class='list'></td><td class='list' colspan='3'>" + fotoHash['kommentar_' + i] + "</td></tr>";
        }

        // Assign to global variable, used in step 3
        gl_photo.push(fotoHash['fotograf_id_' + i] +","+ fotoHash['tid_start_'  + i] +","+ fotoHash['tid_stop_'  + i] +","+ fotoHash['kommentar_'  + i]);
      }
      html_table_1 += "</table>";

      // Insert table in div
      $('#booked_photographers').html(html_table_1);

    }        
    // If no photographer is booked on current date
    else {
      html_table_1 += "Inga fotografer schemalagda";
      $('#booked_photographers').html(html_table_1);
    }



    //----------------------------------------------
    // List booked times for photographers
    //----------------------------------------------

    // Creatade html string to insert into div.
    var html_table_2 = "<p><b>Bokade tider</b></p>";

    // If someone is booked 'schema_bokningar' will not be undefined
    if (schema_bokningar != '') {

      // Ajax fetches the result as hash. mkhash parses the hash.
      var boknHash = eval( "mkhash(" + schema_bokningar + ")" ); 

      // Already booked photographers
      html_table_2 += "<table class='table_list'> \
                         <tr> \
                           <th>Tid</th> \
                           <th>Namn</th> \
                           <th>Video</th> \
                           <th>Foto</th> \
                         </tr>";


      for (var i=1; i<200; i++) {    // 200 doesn't mean anything, it's just a large number
        
        // Break out of loop when no more hits
        if (typeof boknHash['bokn_id_' + i] == 'undefined') {
          break;
        }
  
        html_table_2 += "<tr>";
        html_table_2 += "  <td class='list'>" + boknHash['tid_'      + i] + "</td>";
        html_table_2 += "  <td class='list'>" + boknHash['fotograf_' + i] + "</td>";
        if (boknHash['video_' + i] != 0) {
          html_table_2 += "<td class='list' align='center'><span class='icon-ok icon-large icon-st-lightblue icon-st-shadow'></span></td>";
        }
        else {
          html_table_2 += "  <td class='list'></td>";
        }
        if (boknHash['foto_' + i] != 0) {
          html_table_2 += "<td class='list' align='center'><span class='icon-ok icon-large icon-st-lightblue icon-st-shadow'></span></td>";
        }
        else {
          html_table_2 += "  <td class='list'></td>";
        }
        html_table_2 += "</tr>";

        // Assign id and time to global variable
        gl_photo_booked.push(boknHash['foto_id_' + i] +","+ boknHash['tid_'  + i]);
      }
      html_table_2 += "</table>";

      // Insert table in div
      $('#booked_photographers_times').html(html_table_2);
    }        
    // If no times are booked on current date
    else {
      html_table_2 += "Inga tider bokade";
      $('#booked_photographers_times').html(html_table_2);
    }

  }

  //----------------------------------------------
  // Delete time. Populate popup box
  //----------------------------------------------

  function delete_time_info_f(row) { 

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + row + ")" ); 

    // Show popup
    $('#delete_time_popup, #overlay').popShow();
    $('#delete_time_popup').popCenter();

    // Insert info in delete popup
    $('#delete_f_date').html(myhash['datum']);
    $('#delete_f_time').html(myhash['tid_start'].substr(0,5) + " - " + myhash['tid_stop'].substr(0,5));
    $('#delete_f_name').html(myhash['fotograf']);
    $('#delete_f_id').val(myhash['id']);
    
  }

  function delete_time_f(id, date) {

    // Delete time from database
    ajax_f('delete_time', "&id="+id);

    // Hide the popup
    $('#delete_time_popup, #overlay').popHide();

    // Update calendar
    ajax_f('get_event_days', '&date='+date);

    // Select same date again
    $('#outer_'+date).trigger('click');
  }



//************************************************************************************
// Step 3.
//************************************************************************************

  function list_times_f(photographer) {

    // Choose button, 'lägg till' or 'uppdatera'
    // Store type in global variable 'gl_type'
    var match = false
    $.each(gl_photo, function(i) {
        var re = new RegExp("^"+photographer+".*");
        if (re.test(gl_photo[i])) {
          match = true;
        }
    });
    if (match) {
      gl_type = "update";
      $('#new_photo_submit').hide();
      $('#update_photo_submit').show();
    }
    else {
      gl_type = "new";
      $('#new_photo_submit').show();
      $('#update_photo_submit').hide();
    }

    // Show step 3 and resize
    $('#step_3').show();
    resize_callbacks.fire();

    // Put selected photographer in global variable
    gl_photo_id = photographer;

    // Jumping hours
    // If this is an update of a previously schedule photographer, use old values.
    // Else if jumping is scheduled, use those times.
    // Else use default times
    var start;
    var stop;
    var comment;
    if (gl_type == 'update') {
      $.each(gl_photo, function(i) {
        var org_times = gl_photo[i].split(','); 
        if (org_times[0] == gl_photo_id) {
          start   = org_times[1];
          stop    = org_times[2];
          comment = org_times[3];
        }
      });
    }
    else if ($('#form_start').val() != '') {
      start = $('#form_start').val().substr(0,5);
      stop  = $('#form_stop').val().substr(0,5);
    }
    else {
      // Get date from hidden form and reformat (mo=1, su=7)
      var wd = $('#form_wday').val();
      wd = (wd == 0) ? 7 : wd;
      // Get default jump hours from database
      var start_stop = ajax_f('jump_hours', '&wd='+wd);
      var myhash     = eval( "mkhash(" + start_stop + ")" ); 
          start      = myhash['start_time'];
          stop       = myhash['stop_time'];
    }
    $('#start_dropdown').val(start);
    $('#stop_dropdown').val(stop);
    $('#form_ovrigt').val(comment);

    // Save original form values in global variables (to enable/disable button)
    gl_start_org   = start;
    gl_stop_org    = stop
    gl_comment_org = comment

  }


  //------------------------------------------------
  // Check so that time isn't changed if tandem 
  // is booked. Booked times are stored in the 
  // global variable 'gl_photo_booked'
  //------------------------------------------------

  $(document).on('change', '#start_dropdown, #stop_dropdown', function(event) {
    var possible = true;
    $.each(gl_photo_booked, function(i) {
      var booked_times = gl_photo_booked[i].split(','); 
      if (gl_photo_id == booked_times[0]) {
        if ($('#start_dropdown').val().replace(":", "") > booked_times[1].replace(":", "")) {
          possible = false;
        }
        if ($('#stop_dropdown').val().replace(":", "") < booked_times[1].replace(":", "")) {
          possible = false;
        }
      }
    });
    if (!possible) {
      $('#illegal_time_popup, #overlay').popShow();
      $('#illegal_time_popup').popCenter();
    }
  });


  function revert_times_f() {
    $('#illegal_time_popup, #overlay').popHide();
    $.each(gl_photo, function(i) {
      var org_times = gl_photo[i].split(','); 
      if (org_times[0] == gl_photo_id) {
        $('#start_dropdown').val(org_times[1]);
        $('#stop_dropdown').val(org_times[2]);
      }
    });
  }


  //----------------------------------------------
  // Enable/disable button
  //----------------------------------------------

  $(document).ready(function() {
    $('#update_photo_submit').attr('disabled','disabled');
  });

  $(document).on('change', '#start_dropdown, #stop_dropdown, #form_ovrigt', function(event) {
    if (($('#start_dropdown').val() == gl_start_org) && ($('#stop_dropdown').val() == gl_stop_org) && ($('#form_ovrigt').val() == gl_comment_org)) {
      $('#update_photo_submit').attr('disabled','disabled');
    }
    else {
      $('#update_photo_submit').removeAttr('disabled');
    }
  });




  //----------------------------------------------
  // Submit
  //----------------------------------------------

  function submit_f(id, how) {

    // Create the query string from all input fields
    var qstr  = "&date="+$('#form_date').val();
        qstr += "&tid_start="+$('#start_dropdown').val();
        qstr += "&tid_stop="+$('#stop_dropdown').val();
        qstr += "&kommentar="+$('#form_ovrigt').val();
        qstr += "&id_fotograf="+gl_photo_id;

    // Insert data into database
    if (how == 'new') {
      var status = ajax_f('new_photo_booking', qstr);
    }
    else if (how == 'update') {
      var status = ajax_f('update_photo_booking', qstr);
    }

    // When done, Update calendar.
    if (status == 'ok') {
      $('#step_2').hide();                           // Hide step 2
      $('#step_3').hide();                           // Hide step 3
      ajax_get_f('get_event_days', '&date='+date);   // Update calendar
    }
    else {
      alert("Något gick fel! \n\n" + status);
    }

  }




//************************************************************************************
// Tooltips
//************************************************************************************

  var tip_delete    = "Ta bort";
  var tip_no_delete = "Tandem är bokat.<br>Boka om tandemet först.";

