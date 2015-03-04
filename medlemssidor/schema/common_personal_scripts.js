//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************



//------------------------------------------------------------------
// AJAX
//------------------------------------------------------------------

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

  // Now get the value from user and pass it to server script
  var queryString = "?C5_URL=" + CCM_REL + "&action=" + action + "&type=" + type + qstr;
  ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/schema/common_personal_db.php" + queryString, false);
  ajaxRequest.send(null); 

  // Caller specific stuff
  if (action == 'new_get_all') {
    return(ajaxRequest.responseText);
  }
  else if (action == 'new_get_years') {
    return(ajaxRequest.responseText);
  }
  else if (action == 'new_submit') {
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
  resize_f();
});

function resize_f() {
  $('.popup:visible').popCenter();
  if ($('#full_list').position().top != $('#filter').position().top) {
    $('#filter').css('float', 'left');
    $('#full_list').css('height', $('.popup').height() - $('#new_header').outerHeight(true) - $('#filter').outerHeight(true));
  }
  else {
    $('#filter').css('float', 'right');
    $('#full_list').css('height', $('.popup').height() - $('#new_header').outerHeight(true));
  }
  $('#full_list').css('padding-right', scrollbarWidth());
  
  // Reattach tablesorter (gets deleteted on popCenter -> clone)
  tablesorter_f();
}


// Filter actions
$(document).ready(function(){
  $('body').on('click', '#filter_marked', function() {
    if ($("#filter_marked").is(':checked')) {
      $('#filter_lfk').prop('checked', false);
      $("#filter_year").val($("#filter_year option:eq(0)").val());
    }
    new_get_all_f();
  });

  $('body').on('click', '#filter_lfk', function() {
    if ($("#filter_lfk").is(':checked')) {
      $('#filter_marked').prop('checked', false);
    }
    new_get_all_f();
  });
  $('body').on('change', '#filter_year', function() {
    new_get_all_f();
  });
});



//------------------------------------------------------------------
// List all
//------------------------------------------------------------------

function list_all_f(row) {
  
  // Entries are separated by '|'
  var rows = row.split('|'); 

  var html = "";
  for (row in rows) {

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + rows[row] + ")" ); 

    html += "<tr> \
               <td class='name'>" + myhash['fornamn'] +" "+ myhash['efternamn'] +"</td> \
               <td class='licensnr' align='right'>"+ myhash['sff_nummer'] + "</td> \
             </tr>";
  }

  // Insert table
  $('#main_tbody').html(html);
}




//------------------------------------------------------------------
// New
//------------------------------------------------------------------

function new_f() {

  // Get all years from skywin
  new_get_years_f();

  // Get all candidates from skywin
  new_get_all_f();

  // Some layout stuff
  resize_f();
}


// Get all years
function new_get_years_f() {
  var years = ajax_f('new_get_years', '').split(',');
  var html  = "<option value='all'>Alla år</option>";
  for (year in years) {
    html += "<option value='"+years[year]+"'>"+years[year]+"</option>";
  }
  $('#filter_year').html(html);
  $("#filter_year").val($("#filter_year option:eq(2)").val());
}


// Get all records from skywin
function new_get_all_f() {
  
  // Create the query string
  var lfk    = 0; 
  var marked = 0;
  if ($('#filter_marked').is(':checked')) {
    var marked = 1;
  }
  else if ($('#filter_lfk').is(':checked')) {
    var lfk = 1; 
  }
  var qstr = "&year=" + $('#filter_year').val() + "&lfk=" + lfk + "&marked=" + marked;

  // Ajax call
  var row = ajax_f('new_get_all', qstr);

  // Entries are separated by '|'
  var rows = row.split('|'); 
  
  // Create html table row
  var html = "";
  for (rowNr in rows) {
    
    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + rows[rowNr] + ")" ); 
    
    // Create html table
    html += "<tr> \
               <td> <input type='checkbox' class='new_cb' " + myhash['checked']    + " /></td> \
               <td class='new_first_name'>"                 + myhash['fornamn']    + "</td> \
               <td class='new_last_name'>"                  + myhash['efternamn']  + "</td> \
               <td class='new_licensnr'>"                   + myhash['sff_nummer'] + "</td> \
               <td class='new_club'>"                       + myhash['klubb']      + "</td> \
             </tr>";
  }


  // Show popup
  $('#new_popup, #overlay').popShow();
  $('#new_popup').popCenter();

  // Insert table
  $('#new_tbody').html(html);

  // Attach tablesorter to user list
  tablesorter_f();

}

// Attach tablesorter to user list
function tablesorter_f() {
  $("#new_table").tablesorter({ sortList: [[1,0]], headers:  {0: { sorter: false}}});
}


// Submit and reload
function new_submit_f() {

  // Create the query string
  var qstr = "";
  var tmp  = "";
  $('#new_tbody tr').each(function(){
    if ( $(this).find(".new_cb").is(':checked') ) {
      tmp += $(this).find(".new_first_name").html() +","+ $(this).find(".new_last_name").html() +","+ $(this).find(".new_licensnr").html() + "|";
    }
  });  
  tmp   = tmp.slice(0,-1);
  qstr += "&all=" + tmp;

  // Ajax call
  var success = ajax_f('new_submit', qstr);

  // Check status. If ok, reload all entries
  if (success != 'ok') {
    alert('Something went wrong!\n' + success);
  }
  else {
    ajax_f('get_all', '');
  }

  // Hide popup
  $('#new_popup, #overlay').popHide();
      

}
