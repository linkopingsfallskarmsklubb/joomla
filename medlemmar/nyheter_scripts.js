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

var busy = false;
function ajax_f(action, qstr) {

  // Ignore ajax call if busy
  if (! busy) {
    busy = true;

    // Start loading animation
    $('#spinner').trigger('ajaxSend');
  
    // Ajax Request variable by browser
    ajax_request_f();

    // Create a function that will receive data sent from the server.
    ajaxRequest.onreadystatechange = function() {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
        // Stop loading animation
        $('#spinner').trigger('ajaxStop');
        // Now where not busy anymore
        busy = false;
      }
    }

    // Now get the value from user and pass it to server script (the id can also be a date).
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/index.php/tools/medlemmar/nyheter_db.php" + queryString, false);
    ajaxRequest.send(null); 
  
    // Caller specific stuff
    if (action == 'get_news') {
      return(ajaxRequest.responseText);
    }
    if (action == 'store_news') {
      return(ajaxRequest.responseText);
    }
    if (action == 'delete_news') {
      return(ajaxRequest.responseText);
    }
  }
}




//------------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------------

// Get news when document loads
$(document).ready(function(){
  get_news_f(true);
});


// Get more news when scrolling to the bottom
$(document).scroll(function() {
  if ($(window).scrollTop() + $(window).height() >= getDocHeight() -50 ) {
    get_news_f(false);
  }
});

function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
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
  $('#new_form_text').width(width_1);
  $('.file_div').width(width_1 +10);

  var width_2 = $('.file_div').width() -110;
  $('.new_attachment_val').width(width_2);
};




//------------------------------------------------------------------------------------
// Get news from database
//------------------------------------------------------------------------------------

var start   = 0;
var nr_rows = 10;
var done    = false;
function get_news_f(first) { 

  // Reset counters
  if (first) {
    start   = 0;
    nr_rows = 10;
    done    = false;
  }

  if (! done || first) {

    // Ajax call
    var row = ajax_f('get_news',  '&start='+ start +'&nr_rows=' + nr_rows);
    start = start + nr_rows;

    // Check if we reach end of list
    if (row == "end") {
      done = true;
      return;
    }

    // Entries are separated by '|'
    var rows = row.split('|'); 

    // Create html string
    var html = "";
    rows.forEach(function(entry) {

      // Replace newlines: '\n' -> '<br>'
      entry = entry.replace(/\n/g,"<br>");

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
        
        // The html string
        html += "<div class='news_wrapper'>";
        if (G_LOGGED_IN) {
          html += "<div style='float: right;'>";
          if (G_CAN_WRITE) {
            html += "<span class='icon-remove icon-large icon-st-red        icon-st-click' onmouseover=\"Tip(tip_delete)\" onmouseout=\"UnTip()\" onclick=\"delete_confirm_f(" + myhash['id'] + ",'" + myhash['heading'] + "','" + myhash['name'] + "','" + myhash['created'] + "');\"></span> \
                     <span>&nbsp;</span> \
                     <span>&nbsp;</span>";
          }
                     //<span class='icon-edit   icon-large icon-st-lightblue  icon-st-click' onmouseover=\"Tip(tip_edit)\" onmouseout=\"UnTip()\"></span> \
          html +=   "<span class='" + icon + " icon-large' onmouseover=\"Tip(" + tip + ")\" onmouseout=\"UnTip()\"></span> \
                   </div>";
        }
        html += "  <p class='news_h'>"    + myhash['heading']  + "</p> \
                   <p class='news_info'>" + myhash['name']     + "</p> \
                   <p class='news_info'>" + myhash['created']  + "</p> \
                   <p class='news_border'></p> \
                   <p class='news_text'>" + myhash['text']     + "</p>";

        var i = 0;
        if ((typeof myhash['doc_id_'+i] != 'undefined') && (myhash['doc_id_'+i] != '')) {
          html += "<div class='events_border'></div>";
        }
        while ((typeof myhash['doc_id_'+i] != 'undefined') && (myhash['doc_id_'+i] != '')) {
          if (i == 0) {
            html += "<p class='news_border'></p>";
          }
          html += "<p class='news_file'>";
          html += "<span class='icon-file icon-st-lightblue  icon-st-click'></span><span>&nbsp;&nbsp;&nbsp;</span>";
          html += "<a href=\"" + CCM_REL +"/"+ myhash['doc_path_' +i] + "\">" + myhash['doc_name_' + i] + "</a>";
          html += "<span>&nbsp;&nbsp;&nbsp;" + myhash['doc_size_'+ i] + "</span>";
          html += "</p>";
          i++;
        }
        html += "</div>";
      }
    });

    // Insert table rows into html
    if (first) {
      $('#news').html(html);
    }
    else {
      $('#news').append(html);
    }
  }
}


