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
  
  // Now get the value from user and pass it to server script (the id can also be a date).
  var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
  ajaxRequest.open("GET", CCM_REL + "/index.php/tools/medlemmar/kalender_db.php" + queryString, false);
  ajaxRequest.send(null); 
  
  // Caller specific stuff
  if (action == 'get_events') {
    return(ajaxRequest.responseText);
  }
  if (action == 'store_event') {
    return(ajaxRequest.responseText);
  }
  if (action == 'delete_event') {
    return(ajaxRequest.responseText);
  }
}





//------------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------------

$(document).ready(function(){
    
  // Get all events
  get_events_f();

  // Show/hide time form
  $('#new_form_full_day').on('click', function() {
    $('.time').toggle();
  });

  // Autoformat date
  $(document).on('focusout', '#new_form_start_date, #new_form_stop_date', function() {

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

  // Autoformat time
  $(document).on('focusout', '#new_form_start_time, #new_form_stop_time', function() {

    $(this).css('background-color', '#fff');

    var pattern1 = /^(\D*)(\d)(\D*)$/;
    var pattern2 = /^(\D*)(\d\d)(\D*)$/;
    var pattern3 = /^(\D*)(\d)(\D*)(\d\d)(\D*)$/;
    var pattern4 = /^(\D*)(\d\d)(\D*)(\d\d)(.*)$/;

    if ( pattern1.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern1, "0$2:00"));
    }
    else if ( pattern2.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern2, "$2:00"));
    }
    else if ( pattern3.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern3, "0$2:$4"));
    }
    else if ( pattern4.test($(this).val()) ) {
      $(this).val($(this).val().replace(pattern4, "$2:$4"));
    }
    else if ($(this).val() != "") {
      $(this).css('background-color', '#ffcccc');
    }
  });

  // Copy start date to stop date
  $(document).on('focusout', '#new_form_start_date', function() {
    if ($('#new_form_stop_date').val() == "") {
      $('#new_form_stop_date').val($('#new_form_start_date').val());
    }
  });

});



//------------------------------------------------------------------------------------
// Get events from database
//------------------------------------------------------------------------------------

function get_events_f() { 

  // Ajax call
  var row = ajax_f('get_events',  '');
  
  // Entries are separated by '|'
  var rows = row.split('|'); 

  // Create html string
  var html = "";

  if (rows != "") {
    rows.forEach(function(entry) {

      // Replace newlines: '\n' -> '<br>'
      //      rows[rowNr] = rows[rowNr].replace(/\n/g,"<br>");
    
      // Ajax fetches the result as hash. mkhash parses the hash.
      var myhash = eval( "mkhash(" + entry + ")" ); 
      
      // Unless record is public, user must be logged in
      if ( (myhash['public'] == '1') || G_LOGGED_IN ) {
      
        // Public/Members icon
        if (myhash['public'] == '1') { 
          var icon = "icon-unlock icon-st-green";
          var tip  = "tip_public";
        }
        else { 
          var icon = "icon-lock icon-st-red";
          var tip  = "tip_member";
        }
        
        function get_wday_f(day) {
          if      (day == 0) { return "Söndag";  }
          else if (day == 1) { return "Måndag";  }
          else if (day == 2) { return "Tisdag";  }
          else if (day == 3) { return "Onsdag";  }
          else if (day == 4) { return "Torsdag"; }
          else if (day == 5) { return "Fredag";  }
          else if (day == 6) { return "Lördag";  }
        }

        // Date/time string
        var start_date = new Date(myhash['start_date']);
        var stop_date  = new Date(myhash['stop_date']);
        var start_date_day = get_wday_f(start_date.getDay());
        var stop_date_day  = get_wday_f(stop_date.getDay());

        var date_time_str = "";
        if (myhash['start_date'] == myhash['stop_date']) {
          date_time_str = start_date_day +" "+ myhash['start_date'];
          if (myhash['start_time'] != "") {
            date_time_str += ", kl " + myhash['start_time'];
          }          
          if (myhash['stop_time'] != "") {
            date_time_str += "-" + myhash['stop_time'];
          }          
        }
        else {
          date_time_str += start_date_day +" "+ myhash['start_date'];
          if (myhash['start_time'] != "") {
            date_time_str += ", kl " + myhash['start_time'];
          }
          date_time_str += " - " + stop_date_day +" "+ myhash['stop_date'];
          if (myhash['stop_time'] != "") {
            date_time_str += ", kl " + myhash['stop_time'];
          }          
        }

        // The html string
        html += "<div class='events_wrapper'>";
        if (G_LOGGED_IN) {
          html += "<div style='float: right;'>";
          if (G_CAN_WRITE) {
            html += "<span class='icon-remove icon-large icon-st-red icon-st-click' onmouseover=\"Tip(tip_delete)\" onmouseout=\"UnTip()\" onclick=\"delete_confirm_f(" + myhash['id'] + ",'" + myhash['heading'] + "','" + myhash['name'] + "','" + myhash['created'] + "');\"></span> \
                     <span>&nbsp;</span> \
                     <span>&nbsp;</span>";
          }
          html +=   "<span class='" + icon + " icon-large' onmouseover=\"Tip(" + tip + ")\" onmouseout=\"UnTip()\"></span> \
                   </div>";
        }
        html += "  <p class='events_h'>" + myhash['heading'] + "</p> \
                   <p class='events_date'><span class='icon-time icon-st-lightblue icon-st-shadow'>&nbsp;</span>" + date_time_str      + "</p>";
        if (myhash['location'] != "") {
          html += "<p class='events_loc'> <span class='icon-home icon-st-lightblue icon-st-shadow'>&nbsp;</span>" + myhash['location'] + "</p>";
        }
        if (myhash['text'] != "") {
          html += "<p class='events_border'></p>";
          html += "<p class='events_text'>" + myhash['text']     + "</p>";
        }

        var i = 0;
        while ((typeof myhash['doc_id_'+i] != 'undefined') && (myhash['doc_id_'+i] != '')) {
          if (i == 0) {
            html += "<p class='events_border'></p>";
          }
          html += "<p class='events_file'>";
          html += "<span class='icon-file icon-st-lightblue  icon-st-click'></span><span>&nbsp;&nbsp;&nbsp;</span>";
          html += "<a href=\"" + CCM_REL +"/"+ myhash['doc_path_' +i] + "\">" + myhash['doc_name_' + i] + "</a>";
          html += "<span>&nbsp;&nbsp;&nbsp;" + myhash['doc_size_'+ i] + "</span>";
          html += "</p>";
          i++;
        }
        html += "</div>";
      }
    });
  }

  // Insert table rows into html
  $('#events').html(html);
}



