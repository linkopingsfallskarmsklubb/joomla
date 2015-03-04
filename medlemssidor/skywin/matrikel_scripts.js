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
        if (action == 'get_years') { list_years_f(ajaxRequest.responseText); }
        if (action == 'get_all')   { list_all_f(ajaxRequest.responseText); }
      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/skywin/matrikel_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'get_details') {
      return(ajaxRequest.responseText);
    }
  }



//------------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------------

$(document).ready(function(){

  // Get all years
  ajax_f('get_years', '');

  // Get user list
  ajax_f('get_all', '&year='+$('#filter_year').val());

  // Attached tablesorter to user list
  $("#table").tablesorter({ 
      sortList: [[2,0], [1,0]],
      headers:  { 0: { sorter: false} }
  }); 

  // Remove expanded item when sorting
  $('.list_header').click(function() {
      $('.expanded').remove();
      $('.selected').removeClass('selected');
      $('.more').show();
      $('.less').hide();
  });

});


// Place filter box according to resoulution
$(window).load(function()   { place_filter_f(); });
$(window).resize(function() { place_filter_f(); });


function place_filter_f() { 

  $('#member_list').css('float','left');
  $('#filter').css('float','left');

  if ($('#content').width() > ($('#filter').outerWidth() + $('#member_list').outerWidth())) {
    $('#filter').insertAfter('#member_list');
  }
  else {
    $('#member_list').css('float','none');
    $('#filter').css('float','none');
    $('#member_list').insertAfter('#filter');
  }

};



//------------------------------------------------------------------
// Add years to dropdown menu
//------------------------------------------------------------------

function list_years_f(years) {

  var yearArr = years.split(',');

  var html = "";
  for (year in yearArr) {
    // Create html table row
    html += "<option value='"+yearArr[year]+"'>"+yearArr[year]+"</option>";
  }
  
  // Insert table rows into html
  $('#filter_year').html(html);

}


//---------------------------------------------------------
// Filter user list
//---------------------------------------------------------

$(document).ready(function(){
    
  // Write on keyup event of keyword input element
  $("#filter_inp").keyup(function(){

    // When value of the input is not blank
    if( $(this).val() != "") {

      // First hide all rows
      $("#table>tbody>tr").hide();

      // Then show all matching rows
      $(".filter_list:contains-ci('" + $(this).val() + "')").parent("tr").show();
    }
    else {
      // When there is no input or clean again, show everything
      $("#table>tbody>tr").show();
    }
  });
});

// jQuery expression for case-insensitive filter
$.extend($.expr[":"], {
  "contains-ci": function(elem, i, match, array) {
    return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});




//------------------------------------------------------------------
// Main content
//------------------------------------------------------------------

function list_all_f(row) {

  // Entries are separated by '|'
  var rows = row.split('|'); 

  // Nr of hits
  $('#nr_hits').html('Antal medlemmar: ' + rows.length);

  // Create html table row
  var html = "";
  for (rowNr in rows) {

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + rows[rowNr] + ")" ); 

    // The thml string
    html += "<tr> \
              <td align='center'> \
                 <span class='more icon-plus  icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"details_f($(this).closest('tr'), 'more', " + myhash['MemberNo'] + "," + myhash['Year'] + ");\"></span> \
                 <span class='less icon-minus icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"details_f($(this).closest('tr'), 'less', " + myhash['MemberNo'] + "," + myhash['Year'] + ");\" style='display: none;'></span> \
              </td> \
              <td class='filter_list'> " + myhash['MemberNo']     + "</td> \
              <td class='filter_list'> " + myhash['FirstName']    + "</td> \
              <td class='filter_list'> " + myhash['LastName']     + "</td> \
              <td class='filter_list'> " + myhash['Emailaddress'] + "</td> \
            </tr>";

  }

  // Insert table rows into html
  $('#table > tbody').html(html);

  // Update tablesorter
  $("#table").trigger('update'); 
 
}


//------------------------------------------------------------------
// Details
//------------------------------------------------------------------

function details_f(parent, moreLess, sffNr, year) {

  // Set default icons
  $('.less').hide();
  $('.more').show();

  // If 'less' is clicked, removed details
  if (moreLess == 'less') {
    $('.expanded').remove();
    $('.selected').removeClass('selected');
  }
 
  // If 'more' is clicked, show details
  else if (moreLess == 'more') {

    // Hide 'more', show 'less'
    parent.find('.more').toggle();
    parent.find('.less').toggle();

    // Remove previous selections
    $('.expanded').remove();
    $('.selected').removeClass('selected');

    // Get details for selected member
    var details = ajax_f('get_details', '&sffNr='+sffNr+'&year='+year);

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + details + ")" ); 
  
    // Addclass 'selected' to selected row
    parent.addClass('selected');

    // Create html string
    var addr = "";
    if (myhash['Address2'] != '') {
      addr = myhash['Address1'] + ", " + myhash['Address2'];
    }
    else {
      addr = myhash['Address1'];
    }

    var html = "<tr class='expanded'> \
                    <td colspan='5'>         \
                    <ul class='clean_list'> \
                     <li> \
                       <div class='c1'>InternalNo: </div> \
                       <div class='c2'>"+ myhash['InternalNo'] +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Namn: </div> \
                       <div class='c2'>"+ myhash['FirstName'] +" "+  myhash['LastName']  +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Smeknamn: </div> \
                       <div class='c2'>"+ myhash['NickName'] +"</div> \
                     </li> \
                     <li> \
                       &nbsp; \
                     </li> \
                     <li> \
                       <div class='c1'>Adress: </div> \
                       <div class='c2'>"+ addr +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Postnr, ort: </div> \
                       <div class='c2'>"+ myhash['Postcode'] +" "+ myhash['Posttown']  +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Email: </div> \
                       <div class='c2'>"+ myhash['Emailaddress']    +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Tel mob: </div> \
                       <div class='c2'>"+ myhash['PhoneMob']    +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Tel hem: </div> \
                       <div class='c2'>"+ myhash['PhoneHome']    +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Tel arbete: </div> \
                       <div class='c2'>"+ myhash['PhoneWork']    +"</div> \
                     </li> \
                     <li> \
                       &nbsp; \
                     </li> \
                     <li> \
                       <div class='c1'>Klubb: </div> \
                       <div class='c2'>"+ myhash['Club']    +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Instruktör: </div> \
                       <div class='c2'>"+ myhash['InstructType']    +"</div> \
                     </li> \
                     <li> \
                       <div class='c1'>Saldo: </div> \
                       <div class='c2'>"+ myhash['Balance']    +"</div> \
                     </li> \
                    </ul> \
                  </td> \
                </tr>";

    // Add new table row with all information
    parent.after(html);
  }
}