//------------------------------------------------------------------------------------
// New
//------------------------------------------------------------------------------------

function new_f() {
  $('#new_popup, #overlay').popShow();
  $('#new_popup').popCenter();
	$('#new_form_heading').focus();
  resize_f();
}


// Copy from hidden file field
$(document).on('change', '.fileToUpload', function(event) {
    var path = $(this).parent().find('.fileToUpload').val();
    path = path.replace(/^.*[\\\/]/, '');
    $(this).parent().find('.new_attachment_val').val(path);
});


// Insert into table 'news', then call file upload function
function new_submit_f() {

  // Validate
  $('#error').html("");
  var error = "";

  if ( $('#new_form_name').val() == "" ) {
    error += "* 'Postat av' är ett obligatoriskt fält <br>";
  }
  if ( $('#new_form_heading').val() == "" ) {
    error += "* 'Rubrik' är ett obligatoriskt fält <br>";
  }
  if ( $('#new_form_text').val() == "" ) {
    error += "* 'Text' är ett obligatoriskt fält <br>";
  }
  if ( $('input:radio[name=new_public]:checked').length == 0 ) {
    error += "* 'Publik' är ett obligatoriskt fält <br>"
  }

  // If no errors, insert into database
  if (error == "") {

    // Query string
    var qstr = "&page_id="           + G_PAGE_ID +
               "&name="              + $('#new_form_name').val() + 
               "&username="          + G_USERNAME + 
               "&heading="           + $('#new_form_heading').val() + 
               "&text="              + $('#new_form_text').val().replace(/(\r\n|\n|\r)/gm,"<br>") + 
               "&approved="          + G_CAN_WRITE + 
               "&public="            + $('input:radio[name=new_public]:checked').val();

    // Ajax call
    var result = ajax_f('store_news', qstr);

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
    new_complete_f();
  }
}


// Special ajax function for file upload
function ajaxFileUpload(id, file_id, file_name)	{

  // Query string
  var qstr = "?C5_URL=" + CCM_REL + "&action=store_news_doc&id_news="+id+"&file_id="+file_id;

  // Upload the file
  $.ajaxFileUpload({
    url:           CCM_REL + "/index.php/tools/medlemmar/nyheter_db.php" + qstr,
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
                       new_complete_f();
                     }
                   },
        error:     function (e)	{
                     $('#error').append('* Något gick fel: ' + e + " (" + file_name + ')<br>');
                     delete_f(id);
                   }
  })
}  


// Reset form, hide popup, reload news
function new_complete_f() {
  $('#new_form').find("input, textarea").not("#new_form_name").val("");
  $('#new_form').find("input:radio").prop('checked', false);
  $('.popup, #overlay').hide();
  get_news_f(true);
}


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
  var success = ajax_f('delete_news', "&id="+id);

  // Check status. If ok, upload files (if any)
  if (success != 'ok') {
    alert('Something went wrong!\n' + success);
  }
  else {
    get_news_f(true);
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


