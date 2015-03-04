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
      }
    }

    var queryString = "action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("POST", CCM_REL + "/index.php/tools/medlemslogin/logga_in_db.php", false);
    ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxRequest.send(queryString); 

    // Caller specific stuff
    if (action == 'login') {
      return(ajaxRequest.responseText);
    }
  }


//-------------------------------------
// Init
//-------------------------------------

$(document).ready(function(){
	$("input[name=uName]").focus();
});



//-------------------------------------
// Submit
//-------------------------------------

function submit_f() {
  $('#error').hide();

  var post   = "&uName=" + $('#uName').val() + "&uPassword=" + $('#uPassword').val() + "&rdURL=" + $('#rdURL').val();
  var status = ajax_f('login', post);
  if (status[0] != '/') {
    $('#error').show();
    $('#error_msg').html(status);
  }
  else {
    window.location.assign(status);
  }
}