//------------------------------------------------------------------------------------
// New
//------------------------------------------------------------------------------------

function new_f() {
  
  // Show the form popup
  $('#new_popup, #overlay').popShow();
  $('#new_popup').popCenter();
	$('#new_form_name').focus();
  
  // Resize form
  resize_f();

  // Attach date picker to form
  $('#new_form_start_date').glDatePicker();
  $('#new_form_stop_date').glDatePicker();

}


// Insert into table 'calendar', then call file upload function
function new_submit_f() {

  // Validate
  $('#error').html("");
  var error = "";

  if ( $('#new_form_name').val() == "" ) {
    error += "* 'Postat av' är obligatoriskt <br>";
  }
  if ( $('#new_form_heading').val() == "" ) {
    error += "* 'Rubrik' är obligatorisk <br>";
  }
  if ( $('#new_form_start_date').val() == "" ) {
    error += "* 'Startdatum' är obligatoriskt <br>";
  }
  if ( $('#new_form_stop_date').val() == "" ) {
    error += "* 'Slutdatum' är obligatoriskt <br>";
  }
  if ( $('#new_form_stop_date').val() < $('#new_form_start_date').val() ) {
    error += "* 'Slutdatum kan inte vara tidigare än startdatumet <br>";
  }
  if ( !$('#new_form_full_day').is(':checked') && $('#new_form_start_time').val() == "" ) {
    error += "* 'Välj heldag om du inte vill ha en starttid <br>";
  }
  if ( $('input:radio[name=new_public]:checked').length == 0 ) {
    error += "* 'Publik' är ett obligatoriskt fält <br>"
  }

  // If no errors, insert into database
  if (error == "") {

    // Query string
    var qstr = "&page_id="    + G_PAGE_ID +
               "&name="       + $('#new_form_name').val() + 
               "&username="   + G_USERNAME + 
               "&start_date=" + $('#new_form_start_date').val() + 
               "&start_time=" + $('#new_form_start_time').val() +
               "&stop_date="  + $('#new_form_stop_date').val() +
               "&stop_time="  + $('#new_form_stop_time').val() +
               "&heading="    + $('#new_form_heading').val() + 
               "&location="   + $('#new_form_location').val() + 
               "&text="       + $('#new_form_text').val().replace(/(\r\n|\n|\r)/gm,"<br>") + 
               "&approved="   + G_CAN_WRITE + 
               "&public="     + $('input:radio[name=new_public]:checked').val();

    // Ajax call
    var result = ajax_f('store_event', qstr);

    // Check result, if a number (the database row ID), upload files (if any)
    if (isNaN(result)) {
      alert('Something went wrong!\n' + result);
    }
    else {

      var has_file = false;
      $('.fileToUpload').each(function(index) {
        if ( $(this).val() != "" ) {
          has_file = true;
          var file_id   = $(this).attr('id');
          var file_name = $(this).val();
          ajaxFileUpload(result, file_id, file_name);
        }
      });
    }
  }
  else {
    $('#error').append(error);
  }

  // If no file and no error, reload. (If file, reload is handled elsewhere)
  if (! has_file && $('#error').html() == "") {
    new_done_f();
  }
}


