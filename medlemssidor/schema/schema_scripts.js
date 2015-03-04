//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************



//************************************************************************************
// AJAX: get calendar events from database
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
    // When data is fetch another function is called depending on what we want to do.
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {

        // Stop loading animation
        $('#spinner').trigger('ajaxStop');

        // Continue
        if (action == 'get_event_days') { highlight_f(ajaxRequest.responseText); }   // Draw the calendar with days with events highlighted
        if (action == 'list_day')       { list_day_f(ajaxRequest.responseText);  }   // List info for the current day.
      }
    }

    // Now get the value from user and pass it to server script
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/schema/schema_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'jumped') {
      return(ajaxRequest.responseText);
    }
  }


//------------------------------------------------------------------------------------
// Print
//------------------------------------------------------------------------------------

function print_f() {
  $('#wrapper').printElement({printMode:'popup', leaveOpen:true,});
}


//************************************************************************************
// Step 1.
//************************************************************************************

  //------------------------------------------------------------------------------------
  // Inititialize the calendar when page is loaded.
  //------------------------------------------------------------------------------------

  $(document).ready(function(){
    cal_draw_f({sel_cur : true});
  });


  function cal_user_nav_f(date) { 
    ajax_f('get_event_days', '&date='+date);
  }


  // Highlight dates with events, different sets of dates are separated by '|'
  function highlight_f(event_dates) { 
    dates       = event_dates.split('|'); 
    if (dates[0] != "") {cal_highlight_f(dates[0], 'hl_outer_green');  } // Jumping
    if (dates[1] != "") {cal_highlight_f(dates[1], 'hl_inner_orange'); } // Tandem planned
    if (dates[2] != "") {cal_highlight_f(dates[2], 'hl_inner_bold');   } // Tandem booked
  }


  function select_handler_f(date) { 
    date = date.split('_')[1];    // Date also contains some other stuff. remove it
    ajax_f('list_day', '&date='+date);
  }



