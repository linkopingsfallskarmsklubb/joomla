  //******************************************************************
  // jQuery plugins
  //******************************************************************

// TODO(bluecmd)
var CCM_REL = "/marten";

  // Regex selector
  jQuery.expr[':'].regex = function(elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ? 
                        matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels,'')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
  }



  $.fn.reverse = [].reverse; 


  // Center element
  $.fn.center = function () {
    this.css("position","absolute");
    this.css("top",  Math.max(0, (($(window).height() - this.outerHeight(true)) / 2) + $(window).scrollTop())  + "px");
    this.css("left", Math.max(0, (($(window).width()  - this.outerWidth(true))  / 2) + $(window).scrollLeft()) + "px");
    return this;
  }


  // Center popup
  $.fn.popCenter = function () {

    // Center popup
    this.css("position","absolute");
    this.css("top",  Math.max(0, (($(window).height() - this.outerHeight(true)) / 2) + $(window).scrollTop())  + "px");
    this.css("left", Math.max(0, (($(window).width()  - this.outerWidth(true))  / 2) + $(window).scrollLeft()) + "px");

    // Move popup to 'body'
    $(this).appendTo('body');
    //$(this).clone(true, true).appendTo('body');
    //$(this).remove();

    // Set wrapper size (concrete specific theme)
    wrapper_height_f();

    return this;
    
  }


  // Popup show/hide
  // - Normal show/hide but also resize wrapper
  // - Not portable (use concrete specific theme)
  $.fn.popShow = function () {
    $(this).show();
    wrapper_height_f();
  }

  $.fn.popHide = function () {
    $(this).hide();
    wrapper_height_f();
  }

  // Max width/height
  $.fn.maxWidth = function() {
    var max = 0;
    this.each(function() {
      max = Math.max(max, $(this).width());
    });
    return max;
  };

  $.fn.maxHeight = function() {
    var max = 0;
    this.each(function() {
      max = Math.max(max, $(this).height());
    });
    return max;
  };


  //******************************************************************
  // Other useful functions
  //******************************************************************

  // Get scrollbar width
  // - Useful for adding padding to auto overflow divs
  function scrollbarWidth() {
    var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
        $outer = jQuery('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
        inner = $inner[0],
        outer = $outer[0];
     
    jQuery('body').append(outer);
    var width1 = inner.offsetWidth;
    $outer.css('overflow', 'scroll');
    var width2 = outer.clientWidth;
    $outer.remove();
 
    return (width1 - width2);
  }


  // Remove multiple, leading or trailing spaces
  function trim(s) {
	  s = s.replace(/(^\s*)|(\s*$)/gi,"");
	  s = s.replace(/[ ]{2,}/gi," ");
	  s = s.replace(/\n /,"\n");
	  return s;
  }



  //******************************************************************
  // Ajax Request by browser
  //******************************************************************

  var ajaxRequest;  // The variable that makes Ajax possible!
  function ajax_request_f() {
    try {
      // Opera 8.0+, Firefox, Safari
      ajaxRequest = new XMLHttpRequest();
    } catch (e) {
      // Internet Explorer Browsers
      try {
        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
         try {
           ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         } catch (e) {
           // Something went wrong
           alert("Your browser broke!");
           return false;
         }
      }
    }
  }



  //******************************************************************
  // Ajax animation block
  //******************************************************************

  function ajax_anim() {

    var html = "<div id='spinner' class='spinner' style='display: none; z-index: 200;'> \
                  <div id='circularG'> \
                    <div id='circularG_1' class='circularG'> \
                    </div> \
                    <div id='circularG_2' class='circularG'> \
                    </div> \
                    <div id='circularG_3' class='circularG'> \
                    </div> \
                    <div id='circularG_4' class='circularG'> \
                    </div> \
                    <div id='circularG_5' class='circularG'> \
                    </div> \
                    <div id='circularG_6' class='circularG'> \
                    </div> \
                    <div id='circularG_7' class='circularG'> \
                    </div> \
                    <div id='circularG_8' class='circularG'> \
                    </div> \
                  </div> \
                </div>";

    $('body').append(html);

    $('#spinner').center();
    $("#spinner").bind("ajaxSend", function() {
       $(this).show();
    }).bind("ajaxStop", function() {
       $(this).hide();
    }).bind("ajaxError", function() {
      $(this).hide();
    })


  }


  //******************************************************************
  // Parse the hash that is fetched by ajax.
  //******************************************************************

  function mkhash( ) {
    var ret = new Object( );
    for (var i = 0; i < arguments.length; ++i ) {
      ret[arguments[i][0]] = arguments[i][1];
    }
    return ret;
  }


