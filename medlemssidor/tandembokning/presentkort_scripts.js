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
        
        // Continue
        if (action == 'get_all') { list_all_f(ajaxRequest.responseText); }
      }
    }

    // Get the value from user and pass it to server script.
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/tandembokning/presentkort_db.php" + queryString, false);
    ajaxRequest.send(null); 


    // Caller specific stuff
    if (action == 'delete') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'new_pk') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'details') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'edit') {
      return(ajaxRequest.responseText);
    }
  }


//------------------------------------------------------------------
// Init
//------------------------------------------------------------------

// Get all entries
$(document).ready(function(){
  ajax_f('get_all', '');
});


// Center popup on resize
$(window).resize(function() { 
  $('.popup:visible').popCenter();
});


// Autoformat date
$(document).on('focusout', '#new_giltigt_till', function() {

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


// Attached tablesorter to user list
$(document).ready(function(){
    $("#pk_table").tablesorter({ 
        sortList: [[0,0]], 
          headers:  { 1: { sorter: false}, 
                      2: { sorter: false}, 
                      6: { sorter: false}, 
                      7: { sorter: false} }});
});


//------------------------------------------------------------------
// List all
//------------------------------------------------------------------

  function list_all_f(row) {

    // Each entry is separated by '|'
    pkArr = row.split('|'); 

    // Create the html table
    var html = "";

    for (var i in pkArr) {
      var myhash      = eval( "mkhash(" + pkArr[i] + ")" ); 

      if (myhash['hoppat'] == '1') {
        var hoppat = "<span class='icon-ok icon-large icon-st-green icon-st-shadow'>";
      }
      else {
        var hoppat = "&nbsp;";
      }

      if (myhash['video'] == '1') {
        var video = "<span class='icon-ok icon-large icon-st-green icon-st-shadow'>";
      }
      else {
        var video = "&nbsp;";
      }

      if (myhash['foto'] == '1') {
        var foto = "<span class='icon-ok icon-large icon-st-green icon-st-shadow'>";
      }
      else {
        var foto = "&nbsp;";
      }

      if (myhash['bokad'] == '1') {
        var bokad = "<span class='icon-ok icon-large icon-st-green icon-st-shadow'>";
      }
      else {
        var bokad = "&nbsp;";
      }

      html += "<tr class='tr_hover' onclick=\"details_f(" + myhash['id'] + ");\"> \
                 <td align='center'>" + myhash['id'] + "</td> \
                 <td class='list_jumped' align='center'>" + hoppat + "</td> \
                 <td class='list_booked' align='center'>" + bokad + "</td> \
                 <td class='list_p_name nowrap'>" + myhash['pax_fornamn'] + " " + myhash['pax_efternamn'] + "</td> \
                 <td align='center'>" + myhash['pax_langd'] + "</td> \
                 <td align='center'>" + myhash['pax_vikt'] + "</td> \
                 <td align='center'>" + video + "</td> \
                 <td align='center'>" + foto + "</td> \
                 <td class='list_expired nowrap'>" + myhash['giltigt_till'] + "</td> \
                 <td>" + myhash['ovrigt'] + "</td> \
               </tr>";
    }

    // Insert table
    $('#pk_table > tbody').html(html);

    // Filter
    filter_cb_f();

    // Update tablesorter
    $('#pk_table').trigger('update');  

  }



//---------------------------------------------------------
// Filter list
//---------------------------------------------------------

$(document).ready(function(){
    
  // On keyup
  $("#filter_inp").keyup(function(){

    // When value of the input is not blank
    if( $(this).val() != "") {

      // First hide all rows
      $("#pk_table>tbody>tr").hide();

      // Then show all matching rows
      if ($("#filter_sel").val() == 'p_name') {
        $(".list_p_name:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
      else if ($("#filter_sel").val() == 'p_email') {
        $(".list_p_email:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
      else if ($("#filter_sel").val() == 'p_phone') {
        $(".list_p_phone:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
      else if ($("#filter_sel").val() == 'k_name') {
        $(".list_k_name:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
      else if ($("#filter_sel").val() == 'k_email') {
        $(".list_k_email:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
      else if ($("#filter_sel").val() == 'k_phone') {
        $(".list_k_phone:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
    }
    else {
      // When there is no input or clean again, show everything
      $("#pk_table>tbody>tr").show();
    }
  });
});

// jQuery expression for case-insensitive filter
$.extend($.expr[":"], {
  "contains-ci": function(elem, i, match, array) {
    return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});



function filter_cb_f() {

  //$('#pk_table_body tr').show();

  var hide = false;
  var hits = 0;

  $('#pk_table_body tr').each(function() {

    if (( $(this).children('.list_jumped').html() != '&nbsp;' ) && ( $('#filter_jumped').is(':checked') )) {
      hide = true;
    }
    else if (( $(this).children('.list_booked').html() != '&nbsp;' ) &&  ( $('#filter_booked').is(':checked') )) {
      hide = true;
    }
    else if (( new Date($(this).children('.list_expired').html()) < new Date() ) && ( $('#filter_expired').is(':checked') )) {
      hide = true;
    }
    else {
      hide = false;
    }

    if (hide == true) {
      $(this).hide();
    }
    else {
      $(this).show();
      hits++;
    }

  });

  $('#hits').html(hits);
}




//------------------------------------------------------------------
// Details popup
//------------------------------------------------------------------

  function details_f(id) {

    // Shoe details popup
    $('#details_popup, #overlay').popShow();
    $('#details_popup').popCenter();

    // Ajax fetches the result as hash. mkhash parses the hash.
    var qstr   = "&id=" + id;
    var row    = ajax_f('details', qstr);
    var myhash = eval( "mkhash(" + row + ")" ); 

    // Get field values from hash
    var use_contact        = (myhash['use_contact'] == 1) ? 'Ja' : 'Nej';
    var pax_fornamn        = myhash['pax_fornamn'];
    var pax_efternamn      = myhash['pax_efternamn'];
    var pax_adress_1       = myhash['pax_adress_1'];
    var pax_adress_2       = myhash['pax_adress_2'];
    var pax_postnummer     = myhash['pax_postnummer'];
    var pax_ort            = myhash['pax_ort'];
    var pax_telefon        = myhash['pax_telefon'];
    var pax_email          = myhash['pax_email'];
    var pax_langd          = myhash['pax_langd'];
    var pax_vikt           = myhash['pax_vikt'];
    var kontakt_fornamn    = myhash['kontakt_fornamn'];
    var kontakt_efternamn  = myhash['kontakt_efternamn'];
    var kontakt_adress_1   = myhash['kontakt_adress_1'];
    var kontakt_adress_2   = myhash['kontakt_adress_2'];
    var kontakt_postnummer = myhash['kontakt_postnummer'];
    var kontakt_ort        = myhash['kontakt_ort'];
    var kontakt_telefon    = myhash['kontakt_telefon'];
    var kontakt_email      = myhash['kontakt_email'];
    var hoppat             = (myhash['hoppat'] == 1) ? 'Ja' : 'Nej';
    var video              = (myhash['video']  == 1) ? 'Ja' : 'Nej';
    var foto               = (myhash['foto']   == 1) ? 'Ja' : 'Nej';
    var giltigt_till       = myhash['giltigt_till'];
    var betalat            = myhash['betalat'];
    var ovrigt             = myhash['ovrigt'];
    var tillagd            = myhash['tillagd'].substring(0,10);
    var modifierad         = myhash['modifierad'] !== '' ? myhash['modifierad'].substring(0,10) : "-";
    var id                 = myhash['id'];
    var bokad              = myhash['bokad'];

    // Set field value
    $('#det_hoppat').html(hoppat);
    $('#det_video').html(video);
    $('#det_foto').html(foto);
    $('#det_giltigt_till').html(giltigt_till);
    $('#det_betalat').html(betalat);
    $('#det_ovrigt').html(ovrigt);
    $('#det_tillagd').html(tillagd);
    $('#det_modifierad').html(modifierad);
    $('#det_pknr').html(id);
    $('#det_pax_fornamn').html(pax_fornamn);
    $('#det_pax_efternamn').html(pax_efternamn);
    $('#det_pax_adress_1').html(pax_adress_1);
    $('#det_pax_adress_2').html(pax_adress_2);
    $('#det_pax_postnummer').html(pax_postnummer + " " + pax_ort);
    $('#det_pax_telefon').html(pax_telefon);
    $('#det_pax_email').html(pax_email);
    $('#det_pax_langd').html(pax_langd);
    $('#det_pax_vikt').html(pax_vikt);
    $('#det_use_contact').html(use_contact);
    $('#det_kontakt_fornamn').html(kontakt_fornamn);
    $('#det_kontakt_efternamn').html(kontakt_efternamn);
    $('#det_kontakt_adress_1').html(kontakt_adress_1);
    $('#det_kontakt_adress_2').html(kontakt_adress_2);
    $('#det_kontakt_postnummer').html(kontakt_postnummer + " " + kontakt_ort);
    $('#det_kontakt_telefon').html(kontakt_telefon);
    $('#det_kontakt_email').html(kontakt_email);

    // Enabe/disable contact form
    $('#det_use_contact').change(function() {
      enab_contact_form_f();
    });
    $('#det_use_contact').trigger('change');

    function enab_contact_form_f() {
      if ($('#det_use_contact').html() == "Ja") {
        $('#details_popup .div_kontakt').removeClass("disabled");
      }
      else {
        $('#details_popup .div_kontakt').addClass("disabled");
      }
    }


    // If pax is booked, hide some buttons
    if (bokad == '1') {
      $('#det_book').hide();
      $('#det_delete').hide();
    }
    else {
      $('#det_book').show();
      $('#det_delete').show();
    }
  }


//------------------------------------------------------------------
// Edit
//------------------------------------------------------------------

function edit_f() {

  // Hide details popup
  $('#details_popup').popHide();

  // Ajax fetches the result as hash. mkhash parses the hash.
  var qstr   = "&id=" + $('#det_pknr').html();
  var row    = ajax_f('details', qstr);
  var myhash = eval( "mkhash(" + row + ")" ); 

  // Get field values from hash
  var use_contact        = (myhash['use_contact'] == 1) ? true : false;
  var pax_fornamn        = myhash['pax_fornamn'];
  var pax_efternamn      = myhash['pax_efternamn'];
  var pax_adress_1       = myhash['pax_adress_1'];
  var pax_adress_2       = myhash['pax_adress_2'];
  var pax_postnummer     = myhash['pax_postnummer'];
  var pax_ort            = myhash['pax_ort'];
  var pax_telefon        = myhash['pax_telefon'];
  var pax_email          = myhash['pax_email'];
  var pax_langd          = myhash['pax_langd'];
  var pax_vikt           = myhash['pax_vikt'];
  var kontakt_fornamn    = myhash['kontakt_fornamn'];
  var kontakt_efternamn  = myhash['kontakt_efternamn'];
  var kontakt_adress_1   = myhash['kontakt_adress_1'];
  var kontakt_adress_2   = myhash['kontakt_adress_2'];
  var kontakt_postnummer = myhash['kontakt_postnummer'];
  var kontakt_ort        = myhash['kontakt_ort'];
  var kontakt_telefon    = myhash['kontakt_telefon'];
  var kontakt_email      = myhash['kontakt_email'];
  var hoppat             = (myhash['hoppat'] == 1) ? true : false;
  var video              = (myhash['video']  == 1) ? true : false;
  var foto               = (myhash['foto']   == 1) ? true : false;
  var giltigt_till       = myhash['giltigt_till'];
  var betalat            = myhash['betalat'];
  var ovrigt             = myhash['ovrigt'];
  var tillagd            = myhash['tillagd'].substring(0,10);
  var modifierad         = myhash['modifierad'] !== undefined ? myhash['modifierad'].substring(0,10) : "-";
  var id                 = myhash['id'];

  // Set field value
  $('#edit_video').prop('checked', video);
  $('#edit_foto').prop('checked', foto);
  $('#edit_giltigt_till').val(giltigt_till);
  $('#edit_betalat').val(betalat);
  $('#edit_ovrigt').html(ovrigt);
  $('#edit_tillagd').html(tillagd);
  $('#edit_modifierad').html(modifierad);
  $('#edit_pknr').html(id);
  $('#edit_pax_fornamn').val(pax_fornamn);
  $('#edit_pax_efternamn').val(pax_efternamn);
  $('#edit_pax_adress_1').val(pax_adress_1);
  $('#edit_pax_adress_2').val(pax_adress_2);
  $('#edit_pax_postnummer').val(pax_postnummer);
  $('#edit_pax_ort').val(pax_ort);
  $('#edit_pax_telefon').val(pax_telefon);
  $('#edit_pax_email').val(pax_email);
  $('#edit_pax_langd').val(pax_langd);
  $('#edit_pax_vikt').val(pax_vikt);
  $('#edit_use_contact').prop('checked', use_contact);
  $('#edit_kontakt_fornamn').val(kontakt_fornamn);
  $('#edit_kontakt_efternamn').val(kontakt_efternamn);
  $('#edit_kontakt_adress_1').val(kontakt_adress_1);
  $('#edit_kontakt_adress_2').val(kontakt_adress_2);
  $('#edit_kontakt_postnummer').val(kontakt_postnummer);
  $('#edit_kontakt_ort').val(kontakt_ort);
  $('#edit_kontakt_telefon').val(kontakt_telefon);
  $('#edit_kontakt_email').val(kontakt_email);
  $('#edit_hoppat').val(hoppat);
  $('#edit_hoppat').prop('checked', hoppat);

  // Enabe/disable contact form
  edit_ena_contact_form_f(true);

  // Show edit popup
  $('#edit_popup, #overlay').popShow();
  $('#edit_popup').popCenter();

  // Set initial focus
  $('#edit_pax_fornamn').focus();
  
}



// Enabe/disable contact form
var contact_hash = {};
$(document).ready(function() {
  $('#edit_use_contact').change(function() {
    edit_ena_contact_form_f(false);
  });
});

function edit_ena_contact_form_f(first) {
  if ($('#edit_use_contact').is(':checked')) {
    $('#edit_popup .div_kontakt').removeClass("disabled");
    $('#edit_popup .div_kontakt').find('input').attr('disabled', false);
    $('#edit_pax_telefon').removeClass('required inp_error');
    $('#edit_popup .div_kontakt').find('input').each(function() { 
      if (first == false) {
        $(this).val(contact_hash[$(this).attr('id')]);
      }
    });
  }
  else {
    $('#edit_popup .div_kontakt').addClass("disabled");
    $('#edit_popup .div_kontakt').find('input').removeClass("inp_error");
    $('#edit_popup .div_kontakt').find('input').attr('disabled', true);
    $('#edit_pax_fornamn').addClass('required');
    $('#edit_pax_efternamn').addClass('required');
    $('#edit_pax_telefon').addClass('required');
    $('#edit_popup .div_kontakt').find('input').each(function() { 
      contact_hash[$(this).attr('id')] = $(this).val(); 
      $(this).val("");
    });
  }
}




function edit_submit_f() {

  // --- Validate ---
  var error     = false;

  // Required fields
  $('#edit_popup .required').each(function() {
    if (! $(this).is(':disabled')) { 
      if ($(this).val() == "") {
        $(this).addClass("inp_error");
        error = true;
      }
      else {
        $(this).removeClass("inp_error");
      }
    }
  });

  // Check that 'längd' and 'vikt' only contains digits
  var intRegex  = /^\d+$/;
  var dateRegex = /^\d\d\d\d\-[0-1][1-9]-[0-3][0-9]$/;
  if (! intRegex.test($('#edit_pax_vikt').val()) && ($('#edit_pax_vikt').val() != "")) {
    $('#edit_pax_vikt_error').html("* Endast siffror");
    $('#edit_pax_vikt').addClass("inp_error");
    error = true;
  }
  if (! intRegex.test($('#edit_pax_langd').val()) && ($('#edit_pax_langd').val() != "")) {
    $('#edit_pax_langd_error').html("* Endast siffror");
    $('#edit_pax_langd').addClass("inp_error");
    error = true;
  }

  if (! error) {

    // Create the post string
    var qstr = "&id=" + $('#det_pknr').html();
    $.each($('#edit_popup :input'),function(k){
      if ($(this).attr('type') == 'checkbox') {
        if ($(this).is(':checked')) {
          qstr += "&" + $(this).attr('name').substring(5) + "=1";
        }
        else {
          qstr += "&" + $(this).attr('name').substring(5) + "=0";
        }
      }
      else {
        qstr += "&" + $(this).attr('name').substring(5) + "=" + $(this).val();
      }
    });

    // Ajax call
    var success = ajax_f('edit', qstr);
    // Check status. If ok, reload all entries
    if (success != 'ok') {
      alert('Something went wrong!\n' + success);
    }
    else {
      $('#edit_popup, #overlay').popHide();
      ajax_f('get_all', '');
    }
  }
  else {
    $('#edit_error').html("* Fel hittades i formuläret.");
  }
}

function edit_abort_f() {

  // Reset form
  $('#edit_popup :input').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
  $('#edit_popup').find('.inp_error').each(function(){ $(this).removeClass('inp_error'); });
  $('#edit_error').html("");
  $('#edit_pax_langd_error').html("");
  $('#edit_pax_vikt_error').html("");

  // Hide popup
  $('#edit_popup, #overlay').popHide();
}



//------------------------------------------------------------------
// New 'presentkort'
//------------------------------------------------------------------

function new_form_f() {

  // Show popup
  $('#new_popup, #overlay').popShow();
  $('#new_popup').popCenter();

  // Enabe/disable contact form
  new_ena_contact_form_f();

  // Set initial date in 'Giltigt till'
  Number.prototype.padLeft = function(base,chr){
    var  len = (String(base || 10).length - String(this).length)+1;
    return len > 0? new Array(len).join(chr || '0')+this : this;
  }
  var d = new Date, dformat = [d.getFullYear()+1, (d.getMonth()+1).padLeft(), d.getDate().padLeft()].join('-');
  $('#new_giltigt_till').val(dformat);

  // Set initial focus
  $('#new_pax_fornamn').focus();

}

// Enabe/disable contact form
var h = {};
$(document).ready(function() {
  $('#new_use_contact').change(function() {
    new_ena_contact_form_f();
  });
});

function new_ena_contact_form_f() {
  if ($('#new_use_contact').is(':checked')) {
    $('#new_popup .div_kontakt').removeClass("disabled");
    $('#new_popup .div_kontakt').find('input').attr('disabled', false);
    $('#new_pax_telefon').removeClass('required inp_error');
    $('#new_popup .div_kontakt').find('input').each(function() { 
      $(this).val(h[$(this).attr('id')]);
    });
  }
  else {
    $('#new_popup .div_kontakt').addClass("disabled");
    $('#new_popup .div_kontakt').find('input').removeClass("inp_error");
    $('#new_popup .div_kontakt').find('input').attr('disabled', true);
    $('#new_pax_fornamn').addClass('required');
    $('#new_pax_efternamn').addClass('required');
    $('#new_pax_telefon').addClass('required');
    $('#new_popup .div_kontakt').find('input').each(function() { 
      h[$(this).attr('id')] = $(this).val(); 
      $(this).val("");
    });
  }
}

function new_submit_f() {

  // --- Validate ---
  var error     = false;

  // Required fields
  $('#new_popup .required').each(function() {
    if (! $(this).is(':disabled')) { 
      if ($(this).val() == "") {
        $(this).addClass("inp_error");
        error = true;
      }
      else {
        $(this).removeClass("inp_error");
      }
    }
  });

  // Check that 'längd' and 'vikt' only contains digits
  var intRegex  = /^\d+$/;
  var dateRegex = /^\d\d\d\d\-[0-1][1-9]-[0-3][0-9]$/;
  if (! intRegex.test($('#new_pax_vikt').val()) && ($('#new_pax_vikt').val() != "")) {
    $('#new_pax_vikt_error').html("* Endast siffror");
    $('#new_pax_vikt').addClass("inp_error");
    error = true;
  }
  if (! intRegex.test($('#new_pax_langd').val()) && ($('#new_pax_langd').val() != "")) {
    $('#new_pax_langd_error').html("* Endast siffror");
    $('#new_pax_langd').addClass("inp_error");
    error = true;
  }


  if (! error) {

    // Create the post string
    var qstr = "";
    $.each($('#new_popup :input'),function(k){
      if ($(this).attr('type') == 'checkbox') {
        if ($(this).is(':checked')) {
          qstr += "&" + $(this).attr('name').substring(4) + "=1";
        }
        else {
          qstr += "&" + $(this).attr('name').substring(4) + "=0";
        }
      }
      else {
        qstr += "&" + $(this).attr('name').substring(4) + "=" + $(this).val();
      }
    });

    // Ajax call
    var success = ajax_f('new_pk', qstr);
    
    // Check status. If ok, reload all entries
    if (success != 'ok') {
      alert('Something went wrong!\n' + success);
    }
    else {
      
      $('#new_error').html("");
      $('#new_popup :input').not(':button, :submit, :reset, :hidden')
                            .val('')
                            .removeAttr('checked')
                            .removeAttr('selected');
      
      $('#new_popup, #overlay').popHide();
      ajax_f('get_all', '');
    }
  }
  else {
    $('#new_error').html("* Fel hittades i formuläret.");
  }
}


function new_abort_f() {

  // Reset form
  $('#new_popup :input').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
  $('#new_popup').find('.inp_error').each(function(){ $(this).removeClass('inp_error'); });
  $('#new_error').html("");
  $('#new_pax_langd_error').html("");
  $('#new_pax_vikt_error').html("");

  // Hide popup
  $('#new_popup, #overlay').popHide();
}


//------------------------------------------------------------------
// Delete
//------------------------------------------------------------------

function delete_confirm_f() {

  var id   = $('#det_pknr').html();
  var pax  = $('#det_pax_fornamn').html() + " " + $('#det_pax_efternamn').html()

  $('#del_pknr').html(id);
  $('#del_pk_namn').html(pax);

  $('#details_popup').popHide();
  $('#delete_popup, #overlay').popShow();
  $('#delete_popup').popCenter();
  
}


function delete_f(id) {

  var success = ajax_f('delete','&id='+id);

  $('#delete_popup, #overlay').popHide();

  if (success != 'ok') {
    alert('Something went wrong:\n'+ success);
  }
  else {
    ajax_f('get_all', '');
  }
}


//------------------------------------------------------------------
// Book
//------------------------------------------------------------------

function book_f() {
  var pax_id = $('#det_pknr').html();
  window.location = CCM_REL + "/medlemssidor/tandembokning/ny_bokning/?rebook_id=" + pax_id;
}
