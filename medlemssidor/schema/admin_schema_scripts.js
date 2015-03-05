//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************



//************************************************************************************
// AJAX (Get)
//************************************************************************************

  // Add ajax animation block
  $(document).ready(function() {
    ajax_anim();
  });

  function ajax_f(action, qstr, cb) {

    // Start loading animation
    $('#spinner').trigger('ajaxSend');

    // Ajax Request variable by browser
    ajax_request_f();

    // Create a function that will receive data sent from the server.
    // When data is fetch another function is called depending on what we want to do.
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {

        // Stop loading animation
        $('#spinner').trigger('ajaxStop');

        // Caller specific stuff
        if (cb != undefined) {
          cb(ajaxRequest.responseText);
        }
      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?action=" + action + qstr;
    ajaxRequest.open("GET", "admin_schema_db.php" + queryString, true);
    ajaxRequest.send(null);
  }



//************************************************************************************
// Init
//************************************************************************************

  // Autoformat date
  $(document).on('focusout', '#datum', function() {

    $(this).css('background-color', '#fff');

    var pattern1 = /^(\D*)(\d\d)(\D+)(\d)(\D+)(\d)(\D*)$/;
    var pattern2 = /^(\D*)(\d\d\d\d)(\D+)(\d)(\D+)(\d)(\D*)$/;
    var pattern3 = /^(\D*)(\d\d)(\D*)(\d\d)(\D*)(\d\d)(\D*)$/;
    var pattern4 = /^(\D*)(\d\d\d\d)(\D*)(\d\d)(\D*)(\d\d)(\D*)$/;

    if ( pattern1.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern1, "20$2-0$4-0$6"));
    }
    else if ( pattern2.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern2, "$2-0$4-0$6"));
    }
    else if ( pattern3.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern3, "20$2-$4-$6"));
    }
    else if ( pattern4.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern4, "$2-$4-$6"));
    }
    else if ($(this).val() != "") {
      $(this).css('background-color', '#ffcccc');
    }

  });

  // Autoformat time
  $(document).on('focusout', '#tid_start, #tid_stop', function() {

    $(this).css('background-color', '#fff');

    var pattern1 = /^(\D*)(\d)(\D*)$/;
    var pattern2 = /^(\D*)(\d\d)(\D*)$/;
    var pattern3 = /^(\D*)(\d)(\D*)(\d\d)(\D*)$/;
    var pattern4 = /^(\D*)(\d\d)(\D*)(\d\d)(.*)$/;

    if ( pattern1.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern1, "0$2:00"));
    }
    else if ( pattern2.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern2, "$2:00"));
    }
    else if ( pattern3.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern3, "0$2:$4"));
    }
    else if ( pattern4.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern4, "$2:$4"));
    }
    else if ($(this).val() != "") {
      $(this).css('background-color', '#ffcccc');
    }
  });


//************************************************************************************
// Draw Calendar.
//************************************************************************************


  //-------------------------------------------------------------------------
  // Init
  //-------------------------------------------------------------------------

  $(document).ready(function(){
    // Get names for the autocomplete function
    ajax_f('get_autocomplete', '', function(response) {
      autocomplete_f(response);

      ajax_f('get_event_days', '', function(response) {
        highlight_f(undefined, response);
      });
    });
  });


  //-------------------------------------------------------------------------
  // Highlight dates
  //-------------------------------------------------------------------------

  function highlight_f(date, event_dates) {
    // Highlight scheduled dates
    var calender = document.getElementsByClassName('cal_cont')[0];
    cal_draw_f(calender, true, date, select_handler_f, function(d) {
      var fd = d.getFullYear() + '-';
      if (d.getMonth() < 10) { fd += "0"; }
      fd += (d.getMonth()+1) + '-';
      if (d.getDate() < 10) { fd += "0"; }
      fd += d.getDate();
      if (event_dates.indexOf(fd) != -1) {
        return 'hl_outer_green';
      }
    }, function(date) {
      ajax_f('get_event_days', '&date='+date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate(), function(response) {
        highlight_f(date, response);
      });
    });
  }


  //-------------------------------------------------------------------------
  // When a date is selected, do this
  //-------------------------------------------------------------------------
  function select_handler_f(d) {
    console.log(d);
    var date = d.getFullYear() + '-';
    if (d.getMonth() < 10) { date += "0"; }
    date += (d.getMonth()+1) + '-';
    if (d.getDate() < 10) { date += "0"; }
    date += d.getDate();

    // Show form div
    $('#step_1').hide();
    $('#step_2').show();
    $('#step_3').hide();

    // Reset the form
    $('#new_day_form').each( function() {this.reset()} );

    // Date to form
    $('#datum').val(date);

    // Remove any dynamically added fields (they have class: dyn)
    $('.dyn').reverse().each(function() {
      remove_f($(this).attr('id'));
    });

    // Get info for selected day
    ajax_f('get_day_info', '&date='+date, function(response) {
      fill_info_f(response);
      // Focus first input field
      $('#tid_start').focus();

      // Some alignment stuff
      resize_f();
    });
  }

