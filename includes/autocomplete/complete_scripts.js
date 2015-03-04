 
  //-------------------------------------------------------------------------
  // Global variables
  //-------------------------------------------------------------------------

  var complete_orgValue  = "";
  var complete_prevFocus = "";

  $(document).on("keydown", 'p, input', function() { complete_prevFocus = this.id; });


  //-------------------------------------------------------------------------
  // Attach event handlers
  //-------------------------------------------------------------------------

  // Do stuff when autocomplete input fields are accessed
  $(document).on('focus',   '[data-complete]', function(event) { $('#complete_options').remove(); complete_f($('#' + this.id).attr('data-complete'), this, event)});
  $(document).on('keydown', '[data-complete]', function(event) { if (event.which == 9)           {complete_f($('#' + this.id).attr('data-complete'), this, event)}});
  $(document).on('keyup',   '[data-complete]', function(event) { if (event.which != 9)           {complete_f($('#' + this.id).attr('data-complete'), this, event)}});
  $(document).on('click',   '[data-complete]', function(event) { complete_f($('#' + this.id).attr('data-complete'), this, event)});

  // Hide selection ox when clicking anywhere in the document (except the input boxes)
  $(document).on('click', function(event) {
    var attr = $('#'+event.target.id).attr('data-complete');
    if (typeof attr == 'undefined' || attr == false) {
      $('#complete_options').remove();
      $('[data-complete]').trigger('focusout');
    }
  });


  //-------------------------------------------------------------------------
  // Handle events when <input> is accessed 
  //-------------------------------------------------------------------------

  function complete_f(source, obj, event) {


    // Check if <input> was focused previously, onKewDown/onKeyUp issue
    if ((complete_prevFocus.match(/^opt_/) != null) && (event.which == 40)) {
      return;
    }

    // If undefined stuff, do nothing
    if ((!obj) || (!event)) {
   	 	return;
    }

    // Get object for id input box
    var id_obj = document.getElementById(obj.id.replace(/([0-9]*)$/, 'id_' + '$1'));

    // Only trigger on real characters
    if ((event.which < 32) || (event.which >= 33 && event.which <= 45) || (event.which >= 112 && event.which <= 123)) {

      // Esc
      if (event.which == 27) {
        $('#complete_options').remove();
        return;
      }

      // Tab
      else if (event.which == 9) {
        if ($('#'+id_obj.id).val() == "") {
          $('#'+obj.id).val("");
        }
        $('#complete_options').remove();
        return;
      }

      // Backspace
      else if (event.which == 8) {
      }

      // Down arrow, navigate the options list
      else if (event.which == 40) {
        event.preventDefault();
        if (document.getElementById("complete_options")) {
          complete_sel_f(obj, event, true, obj.id, id_obj.id)
          return;
        }
      }

      // Left Arrow
      else if (event.which == 37) {
        $('#complete_options').remove();
        obj.value = complete_orgValue;
        return;
      }

      // Left mouse button
      else if (event.which == 1) {
        $('#complete_options').remove();
      }

      // Other characters that are not of interest
      else {
        return;
      }
    }


    // --- Traverse the options ---

    var hits = false;
    var html = "";

    // Variable content from variable name string
    var source = window[source];

    // Reset id form
    $('#'+id_obj.id).val("");


    // Do the traversing
    for (var i = 0; i < source.length; i++) {
      // Remove leading space
      if (obj.value[0] == " ") { obj.value = obj.value.substr(1,obj.value.length);};
      // Match full string, set id without selecting from options
      if (source[i][0].toLowerCase() == obj.value.toLowerCase()) {
        $('#' + id_obj.id).val(source[i][1]);
      }
      // Match part of string
      if (source[i][0].toLowerCase().indexOf(obj.value.toLowerCase()) == 0) {
        hits = true;
        complete_orgValue = source[i][0].substr(0, obj.value.length);
        html += "<p id='opt_" + obj.id + "_" + source[i][1] + "' class='comp_list' tabindex='-1' ";
        html += "onKeyDown=\"complete_sel_f(this, event, false, '" + obj.id + "','" + id_obj.id + "')\" ";
        html += "onClick=\"complete_click_f(this, '" + obj.id + "','" + id_obj.id + "','" + source[i][1] + "')\" ";
        html += "<b>" + source[i][0].substr(0, obj.value.length) + "</b>" + source[i][0].substr(obj.value.length,100) + "</p>";
      }
    }

    // Write to and show options list
    if (hits == true) {
      create_div_f(obj.id, html);
    }

    // If no hits, remove options list.
    else {
      $('#complete_options').remove();
    }
  }

  
  //-------------------------------------------------------------------------
  // Create the options list
  //-------------------------------------------------------------------------

  function create_div_f(objId, html) {

    if (! document.getElementById('complete_options')) {

      var width = $('#'+objId).css('width');
      var o     = document.getElementById(objId);
      var left  = o.offsetLeft;
      while (o=o.offsetParent) {
        left += o.offsetLeft;
      }

      o  = document.getElementById(objId);
      var top = o.offsetTop;
      while (o = o.offsetParent) {
        top += o.offsetTop;
      }

      top   = top + document.getElementById(objId).offsetHeight + "px";
      left  = left + "px";

      var newdiv = document.createElement('div');

      newdiv.setAttribute('class','comp_list');
      newdiv.setAttribute('id', 'complete_options');
      newdiv.style.left       = left;
      newdiv.style.top        = top;
      newdiv.style.width      = width;
      newdiv.tabIndex         = "-1";
      newdiv.innerHTML        = html;
      
      document.body.appendChild(newdiv); 
    }
    else {
      $('#complete_options').html(html);
    }

  }


  //-------------------------------------------------------------------------
  // Navigate the options list
  //-------------------------------------------------------------------------

  function complete_sel_f(obj, evt, inpcaller, inpObjId, id_inpObjId) {

    // If called from <input>, set obj to first option
    if (inpcaller == true) {
      obj     = document.getElementById('complete_options').getElementsByTagName("p")[0];
      newNode = obj;
    }

    // Called from focused option
    else { 

      // All options
      var allP = obj.parentNode.getElementsByTagName("p");

      // Esc
      if (evt.which == 27) {
        $('#complete_options').remove();
        document.getElementById(inpObjId).value = complete_orgValue;
        document.getElementById(id_inpObjId).value = "";
        document.getElementById(inpObjId).focus(); // !!!
      }

      // --- Tab ---
      else if (evt.which == 9) {
        document.getElementById(inpObjId).focus();
        $('#complete_options').remove();
      }
      
      // --- Return ---
      else if (evt.which == 13) {
        var next_inp = $('#'+inpObjId).attr('tabindex') +1;
        $('#'+inpObjId).focus();
        $('[tabindex=' + next_inp + ']').focus();
        $('#complete_options').remove();
      }

      // --- Left arrow ---
      else if (evt.which == 37) {
        evt.preventDefault();
        document.getElementById(inpObjId).value = complete_orgValue;
        document.getElementById(id_inpObjId).value = "";
        document.getElementById(inpObjId).focus();
      }

      // --- Right arrow ---
      else if (evt.which == 39) {
        document.getElementById(inpObjId).focus();
        $('#complete_options').remove();
      }

      // --- Down arrow ---
      else if (evt.which == 40) {
        
        // Prevent scroll by arrow key
        evt.preventDefault();

        // If last option, go back to <input>
        if (obj == allP[allP.length-1]) {
          document.getElementById(inpObjId).value = complete_orgValue;
          document.getElementById(id_inpObjId).value = "";
          document.getElementById(inpObjId).focus();
          return;
        }
        
        // Get next sibling
        nextS = obj.nextSibling;
        while (nextS.nodeType != 1) {
          nextS = nextS.nextSibling;
        }
        
        // Assign new node to a variable
        var newNode = document.getElementById(nextS.id);
        
      }

      // --- Up arrow ---
      else if (evt.which == 38) {
        
        // Prevent scroll by arrow key
        evt.preventDefault();
          
        // If first option, go back to <input>
        if (obj == allP[0]) {
          document.getElementById(inpObjId).value = complete_orgValue;
          document.getElementById(id_inpObjId).value = "";
          document.getElementById(inpObjId).focus();
          return;
        }
      
        // Get previous sibling
        prevS = obj.previousSibling;
        while (prevS.nodeType != 1) {
          prevS = prevS.previousSibling;
        }

        // Assign new node to a variable
        var newNode = document.getElementById(prevS.id);

      }
    }

    // Focus new node
    document.getElementById(newNode.id).focus();
    
    // Copy current option to <input> 
    document.getElementById(inpObjId).value    = newNode.innerHTML.replace(/<(?:.|\n)*?>/gm, '');
    document.getElementById(id_inpObjId).value = newNode.id.match(/\d+$/);

  }


  //-------------------------------------------------------------------------
  // Mouse click select
  //-------------------------------------------------------------------------

  function complete_click_f(obj, inpObjId, id_inpObjId, newId) {
    $('#'+inpObjId).val($(obj).html());
    $('#'+inpObjId).focus();
    $('#complete_options').remove();
    $('#'+id_inpObjId).val(newId);
  }
