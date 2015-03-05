//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


var highlighted_dates = {};

//************************************************************************************
// AJAX (Get)
//************************************************************************************

  // Add ajax animation block
  $(document).ready(function() {
    ajax_anim();
  });

  function ajax_f(action, qstr, cb) {

    // Ajax Request variable by browser
    ajax_request_f();

    // Create a function that will receive data sent from the server.
    // When data is fetch another function is called depending on what we want to do.
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {

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
    ajax_f('get_event_days', '', function(response) {
      highlight_f();
    });
  });


  function date2iso(d) {
    var fd = d.getFullYear() + '-';
    if (d.getMonth() < 10) { fd += "0"; }
    fd += (d.getMonth()+1) + '-';
    if (d.getDate() < 10) { fd += "0"; }
    fd += d.getDate();
    return fd;
  }

  //-------------------------------------------------------------------------
  // Highlight dates
  //-------------------------------------------------------------------------

  function highlight_f(date, event_dates) {
    var year = new Date().getFullYear();
    // Highlight scheduled dates
    $('.cal_cont').each(function(idx, calendar) {
      cal_draw_f(calendar, false, new Date(year, idx, 1),
        select_handler_f, function(date) {
          var iso = date2iso(date);
          if (highlighted_dates[iso] == true) {
            return 'hl_outer_green';
          }
        }, function(date) {
          ajax_f('get_event_days', '&date=' + date2iso(date), function(response) {
            highlight_f(date, response);
          });
        });
    });
  }


  //-------------------------------------------------------------------------
  // When a date is selected, do this
  //-------------------------------------------------------------------------
  function select_handler_f(d) {
    var iso = date2iso(d);

    var select = !(highlighted_dates[iso] == true);
    highlighted_dates[iso] = select;

    if (select) {
      $('.sel_date').addClass('hl_outer_green');
    } else {
      $('.sel_date').removeClass('hl_outer_green');
    }
  }

  function select_dow(dow) {
    var year = new Date().getFullYear();

    var select = true;
    var decided = false;

    var from = document.getElementById('month-from');
    var to = document.getElementById('month-to');

    for (var month = from.value; month < to.value; month++) {
      for (var day = 1; day < 32; day++) {
        var d = new Date(year, month, day);
        if (dow.indexOf(d.getDay()) > -1) {
          var iso = date2iso(d);

          if (!decided) {
            if (iso in highlighted_dates) {
              select = !highlighted_dates[iso];
            }
            decided = true;
          }

          highlighted_dates[iso] = select;
        }
      }
    }
    highlight_f();
  }