//************************************************************************************
// Add/remove people
//************************************************************************************

  //---------------------------------------
  // Dubplicate row
  //---------------------------------------

  function duplicate_f(id) {

    var oldLi      = $('#' + id);
    var liInc      = $('#' + id).parent().find('li').length +1;
    var newLiClone = $('#' + id).clone().attr('id', 'li_' + liInc);
    var newLiInp   = newLiClone.find('input');

    // Increment id, name and tabindex on all <input>
    newLiInp.each(function(i) {
      var idNr             = newLiInp[i].id.match(/[0-9]*$/);
      var newIdNr          = parseInt(idNr) + parseInt(1);
      newLiInp[i].id       = newLiInp[i].id.replace(/[0-9]$/, newIdNr);
      newLiInp[i].name     = newLiInp[i].name.replace(/[0-9]$/, newIdNr);
      newLiInp.eq(i).attr('tabindex', $(this).attr('tabindex') +3);
    });

    // Insert the new <li>
    newLiClone.insertAfter('#' + id);

    // Clear input fields
    newLiInp.val('');

    // Add class 'dyn' to all dynamically added content.
    var newLi = $('#li_' + liInc);
    newLi.addClass('dyn');

    // Hide/Remove +/- buttons
    oldLi.find('span:eq(0)').hide();
    oldLi.find('span:eq(1)').hide();
    newLi.find('span:eq(1)').show();

  }


  //---------------------------------------
  // Remove row
  //---------------------------------------

  function remove_f(id) {

    var currLi   = $('#' + id);
    var prevLi   = $('#' + id).prev();
    var prevLi2  = $('#' + id).prev().prev();
    var prevLiId = $('#' + id).prev().attr('id');

    // Show "Add" button in previous <li>
    prevLi.find('span:eq(0)').show();

    // Show "Remove" button in previous <li> unless they are of different type
    if (prevLi2.find('label:eq(0)') != undefined) {
      if (prevLi.find('label:eq(0)').html() == prevLi2.find('label:eq(0)').html()) {
        prevLi.find('span:eq(1)').show();
      }
    }

    // Remove <li>
    currLi.remove();

    // Enable update button
    $('#submit').removeAttr('disabled');
  }

