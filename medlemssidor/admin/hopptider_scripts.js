 
//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


//----------------------------------------------
// AJAX
//----------------------------------------------
 
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

      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?C5_URL=" + CCM_REL + "&action=" + action + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/admin/hopptider_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'get_times') {
      return(ajaxRequest.responseText);
    }
    if (action == 'set_times') {
      return(ajaxRequest.responseText);
    }

  }



//----------------------------------------------
// Get times from database
//----------------------------------------------

  $(document).ready(function() {
      get_times_f();
  });


  function get_times_f() {

    // Get times from database
    var times = ajax_f('get_times', '');

    // Ajax fetches the result as hash. mkhash parses the hash.
    var timeHash  = eval( "mkhash(" + times + ")" ); 

    // Fill form with times
    $('#tid_start_1').val(timeHash['start_1']);
    $('#tid_stop_1').val(timeHash['stop_1']);

    $('#tid_start_2').val(timeHash['start_2']);
    $('#tid_stop_2').val(timeHash['stop_2']);

    $('#tid_start_3').val(timeHash['start_3']);
    $('#tid_stop_3').val(timeHash['stop_3']);

    $('#tid_start_4').val(timeHash['start_4']);
    $('#tid_stop_4').val(timeHash['stop_4']);

    $('#tid_start_5').val(timeHash['start_5']);
    $('#tid_stop_5').val(timeHash['stop_5']);

    $('#tid_start_6').val(timeHash['start_6']);
    $('#tid_stop_6').val(timeHash['stop_6']);

    $('#tid_start_7').val(timeHash['start_7']);
    $('#tid_stop_7').val(timeHash['stop_7']);

  }



//----------------------------------------------
// Reformat time string if entered without colon
//----------------------------------------------

  // Autoformat time
  $(document).on('focusout', '.time', function() {

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


//----------------------------------------------
// Enable/disable button
//----------------------------------------------

  $(document).ready(function() {
    $('#submit').attr('disabled','disabled');
  });

  $(document).on('change', '.time', function(event) {
    $('#submit').removeAttr('disabled');
  });


//----------------------------------------------
// Insert updated times into database
//----------------------------------------------

  function submit_f(form_id) {

    // Create the query string from all input fields
    var qstr = "";
    $('#' + form_id + ' input:text').each(function() {
        qstr += "&" + $(this).attr('id') +"="+ $(this).val();
    });

    // Insert data into database
    ajax_f('set_times', qstr);

    // Reload times from database
    get_times_f();

    // Disable submit button
    $('#submit').attr('disabled','disabled');
  }
