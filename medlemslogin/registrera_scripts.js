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
    ajaxRequest.open("POST", CCM_REL + "/index.php/tools/medlemslogin/registrera_db.php", false);
    ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajaxRequest.send(queryString); 

    // Caller specific stuff
    if (action == 'register') {
      return(ajaxRequest.responseText);
    }
  }



//-------------------------------------
// Init
//-------------------------------------


$(document).ready(function(){

  $("#cb_lfk").prop('checked', true);

  $("#cb_lfk, #cb_pilot, #cb_none").click(function(event) {

    $('#main_form').show();

    if ($(this).is('#cb_none')) {
      $("#cb_lfk").prop('checked', false);
      $("#cb_pilot").prop('checked', false);
    }
    else {
      $("#cb_none").prop('checked', false);
    }


    if ( $("#cb_lfk").prop('checked') ) {
      $('#opt_licensnummer').hide();
      $('#comment_div').hide();
      $('#fname_div').hide();
      $('#lname_div').hide();
      $('#info').hide();
    }
    else if ( $("#cb_pilot").prop('checked') ) {
      $('#opt_licensnummer').show();
      $('#comment_div').hide();
      $('#fname_div').show();
      $('#lname_div').show();
      $('#info').show();
    }
    else if ( $("#cb_none").prop('checked') ) {
      $('#opt_licensnummer').show();
      $('#comment_div').show();
      $('#fname_div').show();
      $('#lname_div').show();
      $('#info').show();
    }
    else {
      $('#main_form').hide();
    }

  });
});



$(document).ready(function(){
	$("#uLicenseNumber").focus();
});


// Autoformat date
$(document).on('focusout', '#uPID', function() {

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



//-------------------------------------
// Submit
//-------------------------------------

function submit_f() {

  $('#error').hide();

  var post = "";
  if ($("#cb_lfk").prop('checked')) {
    post += "&lfk=1";
  }
  else {
    post += "&lfk=0";
  }

  if ($("#cb_pilot").prop('checked')) {
    post += "&pilot=1";
    post += "&comment=Pilot";
  }
  else {
    post += "&pilot=0";
  }

  if ($("#cb_none").prop('checked')) {
    post += "&other=1";
    post += "&comment=" + $('#uComment').val();
  }
  else {
    post += "&other=0";
  }

  $('#main_form').find('input').each(function() {
    post += "&" +$(this).attr('id') +"="+ $(this).val();
  });

  var status = ajax_f('register', post);

  
  if (status == "lfk_ok") {
    window.location = "?success=lfk_ok";
  }
  else if (status == "non_lfk_ok") {
    window.location = "?success=non_lfk_ok";
  }
  else {
    $('#error').show();
    $('#error_msg').html(status);
  }
  
}