//************************************************************************************
// Fill info when a previously scheduled date is selected
//************************************************************************************

  function fill_info_f(info) {

    // Trim white spaces, new lines etc
    info = info.trim();

    // Day is not scheduled
    if (info == "") {

      // Get date from form and reformat (mo=1, su=7)
      var wd = new Date($('#datum').val()).getDay();
      wd = (wd == 0) ? 7 : wd;

      // Get default jump hours from database
      ajax_f('jump_hours', '&wd='+wd, function(start_stop) {
        var timeHash   = eval( "mkhash(" + start_stop + ")" );

        // Set form values
        $('#tid_start').val(timeHash['start_time']);
        $('#tid_stop').val(timeHash['stop_time']);

        // Set submit button text
        $('#submit').val('Lägg till');

        // Hide remove button
        $('#remove').hide();
      });
    }

    // Day is scheduled
    else {

      // Diffrerent data sets are separated by '|'.
      var schema     = info.split('|');
      var hopp_tider = schema[0];
      var personal   = schema[1];

      // General start/stop times
      var myhash     = eval( "mkhash(" + hopp_tider + ")" );
      var tid_start  = myhash['tid_start'];
      var tid_stop   = myhash['tid_stop'];

      $('#tid_start').val(tid_start);
      $('#tid_stop').val(tid_stop);

      // Individual persons
      if (personal) {
        var hopp_schema = personal.split('~');
        var Arr = new Array(5);

        Arr[0] = new Array(); // HL
        Arr[1] = new Array(); // Manifest
        Arr[2] = new Array(); // Pilot
        Arr[3] = new Array(); // HM
        Arr[4] = new Array(); // AFF

        for (row in hopp_schema) {
          var myhash = eval( "mkhash(" + hopp_schema[row] + ")" );
          switch (myhash['type']) {
            case 'hl':          Arr[0].push(new Array("hl",  myhash['fornamn'] + " " + myhash['efternamn'],myhash['id'],myhash['tid_start'],myhash['tid_stop'])); break;
            case 'manifest':    Arr[1].push(new Array("man", myhash['fornamn'] + " " + myhash['efternamn'],myhash['id'],myhash['tid_start'],myhash['tid_stop'])); break;
            case 'pilot':       Arr[2].push(new Array("pil", myhash['fornamn'] + " " + myhash['efternamn'],myhash['id'],myhash['tid_start'],myhash['tid_stop'])); break;
            case 'hm':          Arr[3].push(new Array("hm",  myhash['fornamn'] + " " + myhash['efternamn'],myhash['id'],myhash['tid_start'],myhash['tid_stop'])); break;
            case 'aff':         Arr[4].push(new Array("aff", myhash['fornamn'] + " " + myhash['efternamn'],myhash['id'],myhash['tid_start'],myhash['tid_stop'])); break;
            default: break;
          }
        }

        for (i in Arr) {  // each type
          for (var l=0; l < Arr[i].length-1; l++) {
            duplicate_f($('#'+Arr[i][0][0] + '_1').closest('li').attr('id'));
          }
          for (j in Arr[i]) {
            var k = parseInt(j) + parseInt(1);
            $('#'+Arr[i][j][0] + '_'           + k).val(Arr[i][j][1]);
            $('#'+Arr[i][j][0] + '_id_'        + k).val(Arr[i][j][2]);
            $('#'+Arr[i][j][0] + '_tid_start_' + k).val(Arr[i][j][3]);
            $('#'+Arr[i][j][0] + '_tid_stop_'  + k).val(Arr[i][j][4]);
          }
        }
      }

      // Set submit button text
      $('#submit').val('Uppdatera');

      // Enable/disable button
      $('#submit').attr('disabled','disabled');
      var form_before = $('#new_day_form').serialize();
      $(document).on('focusout', '#new_day_form :input', function(event) {
        var form_after = $('#new_day_form').serialize();
        if (form_before != form_after) {
          $('#submit').removeAttr('disabled');
        }
      });

      // Show remove button
      $('#remove').show();
    }
  }

//************************************************************************************
// Autocomplete
//************************************************************************************

  //-------------------------------------------------------------------------
  // Create autocomplete variables
  //-------------------------------------------------------------------------

  var hm_names;
  var hl_names;
  var aff_names;
  var man_names;
  var pil_names;

  function autocomplete_f(names) {

    // Separate different types
    myhash = eval( "mkhash(" + names + ")" );

    // Split each type into array
    hl_names  = myhash['hl'].split(",");
    hm_names  = myhash['hm'].split(",");
    aff_names = myhash['aff'].split(",");
    man_names = myhash['man'].split(",");
    pil_names = myhash['pil'].split(",");

    // For each entry, split into key/value
    for (i in hl_names)  { hl_names[i]  = hl_names[i].split("|");  }
    for (i in hm_names)  { hm_names[i]  = hm_names[i].split("|");  }
    for (i in aff_names) { aff_names[i] = aff_names[i].split("|"); }
    for (i in man_names) { man_names[i] = man_names[i].split("|"); }
    for (i in pil_names) { pil_names[i] = pil_names[i].split("|"); }

  }


  //-------------------------------------------------------------------------
  // Fill 'tid_start' and 'tid_stop'
  //-------------------------------------------------------------------------

  $(document).on('focusin', '[data-complete]', function(event) {

    var obj_tid_start = $('#' +this.id.replace(/([0-9]*)$/, 'tid_start_' + '$1'));
    var obj_tid_stop  = $('#' +this.id.replace(/([0-9]*)$/, 'tid_stop_'  + '$1'));

    if (this.value != "") {
      if (obj_tid_start.val() == "") {
        obj_tid_start.val($('#tid_start').val());
      }
      if (obj_tid_stop.val() == "") {
        obj_tid_stop.val($('#tid_stop').val());
      }
    }
    else {
      obj_tid_start.val('');
      obj_tid_stop.val('');
    }

  });



