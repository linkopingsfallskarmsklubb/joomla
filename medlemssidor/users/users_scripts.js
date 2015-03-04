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
        if (action == 'get_details')  { edit_f(ajaxRequest.responseText); }
      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/index.php/tools/medlemssidor/users/users_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'set_details') {
      return;
    }
    else if (action == 'delete') {
      return;
    }

  }



//------------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------------

$(document).ready(function(){

  // Get user list
  ajax_f('get_overview', '');

  // Attached tablesorter to user list
  $("#table").tablesorter({ sortList: [[2,0], [1,0]] }); 

});


//------------------------------------------------------------------
// Main content
//------------------------------------------------------------------

function fill_f(row) {

  // Entries are separated by '|'
  var rows = row.split('|'); 

  var html = "";
  for (row in rows) {

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + rows[row] + ")" ); 

    // Set icon color
    if (myhash['isActive'] =='1') {
      var icon_type  = "icon-ok";
      var icon_color = "icon-st-green";
    }
    else {
      var icon_type  = "icon-remove";
      var icon_color = "icon-st-red";
    }

    // Create html table row
    html += "<tr>                                                 \
              <td class='list_edit' align='center'><span class='icon-edit icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"ajax_f('get_details', '&id="+myhash['concID'] + "');\" onmouseover='Tip(edit)' onmouseout='UnTip()'></span></td> \
              <td class='list_username'>" + myhash['userName']  + "</td> \
              <td class='list_name'>" + myhash['fornamn']   + " " + myhash['efternamn'] + "</td> \
              <td class='list_lastlogin'>" + myhash['lastLogin'] + "</td> \
              <td class='list_numlogins' align='center'>" + myhash['numLogins'] + "</td> \
              <td class='list_active' align='center'><span class='" + icon_type + " icon-large " + icon_color + " icon-st-shadow'></span></td> \
              <td class='list_group wrap'>" + myhash['groups']    + "</td> \
            </tr>";
  }
  
  // Insert table rows into html
  $('#table > tbody').html(html);

  // Update tablesorter
  $('#table').trigger('update');  
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
      if ($("#filter_sel").val() == 'name') {
        $(".list_name:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
      else if ($("#filter_sel").val() == 'username') {
        $(".list_username:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
      else if ($("#filter_sel").val() == 'group') {
        $(".list_group:contains-ci('" + $(this).val() + "')").parent("tr").show();
      }
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
// Edit 
//------------------------------------------------------------------

function edit_f(row) {

  // Entries are separated by '|'
  var rows = row.split('|'); 

  // Ajax fetches the result as hash. mkhash parses the hash.
  var myhash = eval( "mkhash(" + rows[0] + ")" ); 
  
  // Populate popup values
  $('#det_username').html(myhash['userName']);
  $('#det_name').html(myhash['fornamn'] + " " + myhash['efternamn']);
  $('#det_sff_nr').html(myhash['sff_number']);
  $('#det_registered').html(myhash['registered']);
  $('#det_last_login').html(myhash['lastLogin']);
  $('#det_last_online').html(myhash['lastOnline']);
  $('#det_nr_logins').html(myhash['numLogins']);
  
  if (myhash['isActive'] == '1') {
    $('#det_active').prop('checked', true);
  }
  else {
    $('#det_active').prop('checked', false);
  }
  
  var groups = rows[1].split(',');
  var html   = "";
  var html   = "<li id='li_1'> \
                  <p class='popup_h1'>Grupper</p> \
                </li>";

  for (group in groups) {

    var checked = "";
    if ((groups[group].split('=')[1]) == '1') {
      checked = 'checked';
    }

    html += "<li id='li_1'>                           \
               <div class='c1'>                            \
                 <label>" + groups[group].split('=')[0] + ":</label> \
               </div> \
               <div class='c2'> \
                 <input type='checkbox' name='det_group_" + groups[group].split('=')[0] + "' id='det_group_" + groups[group].split('=')[0] + "' " + checked + "/> \
               </div> \
              </li>";
  }

  // Insert html
  $('#ul_groups').html(html);

  // Insert Concrete ID in hidden field
  $('#conc_id').val(myhash['concID']);

  // Activate/deactivate submit button
  $(document).ready(function() {
    $('#submit').attr('disabled','disabled');
  });
  $(document).on('change', '#details_popup', function(event) {
    $('#submit').removeAttr('disabled');
  });

  // Show the popup
  $("#details_popup, #overlay").popShow();

  // Popup alignment
  resize_callbacks.fire();

}



//------------------------------------------------------------------
// Delete
//------------------------------------------------------------------

function delete_confirm_f(username, name) {
  $('#del_username').html('[' + username + ']');
  $('#del_name').html(name);
  $('#details_popup').popHide();
  $('#delete_popup').popShow();
  $('#delete_popup').popCenter();
}


function delete_f() {

  // Add concrete ID to 'qstr'
  var qstr = "&conc_id=" + $('#conc_id').val();

  // Insert data into database
  ajax_f('delete', qstr);

  // Hide popup
  $('#delete_popup, #overlay').popHide();

  // Reload content
  ajax_f('get_overview', '');

}


//------------------------------------------------------------------
// Submit
//------------------------------------------------------------------

function submit_f() {

  // Create the query string from all input fields
  var qstr = "";

  // Add concrete ID to 'qstr'
  qstr += "&conc_id=" + $('#conc_id').val();

  // For each checkbox, add to 'qstr'
  $('#details_popup').find(':checkbox').each(function() {
    qstr += "&" + $(this).attr('id') + "=" + $(this).prop('checked');
  });

  // Insert data into database
  ajax_f('set_details', qstr);

  // Hide popup
  $('#details_popup, #overlay').popHide();

  // Reload content
  ajax_f('get_overview', '');

}


//------------------------------------------------------------------
// Alignment
//------------------------------------------------------------------

  // Before resizing, reset to original
  function resize_reset_f() {
    
    // Remove mobile style
    $('#div_details').removeClass('div_details_m');
    $('#div_misc').removeClass('div_misc_m');
    $('#div_groups').removeClass('div_groups_m');
  
    // Set height eqaul to the highest div
    var h = new Array();
    h[0]  = $('#div_details').height();
    h[1]  = $('#div_misc').height();
    h[2]  = $('#div_groups').height();
    
    var max = Math.max.apply(null, h);
    
    $('#div_details').height(max);
    $('#div_misc').height(max);
    $('#div_groups').height(max);

  }


  // Fix alignment when resizing screen
  function resize_set_f() {

    // --- Details popup ---
    if ($('#details_popup').css('display') != 'none') {

      // If one div has wrapped - apply mobile style
      var p1 = $('#div_details').position().left;
      var p2 = $('#div_misc').position().left;
      var p3 = $('#div_groups').position().left;
  
      if ((p2 == p1) || (p3 == p1)) {

        $('#div_details').addClass('div_details_m');
        $('#div_misc').addClass('div_misc_m');
        $('#div_groups').addClass('div_groups_m');
  
        // Also, set 'c2' width eqaul to the widest 'c2'.
        $('#div_details .c2').width( $('#div_details .c2').maxWidth() );
      }
  
      // Center popup
      $('#details_popup').popCenter();
    }

    // --- Delete confirm  ---
    if ($('#delete_popup').css('display') != 'none') {
      // Center popup
      $('#delete_popup').popCenter();
    }
  }

  // When screen is resized, first reset then resize
  var resize_callbacks = $.Callbacks();
  resize_callbacks.add(resize_reset_f);
  resize_callbacks.add(resize_set_f);
  $(window).resize(function() { resize_callbacks.fire(); });





//************************************************************************************
// Tooltips
//************************************************************************************

  var edit = "Ändra grupper";