// Special ajax function for file upload
function ajaxFileUpload(id, file_id, file_name)	{

  // Query string
  var qstr = "?C5_URL=" + CCM_REL + "&action=store_event_doc&id_event="+id+"&file_id="+file_id;

  // Upload the file
  $.ajaxFileUpload({
    url:           CCM_REL + "/index.php/tools/medlemmar/kalender_db.php" + qstr,
    secureuri:     false,
    fileElementId: file_id,
    dataType:      'text',
    success:       function (data) {
                     data = data.replace(/(\r\n|\n|\r)/gm,"");
                     if (data != 'ok') {
                       if (data == '1') {
                         $('#error').append('* Filen är för stor. (' + file_name + ')<br>' );
                       }
                       else {
                         $('#error').append('* Något gick fel: ' + data + " (" + file_name + ')<br>');
                       }
                       delete_f(id);
                     }
                     else {
                       new_done_f();
                     }
                   },
        error:     function (e)	{
                     $('#error').append('* Något gick fel: ' + e + " (" + file_name + ')<br>');
                     delete_f(id);
                   }
  })
}  

// Copy from hidden file field
$(document).on('change', '.fileToUpload', function(event) {
    var path = $(this).parent().find('.fileToUpload').val();
    path = path.replace(/^.*[\\\/]/, '');
    $(this).parent().find('.new_attachment_val').val(path);
});


// Reset form, hide popup, reload events
function new_done_f() {
  $('#new_form').find("input, textarea").not("#new_form_name").val("");
  $('#new_form').find("input:radio").prop('checked', false);
  $('#new_form').find("input:checkbox").prop('checked', false);
  $('.time').show();
  $('.popup, #overlay').hide();
  get_events_f();
}


// Center popup on resize
$(window).resize(function() { 
  $('.popup:visible').popCenter();
});


// Resize som inputs
$(window).resize(function() { 
  resize_f();
});

function resize_f() {
  var width_1 = $('#new_form').width() - $('.c1').width() -20;
  $('#new_form_heading').width(width_1);
  $('#new_form_location').width(width_1);
  $('#new_form_text').width(width_1);
  $('.file_div').width(width_1 +10);

  var width_2 = $('.file_div').width() -110;
  $('.new_attachment_val').width(width_2);
};



//------------------------------------------------------------------------------------
// New - Add/Remove file row
//------------------------------------------------------------------------------------

// --- Add row ---

var liInc = 1;
function duplicate_f(id) {
  
  var oldLi      = $('#' + id);
  var newLiClone = $('#' + id).clone().attr('id', 'file_' + liInc);
  var newLiInp   = newLiClone.find('input');
  
  // Increment id, name and tabindex on all <input>
  newLiInp.each(function(i) {
    var idNr             = newLiInp[i].id.match(/[0-9]*$/);
    var newIdNr          = parseInt(idNr) + parseInt(1);
    newLiInp[i].id       = newLiInp[i].id.replace(/[0-9]$/, newIdNr);
    newLiInp[i].name     = newLiInp[i].name.replace(/[0-9]$/, newIdNr);
    newLiInp.eq(i).attr('tabindex', $(this).attr('tabindex') +3);
  });

  // Insert the new <li>
  newLiClone.insertAfter('#' + id);
    
  // Clear input fields
  newLiInp.val('');

  // Set button text (cleared just above)
  $('.browse').val('Välj..');

  // Add class 'dyn' to all dynamically added content.
  var newLi = $('#li_' + liInc);
  newLi.addClass('dyn');

  // Hide '+' buttons
  oldLi.find('span:eq(1)').hide();
  
  // Increment <li> counter
  liInc++;
}


// --- Remove row ---
function remove_f(id) {

  var currLi   = $('#' + id);
  var prevLi   = $('#' + id).prev();
  var prevLi2  = $('#' + id).prev().prev();
  var prevLiId = $('#' + id).prev().attr('id');
  
  // Show "Add" button in previous <li>
  prevLi.find('span:eq(1)').show();


  // Remove/clear <li>
  var file_elems = $('.file_li').length
  if (file_elems > 1) {
    currLi.remove();
  }
  else {
    currLi.find('input').val('');
    $('.browse').val('Välj..');  // Set button text (cleared just above)
  }
}




//------------------------------------------------------------------------------------
// Delete
//------------------------------------------------------------------------------------

function delete_confirm_f(id, heading, name, created) {
  $('#delete_heading').html(heading);
  $('#delete_name').html(name);
  $('#delete_created').html(created);
  $('#delete_id').val(id);
  $('#delete_popup, #overlay').popShow();
  $('#delete_popup').popCenter();
}


function delete_f(id) {

  // Ajax call
  var success = ajax_f('delete_event', "&id="+id);

  // Check status. If ok, upload files (if any)
  if (success != 'ok') {
    alert('Something went wrong!\n' + success);
  }
  else {
    $('.popup, #overlay').hide();
    get_events_f();
  }
}







//************************************************************************************
// Tooltips
//************************************************************************************

  var tip_delete     = "Radera";
  var tip_edit       = "Ändra";
  var tip_public     = "Synlig för alla";
  var tip_member     = "Endast synlig för medlemmar";

  var tip_more_files = "Lägg till fler bilagor";
  var tip_less_files = "Ta bort bilaga";