//************************************************************************************
// Reformat time string if entered without colon
//************************************************************************************

  $(document).on('change', '.time', function(event) {
    var org     = $(this).val();
    var pattern = /^.*(\d\d).*(\d\d).*$/;
    if (org.match(pattern)[1] && org.match(pattern)[2]) {
      $(this).val(org.match(pattern)[1] +":"+ org.match(pattern)[2]);
    }
  });



//************************************************************************************
// Alignment. Set witdh of form.
//************************************************************************************

  function resize_f() {

    if ($('#li_9').height() > 40) {
      $('#submit').closest('li').find('div:eq(0)').width('0');
    }
    else {
      $('#submit').closest('li').find('div:eq(0)').width($('#li_9').closest('li').find('div:eq(0)').width());
    }
  }

  $(window).resize(function() { resize_f() });




//************************************************************************************
// Submit form
//************************************************************************************


  //----------------------------------------
  // New day
  //----------------------------------------

  function submit_day_f(id) {

    // Set variables with number of personell
    var obj_pil = $('.pil_id');
    var obj_hl  = $('.hl_id');
    var obj_hm  = $('.hm_id');
    var obj_aff = $('.aff_id');
    var obj_man = $('.man_id');

    var nrof_pil = 0;
    var nrof_hl  = 0;
    var nrof_hm  = 0;
    var nrof_aff = 0;
    var nrof_man = 0;

    obj_pil.each(function() { if ($(this).val() != "") { nrof_pil++; }; });
    obj_hl.each(function()  { if ($(this).val() != "") { nrof_hl++;  }; });
    obj_hm.each(function()  { if ($(this).val() != "") { nrof_hm++;  }; });
    obj_aff.each(function() { if ($(this).val() != "") { nrof_aff++; }; });
    obj_man.each(function() { if ($(this).val() != "") { nrof_man++; }; });

    $('#nrof_pil').val(nrof_pil);
    $('#nrof_hl').val(nrof_hl);
    $('#nrof_hm').val(nrof_hm);
    $('#nrof_aff').val(nrof_aff);
    $('#nrof_man').val(nrof_man);

    // Create the query string from all input fields
    var date = $('#datum').val();
    var qstr = "";
    $('#new_day_form input').each(function() {
        qstr += "&" + $(this).attr('id') +"="+ $(this).val();
    });

    // Insert data into database
    ajax_f('new_day', qstr, function(status) {
      if (status != "ok") {
        alert(status);
      }
      else {
        // Reset the form
        $('#new_day_form').each( function() {this.reset()} );

        // Hide step 2, Show step 3
        $('#step_2').hide();
        $('#step_3').show();

        // Update calendar
        ajax_f('get_event_days', '&date='+date);
      }
    });

  }


  //----------------------------------------
  // Remove day
  //----------------------------------------

  function remove_day_confirm_f(date) {
    $('#del_date').html(date);
    $('#delete_popup, #overlay').popShow();
    $('#delete_popup').popCenter();
  }


  function remove_day_f(date) {
    // Remove day
    ajax_f('remove_day', '&date='+date, function(status) {
      if (status != "ok") {
        alert(status);
      }
      else {

        // Hide popup
        $('#delete_popup, #overlay').popHide();

        // Update calendar
        ajax_f('get_event_days', '&date='+date);

        // Reset the form
        $('#new_day_form').each( function() {this.reset()} );

        // Hide step 2, show step 3
        $('#step_2').hide();
        $('#step_3').show();
      }
    });
  }
