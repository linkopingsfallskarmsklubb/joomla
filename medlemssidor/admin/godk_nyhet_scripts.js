//************************************************************************************
// Note! Some function that are common to several section of the site can
// be found in '/includes/scripts.js'
//************************************************************************************


//------------------------------------------------------------------
// AJAX
//------------------------------------------------------------------

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

    // Now get the value from user and pass it to server script
    var queryString = "?action=" + action + "&C5_URL=" + CCM_REL + qstr;
    ajaxRequest.open("GET", CCM_REL + "/index.php/tools/medlemssidor/admin/godk_nyhet_db.php" + queryString, false);
    ajaxRequest.send(null); 

    // Caller specific stuff
    if (action == 'get_news') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'approve') {
      return(ajaxRequest.responseText);
    }
    else if (action == 'deny') {
      return(ajaxRequest.responseText);
    }

  }


//------------------------------------------------------------------
// Init
//------------------------------------------------------------------

$(document).ready(function(){
  get_news_f();
});


//------------------------------------------------------------------
// Main content
//------------------------------------------------------------------

function get_news_f() {

    // Ajax call
    var row = ajax_f('get_news', '');

    // Entries are separated by '|'
    var rows = row.split('|'); 

    // Create html string
    var html = "";

    // If any matches, iterate each record
    if (row) {
      for (rowNr in rows) {

        // Replace newlines: '\n' -> '<br>'
        rows[rowNr] = rows[rowNr].replace(/\n/g,"<br>");

        // Ajax fetches the result as hash. mkhash parses the hash.
        var myhash = eval( "mkhash(" + rows[rowNr] + ")" ); 
      

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
        html += "<div class='news_wrapper'> \
                   <div class='buttons'>      \
                     <input type='button' class='button' value='Godkänn' onclick=\"submit_f("+myhash['id']+",'"+myhash['heading']+"',true)\"  /> \
                     <input type='button' class='button' value='Avslå'   onclick=\"submit_f("+myhash['id']+",'"+myhash['heading']+"',false)\" /> \
                   </div> \
                   <p class='news_h'>"    + myhash['heading']  + "</p> \
                   <p class='news_info'>" + myhash['name']     + "</p> \
                   <p class='news_info'>" + myhash['created']  + "</p> \
                   <p class='news_icon " + icon + " icon-large' onmouseover=\"Tip(" + tip + ")\" onmouseout=\"UnTip()\"></p> \
                   <p class='news_border'></p> \
                   <p class='news_text'>" + myhash['text']     + "</p>";

        var i = 0;
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
    }

    // Insert table rows into html
    $('#news').html(html);

};


//------------------------------------------------------------------
// Submit
//------------------------------------------------------------------

function submit_f(id, heading, approve) {

  // Create query string: Id & list of groups with edit permission
  var qstr   = "&id=" + id + "&page_id=" + G_PAGE_ID;

  if (approve) {

    // Ajax call
    var status = ajax_f('approve', qstr);

    if (status == "ok") {
      get_news_f();
      $("#result").html("<p>\""+heading+"\"<br>Nyheten är godkänd.</p>");
    }
    else {
      alert("Something went wrong! \n" + status);
    }

  }
  else {

    // Ajax call
    var status = ajax_f('deny', qstr);

    // If everthing went ok 
    if (status == "ok") {
      get_news_f();
      $("#result").html("<p>\""+heading+"\"<br>Nyheten har raderats.</p>");
    }
    else {
      alert("Something went wrong! \n" + status);
    }
  }

}




//************************************************************************************
// Tooltips
//************************************************************************************

  var tip_public     = "Synlig för alla";
  var tip_member     = "Endast synlig för medlemmar";