//************************************************************************************
// Step 2.
//************************************************************************************


  //------------------------------------------------------------------
  // This function creates an html table with todays info (excl. tandems).
  // The result will be presented in "<div id='day_info'>".
  //------------------------------------------------------------------

  function list_day_f(rows) {

    /* --- General info --- */

    // Diffrerent data sets are separated by '|'.
    schema        = rows.split('|'); 
    datum         = schema[0];
    hopp_tider    = schema[1];
    tandems       = schema[2];
    hopp_schema   = schema[3].split('~');
    tandem_schema = schema[4];


    // Date, time
    var html_datum = "<li><div class='i_c1'>Datum:</div><div class='i_c2'>" + datum + "</div></li>";

    var myhash     = eval( "mkhash(" + hopp_tider + ")" ); 
    var tid_start  = myhash['tid_start'];
    var tid_stop   = myhash['tid_stop'];

    if ((tid_start != undefined) && (tid_stop != undefined)) {
      var html_tider = "<li><div class='i_c1'>Hopptider:</div><div class='i_c2'>" + tid_start + " - " + tid_stop + "</div></li>";
    } 
    else {
      var html_tider = "<li><div class='i_c1'>Hopptider:</div><div class='i_c2'>Ingen hoppning</div></li>";
    }

    // Nr of tandems
    var nrTandemsHash   = eval( "mkhash(" + tandems + ")" ); 
    var nr_tandems      = nrTandemsHash['nr_tandems'] > 0 ? nrTandemsHash['nr_tandems'] : "-";
    var html_nr_tandems = "<li><div class='i_c1'>Antal tandem:</div><div class='i_c2'>" + nr_tandems + "</div></li>";


    // Personnel
    var Arr    = new Array(7);

    Arr[0]     = new Array(); // HL
    Arr[1]     = new Array(); // Manifest
    Arr[2]     = new Array(); // Pilot
    Arr[3]     = new Array(); // HM
    Arr[4]     = new Array(); // AFF
    Arr[5]     = new Array(); // Tandempilot
    Arr[6]     = new Array(); // Tandemfotograf


    for (row in hopp_schema) {
      var myhash = eval( "mkhash(" + hopp_schema[row] + ")" ); 
      switch (myhash['type']) {
        case 'hl':          Arr[0].push(new Array("HL",          myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        case 'manifest':    Arr[1].push(new Array("Manifest",    myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        case 'pilot':       Arr[2].push(new Array("Pilot",       myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        case 'hm':          Arr[3].push(new Array("HM",          myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        case 'aff':         Arr[4].push(new Array("AFF",         myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        case 'tandem_p':    Arr[5].push(new Array("Tandempilot", myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        case 'tandem_f':    Arr[6].push(new Array("Tandemfoto",  myhash['fornamn'] + " " + myhash['efternamn'],myhash['tid_start'],myhash['tid_stop'])); break;
        default: break;
      }
    }



    // Create a html array and intialize it to ""
    var html = new Array(7);
    for (var i=html.length-1; i >= 0; --i) html[i] = "";

    // 
    for (i in Arr) {
      for (j in Arr[i]) {
        if (Arr[i].length == 1) {
          html[i] += "<li><div class='i_c1'>" + Arr[i][j][0] + ":</div>";
        }
        else {
          html[i] += "<li><div class='i_c1'>" + Arr[i][j][0] + " " + (parseInt(j) + parseInt(1)) + ":</div>";
        }
        html[i] += "<div class='i_c2'>" + Arr[i][j][1] + "</div>";

        // Non standard working hours
        if ((Arr[i][j][2] != tid_start) || (Arr[i][j][3] != tid_stop)) {
          if ((i != 5) && (i != 6)) { // Not for tandem
            html[i] += "<div class='i_c3 '>(" + Arr[i][j][2] + " - " + Arr[i][j][3] + ")</div>";
          }
        }
        html[i] += "</li>";
      }
    }

    // Default text if no one is scheduled
    if (html[0] == "") html[0] = "<li><div class='i_c1'>HL:</div>          <div class='i_c2'>-</div></li>";
    if (html[1] == "") html[1] = "<li><div class='i_c1'>Manifest:</div>    <div class='i_c2'>-</div></li>";
    if (html[2] == "") html[2] = "<li><div class='i_c1'>Pilot:</div>       <div class='i_c2'>-</div></li>";
    if (html[3] == "") html[3] = "<li><div class='i_c1'>HM:</div>          <div class='i_c2'>-</div></li>";
    if (html[4] == "") html[4] = "<li><div class='i_c1'>AFF:</div>         <div class='i_c2'>-</div></li>";
    if (html[5] == "") html[5] = "<li><div class='i_c1'>Tandempilot:</div> <div class='i_c2'>-</div></li>";
    if (html[6] == "") html[6] = "<li><div class='i_c1'>Tandemfoto:</div>  <div class='i_c2'>-</div></li>";

    // Write to html
    $('#day_info_ul').html(html_datum);
    $('#day_info_ul').append(html_tider);
    $('#day_info_ul').append("<li>&nbsp;</li>");
    $('#day_info_ul').append(html[0]);
    $('#day_info_ul').append(html[1]);
    $('#day_info_ul').append(html[2]);
    $('#day_info_ul').append(html[3]);
    $('#day_info_ul').append(html[4]);
    $('#day_info_ul').append(html[5]);
    $('#day_info_ul').append(html[6]);
    $('#day_info_ul').append(html_nr_tandems);

    // Fix some widths in order to have consistent wrapping
    $('.i_c2').css('width', $('.i_c2').maxWidth());
    var padding_left  = $('#day_info').css('padding-left').replace("px", "");
    var padding_right = $('#day_info').css('padding-right').replace("px", "");
    $('#day_info').css('max-width',$('.i_c1').outerWidth() + $('.i_c2').outerWidth() + $('.i_c3').outerWidth() + parseInt(padding_left) + parseInt(padding_right) +2);

    // Set min-height equal to calendar
    $('#day_info').css('min-height', $('#calendar').outerHeight());

    /* --- Tandem list --- */

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash       = eval( "mkhash(" + tandem_schema + ")" ); 
    var html_tandem  = "";

    for (var i=1; i<200; i++) {    // 200 doesn't mean anything, it's just a large number

      // Break out of loop when no more hits
      if (typeof myhash['tid_' + i] == 'undefined') {
        break;
      }

      var tid                = myhash['tid_' + i];
      var p_fornamn          = myhash['p_fornamn_' + i];
      var p_efternamn        = myhash['p_efternamn_' + i];
      var f_fornamn          = myhash['f_fornamn_' + i];
      var f_efternamn        = myhash['f_efternamn_' + i];
      var b_ovrigt           = myhash['b_ovrigt_' + i];
      var pax_id             = myhash['pax_id_' + i];
      var pax_fornamn        = myhash['pax_fornamn_' + i];
      var pax_efternamn      = myhash['pax_efternamn_' + i];
      var pax_adress_1       = myhash['pax_adress_1_' + i];
      var pax_adress_2       = myhash['pax_adress_2_' + i];
      var pax_postnummer     = myhash['pax_postnummer_' + i];
      var pax_ort            = myhash['pax_ort_' + i];
      var pax_telefon        = myhash['pax_telefon_' + i];
      var pax_email          = myhash['pax_email_' + i];
      var pax_vikt           = myhash['pax_vikt_' + i];
      var pax_langd          = myhash['pax_langd_' + i];
      var kontakt_fornamn    = myhash['kontakt_fornamn_' + i];
      var kontakt_efternamn  = myhash['kontakt_efternamn_' + i];
      var kontakt_adress_1   = myhash['kontakt_adress_1_' + i];
      var kontakt_adress_2   = myhash['kontakt_adress_2_' + i];
      var kontakt_postnummer = myhash['kontakt_postnummer_' + i];
      var kontakt_ort        = myhash['kontakt_ort_' + i];
      var kontakt_telefon    = myhash['kontakt_telefon_' + i];
      var kontakt_email      = myhash['kontakt_email_' + i];
      var pax_ovrigt         = myhash['pax_ovrigt_' + i];

      // Create the html info table.
      html_tandem  += "  \
        <li class='tandem_head'> \
          <div class='ch'> \
            <div class='left    c1h'><span>#"+ i +":</span></div> \
            <div class='left    c2h'><span>"+ tid +"</span></div> \
          </div> \
          <div class='ch'> \
            <div class='left    c1h'><span>Pilot:</span></div> \
            <div class='left    c2h'><span>"+ p_fornamn +" "+ p_efternamn +"</span></div> \
          </div> \
          <div class='ch'> \
            <div class='left    c1h'><span>Fotograf:</span></div> \
            <div class='left    c2h'><span>"+ f_fornamn +" "+ f_efternamn +"</span></div> \
          </div> \
          <div class='ch'> \
            <div class='left    c1h'><span>Bokare:</span></div> \
            <div class='left    c2h'><span>yada</span></div> \
          </div> \
        </li> \
        <li class='tandem_body'> \
          <div class='left cr c1'><span>Pax:</span></div> \
          <div class='left    c2'><span>" + pax_fornamn + " " + pax_efternamn     + ", " + pax_langd + "/" + pax_vikt + "</span></div> \
          <div class='left cr c1'><span>Kontakt:</span></div> \
          <div class='left    c2'><span>" + kontakt_fornamn + " " + kontakt_efternamn + ", " + kontakt_adress_1 + ", " + kontakt_adress_2 +  ", " + kontakt_postnummer + " " + kontakt_ort + ", " + kontakt_telefon + ", " + kontakt_email + "</span></div> \
          <div class='left cr c1'><span>Betalning:</span></div> \
          <div class='left    c2'><span>Yada yadaYada yada Yada yada Yada yada Yada yada Yada yada</span></div>";
      if (pax_ovrigt != "") {
        html_tandem  += "  \
          <div class='left cr c1'><span>Övrigt (pax):</span></div>      \
          <div class='left    c2'><span>" + pax_ovrigt + "</span></div>";
      }
      if (b_ovrigt != "") {
        html_tandem  += "  \
          <div class='left cr c1'><span>Övrigt (bokning):</span></div> \
          <div class='left    c2'><span>" + b_ovrigt + "</span></div>";
      }
      html_tandem  += "</li>";

      
      html_tandem  += "<li class='tandem_foot'>";
      html_tandem  += "  <div class='left cr c1'><span>Avrapportering:</span></div> ";
      html_tandem  += "  <div class='left    c2'>";
      html_tandem  += "    <div class='left c3'><input type='checkbox' style='border: 1px #999 solid' name='jumped_cb' id='jumped_cb' onclick=\"jumped_f('"+ pax_fornamn +" "+ pax_efternamn +"');\" value=''/>Hoppat</div>";
      html_tandem  += "    <div class='left c3'><a href='"+CCM_REL+"/medlemssidor/tandembokning/ny_bokning/?rebook_id="+pax_id+"'>Boka om</a></div>";
      html_tandem  += "  </div>";
      html_tandem  += "</li>";
        

    }

    // Put the html into appropriate divs.
    if (html_tandem != "") {
      $('#tandem_info').show();
      $('#tandem_info_list').html(html_tandem);
      resize_f();
    }
    else {
      $('#tandem_info').hide();
      $('#tandem_info_list').html('');
    }
  };





  //------------------------------------------------------------------
  // 
  //------------------------------------------------------------------

  function jumped_f(name) {
    $('#overlay').show();
    $('#jumped_popup').popShow();
    $('#jumped_popup').popCenter();
    $('#jumped_name').html(name);
  }

  function jumped_confirm_f(id) {
    var status = ajax_f('jumped', '&id='+id);
    $('#overlay').hide();
    $('#jumped_popup').popHide();
    //alert(status);
  }




  //------------------------------------------------------------------
  // Take care of some alignment issues
  //------------------------------------------------------------------

  function resize_f() {

    if ($('#tandem_info').is(':visible')) {

      /* Tandem head */
      var height_x1 = parseInt($('.tandem_head').css('line-height').replace('px','')) + 
                      parseInt($('.tandem_head').css('padding-top').replace('px','')) +
                      parseInt($('.tandem_head').css('padding-bottom').replace('px','')) +
                      parseInt($('.tandem_head').css('border-top').replace('px','')) + 
                      parseInt($('.tandem_head').css('border-bottom').replace('px',''));

      var height_x2 = parseInt($('.tandem_head').css('line-height').replace('px','')) *2 + 
                      parseInt($('.tandem_head').css('padding-top').replace('px','')) +
                      parseInt($('.tandem_head').css('padding-bottom').replace('px','')) +
                      parseInt($('.tandem_head').css('border-top').replace('px','')) + 
                      parseInt($('.tandem_head').css('border-bottom').replace('px',''));

      $('.tandem .c1h').css('width', 'auto');
      $('.tandem .ch').css('clear', 'none');
      $('.tandem .c2h').css('clear', 'none');

      for (var i = 0; i <= 1; i++) {
        
        // 2x line-height
        if ($('.tandem_head').height() > height_x2) {
          $('.tandem .ch').css('clear', 'both');
          $('.tandem .c1h').css('width', '60px');
          $('.tandem .c2h').css('clear', 'none');
        }
        // 1x line-height
        else if ($('.tandem_head').height() > height_x1) {
          $('.tandem .c2h').css('clear', 'both');
        }
      }
      
      /* Tandem body */
      if ($('.tandem .c2').css('clear') == 'both') {
        $('.tandem .c2').css('width', $('.tandem_body').width());
      }
      else {
        $('.tandem .c2').css('width', $('.tandem_body').width() - $('.tandem .c1').width());
      }
    }
  }

  $(document).ready(function(){ resize_f() });
  $(window).resize(function() { resize_f() });

    


