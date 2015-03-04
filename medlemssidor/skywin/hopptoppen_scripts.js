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
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/skywin/hopptoppen_db.php" + queryString, false);
    ajaxRequest.send(null); 

  }



//------------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------------

$(document).ready(function(){

  // Get all years
  ajax_f('get_years', '');

  // Get 'hopptoppen'
  ajax_f('get_all', '&year='+$('#filter_year').val());

});


// Place filter box according to resoulution
$(window).load(function() { place_filter_f(); });
$(window).resize(function() { place_filter_f(); });


function place_filter_f() { 

  $('#hopptoppen').css('float','left');
  $('#filter').css('float','left');

  if ($('#content').width() > ($('#filter').outerWidth() + $('#hopptoppen').outerWidth())) {
    $('#filter').insertAfter('#hopptoppen');
  }
  else {
    $('#hopptoppen').css('float','none');
    $('#filter').css('float','none');
    $('#hopptoppen').insertAfter('#filter');
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

  // Create html table row
  var html = "";
  for (rowNr in rows) {

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + rows[rowNr] + ")" ); 

    if (typeof myhash['rank']  == "undefined") { var rank  = "-"; } else { var rank  = myhash['rank'];  }
    if (typeof myhash['name']  == "undefined") { var name  = "-"; } else { var name  = myhash['name'];  }
    if (typeof myhash['jumps'] == "undefined") { var jumps = "-"; } else { var jumps = myhash['jumps']; }


    // The thml string
    html += "<tr> \
              <td align='center'> " + rank  + "</td> \
              <td> " + name  + "</td> \
              <td align='center'> " + jumps + "</td> \
            </tr>";

  }

  // Insert table rows into html
  $('#table > tbody').html(html);

}


