//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


//------------------------------------------------------------------------------------
// AJAX
//------------------------------------------------------------------------------------

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
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
        // Stop loading animation
        $('#spinner').trigger('ajaxStop');
      }
    }

    // Now get the value from user and pass it to server script
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/mitt_skywin/hoppkonto_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'get_all') {
      return(ajaxRequest.responseText);
    }
  }



//------------------------------------------------------------------------------------
// List all
//------------------------------------------------------------------------------------

$(document).ready(function(){

  // Get user list
  var response = ajax_f('get_all', '&sff_nr=' + G_SFF_NR);

  // Entries are separated by '|'
  var rows = response.split('|'); 

  // Nr of hits
  $('#balance').html(rows[0] + " kr");
  $('#updated').html(rows[1]);

  // Create html table row
  var html = "";

  for (var i=2; i<rows.length; i++) {

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + rows[i] + ")" ); 

    // The thml string
    html += "<tr> \
               <td class='nowrap transtype'>"             + myhash['TransType']   + "</td> \
               <td class='nowrap accounttype'>"           + myhash['AccountType'] + "</td> \
               <td class='nowrap regdate'>"               + myhash['RegDate']     + "</td> \
               <td class='nowrap amount' align='right'>"  + myhash['Amount']      + "</td> \
               <td class='nowrap balance' align='right'>" + myhash['Balance']     + "</td> \
               <td class='comment'>"                      + myhash['Comment']     + "</td> \
             </tr>";

  }

  // Insert table rows into html
  $('#table > tbody').html(html);


});

