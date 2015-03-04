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
        if (action == 'get_all_pilots')        { list_pilots_f(ajaxRequest.responseText); }  
        if (action == 'get_all_photographers') { list_photographers_f(ajaxRequest.responseText); }  
      }
    }

    // Now get the value from user and pass it to server script
    var queryString = "?C5_URL=" + CCM_REL + "&action=" + action + qstr;
    ajaxRequest.open("GET", CCM_REL + "/single_pages/medlemssidor/tandembokning/piloter_fotografer_db.php" + queryString, false);
    ajaxRequest.send(null); 


    // Caller specific stuff
    if (action == 'new_pilot') {
      return(ajaxRequest.responseText);
    }
    if (action == 'new_photo') {
      return(ajaxRequest.responseText);
    }
    if (action == 'get_pilot') {
      return(ajaxRequest.responseText);
    }
    if (action == 'edit_pilot') {
      return(ajaxRequest.responseText);
    }
    if (action == 'get_photo') {
      return(ajaxRequest.responseText);
    }
    if (action == 'edit_photo') {
      return(ajaxRequest.responseText);
    }
  }




  //------------------------------------------------------------------
  // Init
  //------------------------------------------------------------------

  window.onload="document.forms['form_new'].reset()"

  // Get all entries
  $(document).ready(function(){
    ajax_f('get_all_pilots', '');
    ajax_f('get_all_photographers', '');
  });


  //------------------------------------------------------------------
  // List all pilots
  //------------------------------------------------------------------

  function list_pilots_f(row) {

    // Entries are separated by '|'
    var rows = row.split('|'); 

    var html = "";
    for (row in rows) {

      // Ajax fetches the result as hash. mkhash parses the hash.
      var myhash = eval( "mkhash(" + rows[row] + ")" ); 

      if (myhash['aktiv'] == '1') {
        var aktiv = "<span class='icon-ok icon-large icon-st-green icon-st-shadow'>";
      }
      else {
        var aktiv = "&nbsp;";
      }

      html += "<tr> \
                 <td align='center'><span class='icon-edit   icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"edit_pilot_f(" + myhash['id'] + ");\"></span></td> \
                 <td align='center'>"+ aktiv +"</td> \
                 <td>"+ myhash['fornamn'] +" "+ myhash['efternamn'] +"</td> \
                 <td>"+ myhash['maxlangd'] +" cm</td> \
                 <td>"+ myhash['maxvikt'] +" kg</td> \
                 <td>"+ myhash['tid_mellan'] +" min</td> \
               </tr>";
    }

    // Insert table
    $('#p_table tbody').html(html);
  }


  //------------------------------------------------------------------
  // List all photographers
  //------------------------------------------------------------------

  function list_photographers_f(row) {

    // Entries are separated by '|'
    var rows = row.split('|'); 

    var html = "";
    for (row in rows) {

      // Ajax fetches the result as hash. mkhash parses the hash.
      var myhash = eval( "mkhash(" + rows[row] + ")" ); 

      if (myhash['aktiv'] == '1') {
        var aktiv = "<span class='icon-ok icon-large icon-st-green icon-st-shadow'>";
      }
      else {
        var aktiv = "&nbsp;";
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



      html += "<tr> \
                 <td align='center'><span class='icon-edit   icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick=\"edit_photo_f(" + myhash['id'] + ");\"></span></td> \
                 <td align='center'>"+ aktiv +"</td> \
                 <td>"+ myhash['fornamn'] +" "+ myhash['efternamn'] +"</td> \
                 <td align='center'>"+ video +"</td> \
                 <td align='center'>"+ foto +"</td> \
               </tr>";
    }

    // Insert table
    $('#f_table tbody').html(html);
  }



  //------------------------------------------------------------------
  // New pilot
  //------------------------------------------------------------------

  function new_pilot_f() {
    $('#new_pilot, #overlay').popShow();
    $('#new_pilot').popCenter();
  }

  function new_pilot_submit_f() {

    var certnr     = $('#new_pilot_certnr').val();
    var fornamn    = $('#new_pilot_fornamn').val();
    var efternamn  = $('#new_pilot_efternamn').val();
    var maxlangd   = $('#new_pilot_maxlangd').val();
    var maxvikt    = $('#new_pilot_maxvikt').val();
    var tid_mellan = $('#new_pilot_tid_mellan').val();
    var qstr       = "&certnr="+certnr+"&fornamn="+fornamn+"&efternamn="+efternamn+"&maxlangd="+maxlangd+"&maxvikt="+maxvikt+"&tid_mellan="+tid_mellan;

    // Validate input
    var valid  = true;

    if (certnr != "") {
      var value = certnr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#new_pilot_certnr').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#new_pilot_certnr').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (fornamn == "") {
      $('#new_pilot_fornamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (efternamn == "") {
      $('#new_pilot_efternamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (maxlangd != "") {
      var value = maxlangd.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#new_pilot_maxlangd').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#new_pilot_maxlangd').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (maxvikt != "") {
      var value = maxvikt.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#new_pilot_maxvikt').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#new_pilot_maxvikt').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }


    // If valid form, insert into database
    if (valid) {
 
      var status = ajax_f('new_pilot', qstr);
      if (status != "ok") {
        alert(status);
      }
    
      // Hide popup
      $('#new_pilot, #overlay').popHide();

      // Update table
      ajax_f('get_all_pilots', '');
    }

  }


  //------------------------------------------------------------------
  // New photographer
  //------------------------------------------------------------------

  function new_photo_f() {
    $('#new_photo, #overlay').popShow();
    $('#new_photo').popCenter();
  }

  function new_photo_submit_f() {

    var certnr    = $('#new_photo_certnr').val();
    var fornamn   = $('#new_photo_fornamn').val();
    var efternamn = $('#new_photo_efternamn').val();
    var video     = ($('#new_photo_video').is(':checked') ? "1" : "0");
    var foto      = ($('#new_photo_foto').is(':checked') ? "1" : "0");
    var qstr      = "&certnr="+certnr+"&fornamn="+fornamn+"&efternamn="+efternamn+"&video="+video+"&foto="+foto;

    // Validate input
    var valid  = true;

    if (certnr != "") {
      var value = certnr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#new_photo_certnr').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#new_photo_certnr').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (fornamn == "") {
      $('#new_photo_fornamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (efternamn == "") {
      $('#new_photo_efternamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }


    // If valid form, insert into database
    if (valid) {
 
      var status = ajax_f('new_photo', qstr);
      if (status != "ok") {
        alert(status);
      }
    
      // Hide popup
      $('#new_photo, #overlay').popHide();

      // Update table
      ajax_f('get_all_photographers', '');
    }

  }



  //------------------------------------------------------------------
  // Edit pilot
  //------------------------------------------------------------------

  function edit_pilot_f(id) {

    // Show popup
    $('#edit_pilot, #overlay').popShow();
    $('#edit_pilot').popCenter();

    // Get stored data from database
    var pilot = ajax_f('get_pilot', '&id='+id);

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + pilot + ")" ); 

    // Insert stored data in form
    $('#ed_pilot_id').val(myhash['id']);
    $('#ed_pilot_certnr').val(myhash['certnr']);
    $('#ed_pilot_fornamn').val(myhash['fornamn']);
    $('#ed_pilot_efternamn').val(myhash['efternamn']);
    $('#ed_pilot_maxlangd').val(myhash['maxlangd']);
    $('#ed_pilot_maxvikt').val(myhash['maxvikt']);
    $('#ed_pilot_tid_mellan').val(myhash['tid_mellan']);
    $('#ed_pilot_aktiv').prop('checked', (myhash['aktiv'] == '1' ? true : false));
  }


  function edit_pilot_submit_f() {

    var id         = $('#ed_pilot_id').val();
    var certnr     = $('#ed_pilot_certnr').val();
    var fornamn    = $('#ed_pilot_fornamn').val();
    var efternamn  = $('#ed_pilot_efternamn').val();
    var maxlangd   = $('#ed_pilot_maxlangd').val();
    var maxvikt    = $('#ed_pilot_maxvikt').val();
    var tid_mellan = $('#ed_pilot_tid_mellan').val();
    var aktiv      = ($('#ed_pilot_aktiv').is(':checked') ? "1" : "0");
    var qstr       = "&id="+id+"&certnr="+certnr+"&fornamn="+fornamn+"&efternamn="+efternamn+"&maxlangd="+maxlangd+"&maxvikt="+maxvikt+"&tid_mellan="+tid_mellan+"&aktiv="+aktiv;

    // Validate input
    var valid  = true;

    if (certnr != "") {
      var value = certnr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#ed_pilot_certnr').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#ed_pilot_certnr').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (fornamn == "") {
      $('#ed_pilot_fornamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (efternamn == "") {
      $('#ed_pilot_efternamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (maxlangd != "") {
      var value = maxlangd.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#ed_pilot_maxlangd').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#ed_pilot_maxlangd').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (maxvikt != "") {
      var value = maxvikt.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#ed_pilot_maxvikt').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#ed_pilot_maxvikt').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    // If valid form, insert into database
    if (valid) {

      var status = ajax_f('edit_pilot', qstr);

      if (status != "ok") {
        alert(status);
      }
    
      // Hide popup
      $('#edit_pilot, #overlay').popHide();

      // Update table
      ajax_f('get_all_pilots', '');
    }
  }


  //------------------------------------------------------------------
  // Edit photographer
  //------------------------------------------------------------------

  function edit_photo_f(id) {

    // Show popup
    $('#edit_photo, #overlay').popShow();
    $('#edit_photo').popCenter();

    // Get stored data from database
    var photographer = ajax_f('get_photo', '&id='+id);

    // Ajax fetches the result as hash. mkhash parses the hash.
    var myhash = eval( "mkhash(" + photographer + ")" ); 

    // Insert stored data in form
    $('#ed_photo_id').val(myhash['id']);
    $('#ed_photo_certnr').val(myhash['certnr']);
    $('#ed_photo_fornamn').val(myhash['fornamn']);
    $('#ed_photo_efternamn').val(myhash['efternamn']);
    $('#ed_photo_video').prop('checked', (myhash['video'] == '1' ? true : false));
    $('#ed_photo_foto').prop('checked', (myhash['foto'] == '1' ? true : false));
    $('#ed_photo_aktiv').prop('checked', (myhash['aktiv'] == '1' ? true : false));

  }


  function edit_photo_submit_f() {

    var id        = $('#ed_photo_id').val();
    var certnr    = $('#ed_photo_certnr').val();
    var fornamn   = $('#ed_photo_fornamn').val();
    var efternamn = $('#ed_photo_efternamn').val();
    var video     = ($('#ed_photo_video').is(':checked') ? "1" : "0");
    var foto      = ($('#ed_photo_foto').is(':checked') ? "1" : "0");
    var aktiv     = ($('#ed_photo_aktiv').is(':checked') ? "1" : "0");
    var qstr      = "&id="+id+"&certnr="+certnr+"&fornamn="+fornamn+"&efternamn="+efternamn+"&video="+video+"&foto="+foto+"&aktiv="+aktiv;

    // Validate input
    var valid  = true;

    if (certnr != "") {
      var value = certnr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      var intRegex = /^\d+$/;
      if (!intRegex.test(value)) {
        $('#ed_photo_certnr').parent().append("<span class='validate'> Endast siffror</span>");
        valid = false;
      }
    } 
    else {
      $('#ed_photo_certnr').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (fornamn == "") {
      $('#ed_photo_fornamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }

    if (efternamn == "") {
      $('#ed_photo_efternamn').parent().append("<span class='validate'> Obligatoriskt</span>");
      valid = false;
    }


    // If valid form, insert into database
    if (valid) {

      var status = ajax_f('edit_photo', qstr);

      if (status != "ok") {
        alert(status);
      }
    
      // Hide popup
      $('#edit_photo, #overlay').popHide();

      // Update table
      ajax_f('get_all_photographers', '');
    }
  }













