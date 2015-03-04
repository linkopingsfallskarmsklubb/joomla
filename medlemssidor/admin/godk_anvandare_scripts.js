//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


//************************************************************************************
// AJAX
//************************************************************************************

  function ajax_f(action, qstr) {

    // Add ajax animation block
    $(document).ready(function() {
      ajax_anim();
    });

    // Start loading animation
    $('#spinner').trigger('ajaxSend');

    // Ajax Request variable by browser
    ajax_request_f();

    // Create a function that will receive data sent from the server.
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {

        // Stop loading animation
        $('#spinner').trigger('ajaxStop');

        // Continue
        if (action == 'get_overview') { fill_f(ajaxRequest.responseText); }
      }
    }

    // Now get the value from user and pass it to server script
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/index.php/tools/medlemssidor/admin/godk_anvandare_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'approve') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'deny') {
      return(ajaxRequest.responseText);
    }

  }



//------------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------------

$(document).ready(function(){

  // Get user list
  ajax_f('get_overview', '');

  // Some alignment stuff
  resize_f();

});


//------------------------------------------------------------------
// Main content
//------------------------------------------------------------------

function fill_f(row) {

  if (row) {

    // Entries are separated by '|'
    var rows = row.split('|'); 


    // Clear list
    $('#list_ul').html("");


    var html = "";
    for (row in rows) {


      // Ajax fetches the result as hash. mkhash parses the hash.
      var myhash = eval( "mkhash(" + rows[row] + ")" ); 

      //
      if (myhash['pilot'] =='1') {
        var pilot = 'Ja';
      }
      else {
        var pilot = 'Nej';
      }

      //
      if (myhash['comment'] =='') {
        var comment = '-';
      }
      else {
        var comment = myhash['comment'];
      }

      //
      if (myhash['sff_nummer'] =='') {
        var sff_nummer = '-';
      }
      else {
        var sff_nummer = myhash['sff_nummer'];
      }

      // Create html table row
      html += "<li class='entry'> \
                 <div class='details'> \
                  <ul class='clean_list'> \
                    <li><div class='c1'>Namn:</div>          <div class='c2'>" + myhash['firstname']    + " " + myhash['lastname'] + "</div></li> \
                    <li><div class='c1'>Användarnamn:</div>  <div class='c2'>" + myhash['username']     + "</div></li> \
                    <li><div class='c1'>Personnummer:</div>  <div class='c2'>" + myhash['personnummer'] + "</div></li> \
                    <li><div class='c1'>Email:</div>         <div class='c2'>" + myhash['email']        + "</div></li> \
                    <li><div class='c1'>SFF-nummer:</div>    <div class='c2'>" + sff_nummer             + "</div></li> \
                    <li><div class='c1'>Pilot:</div>         <div class='c2'>" + pilot                  + "</div></li> \
                    <li><div class='c1'>Kommentar:</div>     <div class='c2'>" + comment                + "</div></li> \
                  </ul> \
                </div> \
                <div class='buttons'> \
                  <ul class='clean_list'> \
                    <li><input type='button' class='button' value='Godkänn' onclick='submit_f("+myhash['id']+", true)'  /></li> \
                    <li><input type='button' class='button' value='Avslå'   onclick='submit_f("+myhash['id']+", false)' /></li> \
                  </ul> \
                </div> \
              </li>";
    }
  }
  else {
    html = "";
  }
    

  // Insert table rows into html
  $('#list_ul').append(html);

}


//------------------------------------------------------------------
// Submit
//------------------------------------------------------------------

function submit_f(id, approve) {

  if (approve) {
    var status = ajax_f('approve', "&id="+id);
    if (status == "ok") {
      ajax_f('get_overview', '');
      $("#result").html("<p>Ok, användaren är registrerad och ett bekräftelsemail har skickats.</p>");
    }
    else {
      alert("Something went wrong!");
    }

  }
  else {
    var status = ajax_f('deny', "&id="+id);
    if (status == "ok") {
      ajax_f('get_overview', '');
      $("#result").html("<p>Ok, ett email om avslag har skickats.</p>");
    }
    else {
      alert("Something went wrong!");
    }
  }

}


//------------------------------------------------------------------
// Alignment. Set witdh of columns
//------------------------------------------------------------------

  function resize_f() {
    var c2_width = 0;
    $('.c2').each(function() {
      if ($(this).width() > c2_width) {
        c2_width = $(this).width();
      }
    });
    $('.c2').css('width', c2_width);
  }

  $(window).resize(function() { resize_f() });
