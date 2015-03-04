
//************************************************************************************
// Draw calendar
//************************************************************************************

Date.prototype.getWeek = function() {
  var onejan = new Date(this.getFullYear(),0,1);
  return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
} 
  
var now     = new Date();
var year    = now.getFullYear();
var month   = now.getMonth();
var day     = now.getDay();

var prev    = new Date(now.getFullYear(),now.getMonth()-1,1);
var p_month = prev.getMonth();
var p_year  = prev.getFullYear();
  
  
function cal_draw_f(params) {
  
  if (params) {
    var nav     = params['nav'];
    var sel_cur = params['sel_cur'];
  }


  // Insert the calendar into html placeholder
  var html = "<div id='calendar_nav' class='calendar_nav'> \
                <div id='calendar_nav_left'  class='calendar_nav_left'> \
                  <div class='calendar_nav_img_cent'><img src='" + CCM_REL + "/single_pages/includes/calendar/arrow_left.png' onclick=\"cal_draw_f({nav : 'prev'});\" alt='Prev' /> \
                  </div> \
                </div> \
                <div id='calendar_nav_date' class='calendar_nav_date'> \
                </div> \
                <div id='calendar_nav_right' class='calendar_nav_right'> \
                  <div class='calendar_nav_img_cent'><img src='" + CCM_REL + "/single_pages/includes/calendar/arrow_right.png' onclick=\"cal_draw_f({nav : 'next'});\" alt='Next' /> \
                  </div> \
                </div> \
              </div> \
              <div id='calendar_wd' class='calendar_wd'></div> \
              <div id='calendar_wk' class='calendar_wk'></div> \
              <div id='calendar_cl' class='calendar_cl'></div>";

  document.getElementById('calendar').innerHTML = html;


  
  if (nav == 'prev') {
    year    = new Date(year, month-1,1).getFullYear();
    month   = new Date(year, month-1,1).getMonth();
    p_year  = new Date(p_year, p_month-1,1).getFullYear();
    p_month = new Date(p_year, p_month-1,1).getMonth();
  }
  else if (nav == 'next') {
    year    = new Date(year, month+1,1).getFullYear();
    month   = new Date(year, month+1,1).getMonth();
    p_year  = new Date(p_year, p_month+1,1).getFullYear();
    p_month = new Date(p_year, p_month+1,1).getMonth();
  }
 
  // Numeric month to long month
  var longMonth     = new Array(12);
      longMonth[0]  = "Januari";
      longMonth[1]  = "Februari";
      longMonth[2]  = "Mars";
      longMonth[3]  = "April";
      longMonth[4]  = "Maj";
      longMonth[5]  = "Juni";
      longMonth[6]  = "Juli";
      longMonth[7]  = "Augusti";
      longMonth[8]  = "September";
      longMonth[9]  = "Oktober";
      longMonth[10] = "November";
      longMonth[11] = "December";


  
  // Number of days in current month
  function daysInMonth(iMonth, iYear) {
    return 32 - new Date(iYear, iMonth, 32).getDate();
  }
  var monthDays = daysInMonth(month, year);
  
  
  // Number of days in previous month
  function daysInMonth(iMonth, iYear) {
    return 32 - new Date(iYear, iMonth, 32).getDate();
  }
  var p_monthDays = daysInMonth(p_month,p_year);
  
  
  
  // Last Monday of previous month
  var first_wd = new Date(year,month,01).getDay();
  if (first_wd == 0) {first_wd = 7;} // sunday=0,
  var extra_days      = first_wd -1;
  var start_month_day = p_monthDays - extra_days +1;
  
  
  
  // Sunday of last week in current month
  
  
  //-----------------------------------------
  // Define three different html strings
  //-----------------------------------------
  
  var html_wd = "";
  var html_wk = "";
  var html_cl = "";
  
  
  //-----------------------------------------
  // Weekday header
  //-----------------------------------------
  
  html_wd += "<div class='wd'>Må</div> \
              <div class='wd'>Ti</div> \
              <div class='wd'>On</div> \
              <div class='wd'>To</div> \
              <div class='wd'>Fr</div> \
              <div class='wd'>Lö</div> \
              <div class='wd'>Sö</div>";
  
  
  
  //-----------------------------------------
  // Previous month
  //-----------------------------------------
  
  // Container around dates (adds top/left border)
  html_cl = "<div class='cont_cell'>";
  
  var mnp = month;
  if (mnp < 10) {mnp = "0" + mnp; }
  
  if (extra_days > 0) {
    
    for (var i=start_month_day; i<=p_monthDays; i++) { 
      
      // Current week day
      var d = new Date(p_year, p_month, i);  
  
      if (d.getDay() == 1) {
        var weekdate = new Date(p_year, p_month, i);
        var weeknr   = weekdate.getWeek();
        html_wk += "<div class='wk'>v." + weeknr + "</div>";
        html_cl += "<div id='outer_" + year + "-" + mn + "-" + wd + "' class='cell other md'>";
        html_cl += "  <div class='cell_wrapper'>";
        html_cl += "    <div id='inner_" + year + "-" + mn + "-" + wd + "' class='innercell'>" + i + "</div>";
        html_cl += "  </div>";
        html_cl += "</div>";
      }
      else {
        html_cl += "<div id='outer_" + year + "-" + mn + "-" + wd + "' class='cell other'>";
        html_cl += "  <div class='cell_wrapper'>";
        html_cl += "    <div id='inner_" + year + "-" + mn + "-" + wd + "' class='innercell'>" + i + "</div>";
        html_cl += "  </div>";
        html_cl += "</div>";
      }
    }
  }
  

  //-----------------------------------------
  // Current month
  //-----------------------------------------
  for (var i=1; i<=monthDays; i++) { 
    
    // Pad day and month string
    var wd = i;
    var mn = month +1;
    if (wd < 10) {wd = "0" + wd; }
    if (mn < 10) {mn = "0" + mn; }
    
    // Current week day
    var d = new Date(year, month, wd);  
    
    // Different handling for Mondays
    if (d.getDay() == 1) {
      var weekdate = new Date(year, month, i);
      var weeknr   = weekdate.getWeek();
      html_wk += "<div class='wk'>v." + weeknr + "</div>";
      html_cl += "<div id='outer_" + year + "-" + mn + "-" + wd + "' class='cell std md'>";
      html_cl += "  <div class='cell_wrapper'>";
      html_cl += "    <div id='inner_" + year + "-" + mn + "-" + wd + "' class='innercell'>" + i + "</div>";
      html_cl += "  </div>";
      html_cl += "</div>";
    }
    else {
      html_cl += "<div id='outer_" + year + "-" + mn + "-" + wd + "' class='cell std'>";
      html_cl += "  <div class='cell_wrapper'>";
      html_cl += "    <div id='inner_" + year + "-" + mn + "-" + wd + "' class='innercell'>" + i + "</div>";
      html_cl += "  </div>";
      html_cl += "</div>";
    }
  }
  


  //-----------------------------------------
  // Next month
  //-----------------------------------------
  
  var d = new Date(year, month, wd);  

  var mnn = month +2;
  if (mnn < 10) {mnn = "0" + mnn; }

  if (d.getDay() != 0) {
    var wd = 1;
    for (var i=d.getDay()+1; i<=7; i++) { 
      var wdl = wd;
      if (wdl < 10) {wdl = "0" + wdl; }
      html_cl += "<div id='outer_" + year + "-" + mn + "-" + wd + "' class='cell other'>";
      html_cl += "  <div class='cell_wrapper'>";
      html_cl += "    <div id='inner_" + year + "-" + mn + "-" + wd + "' class='innercell'>" + i + "</div>";
      html_cl += "  </div>";
      html_cl += "</div>";
      wd++;
    }
  }

  html_cl += "</div>"; // container div
  
  
  //-----------------------------------------
  // Put all html into divs
  //-----------------------------------------
 
  document.getElementById('calendar_nav_date').innerHTML = "<div class='calendar_nav_date_cent'><b>" + longMonth[month] + "<br>" + year + "</b></div>";
  document.getElementById('calendar_wd').innerHTML       = html_wd;
  document.getElementById('calendar_wk').innerHTML       = html_wk;
  document.getElementById('calendar_cl').innerHTML       = html_cl;


  //-----------------------------------------
  // Add click event handler
  //-----------------------------------------

  $('.std').on('click', function() {
    select_handler_f(this.id);
    $('.std').removeClass('sel_date');
    $(this).addClass('sel_date');
  });


  //-----------------------------------------
  // Call user script
  //-----------------------------------------

  cal_user_nav_f(year + "-" + mn + "-01");


  //-----------------------------------------
  // Select current date
  //-----------------------------------------
  
  var c_d     = new Date();
  var c_month = c_d.getMonth()+1;
  var c_day   = c_d.getDate();
  var c_date  = c_d.getFullYear() + '-' +
                ((''+c_month).length < 2 ? '0' : '') + c_month + '-' +
                ((''+c_day).length   < 2 ? '0' : '') + c_day;
  $('#outer_' + c_date).addClass('cur_date');
  if (sel_cur) {
    $('#outer_' + c_date).trigger('click');
  }

}



//************************************************************************************
// Highlight dates
//************************************************************************************

function cal_highlight_reset_f(dates, style) {
  $('.cell').attr('class', function(i, c) {
    return c.replace(/\S+_outer_\S+/g, '');
  });

  $('.innercell').attr('class', function(i, c) {
    return c.replace(/\S+_inner_\S+/g, '');
  });
}


function cal_highlight_f(dates, style) {
  if (dates != undefined) {
    dates = dates.replace(/(\r\n|\n|\r)/gm,"");
    var what = style.split('_')[1];
    dates = dates.split(",");
    for (x in dates) {
      $('#'+what + '_' + dates[x]).addClass(style);
    }
  }
}
