
var highlighted_dates = {};
var hour_groups = {};
var hour_group_idx = 0;

// Autoformat date
$(document).on('focusout', '#datum', function() {

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
$(document).on('focusout', '#tid_start, #tid_stop', function() {

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


//************************************************************************************
// Draw Calendar.
//************************************************************************************


//-------------------------------------------------------------------------
// Init
//-------------------------------------------------------------------------

$(document).ready(function(){
  load();
});


function date2iso(d) {
  var fd = d.getFullYear() + '-';
  if (d.getMonth() < 10) { fd += "0"; }
  fd += (d.getMonth()+1) + '-';
  if (d.getDate() < 10) { fd += "0"; }
  fd += d.getDate();
  return fd;
}

//-------------------------------------------------------------------------
// Highlight dates
//-------------------------------------------------------------------------

function highlight_f(date, event_dates) {
  var year = new Date().getFullYear();
  // Highlight scheduled dates
  $('.cal_cont').each(function(idx, calendar) {
    cal_draw_f(calendar, false, new Date(year, idx, 1),
      select_handler_f, function(date) {
        var iso = date2iso(date);
        if (highlighted_dates[iso] == true) {
          return 'hl_outer_green';
        }
        for (var group in hour_groups) {
          if (iso in hour_groups[group]['dates']) {
            return 'hl_outer_' + hour_groups[group]['color'];
          }
        }
      }, function(date) {
        highlight_f(date, response);
      });
  });
}


//-------------------------------------------------------------------------
// When a date is selected, do this
//-------------------------------------------------------------------------
function select_handler_f(d) {
  var iso = date2iso(d);

  var select = !(highlighted_dates[iso] == true);
  highlighted_dates[iso] = select;

  if (select) {
    $('.sel_date').addClass('hl_outer_green');
  } else {
    $('.sel_date').removeClass('hl_outer_green');
    delete highlighted_dates[iso];
  }
}

function select_dow(dow) {
  var year = new Date().getFullYear();

  var select = true;
  var decided = false;

  var from = document.getElementById('month-from');
  var to = document.getElementById('month-to');

  for (var month = parseInt(from.value);
      month <= parseInt(to.value); month++) {
    for (var day = 1; day < 32; day++) {
      var d = new Date(year, month, day);
      if (d.getMonth() != month)
        continue;
      if (dow.indexOf(d.getDay()) > -1) {
        var iso = date2iso(d);

        if (!decided) {
          if (iso in highlighted_dates) {
            select = !highlighted_dates[iso];
          }
          decided = true;
        }

        if (select) {
          highlighted_dates[iso] = select;
        } else {
          delete highlighted_dates[iso];
        }
      }
    }
  }
  highlight_f();
}

function apply_hours() {
  var from = document.getElementById('hour-from');
  var to = document.getElementById('hour-to');

  if (Object.keys(highlighted_dates).length == 0) {
    return;
  }
  // TODO(bluecmd): Check if we're overflowing the colors

  var dates = highlighted_dates;
  var key = from.value + ':' + to.value;
  var color = -1;
  var node;
  if (key in hour_groups) {
    $.extend(dates, hour_groups[key]['dates']);
    color = hour_groups[key]['color'];
    node = hour_groups[key]['node'];
  } else {
    color = hour_group_idx++;
    var legend = document.getElementById('color-legend');
    node = document.createElement('li');
    node.innerHTML = '<span class="hl_outer_' + color + '">' +
      from.options[from.selectedIndex].text + ' -> ' +
      to.options[to.selectedIndex].text + '</span>';
    legend.appendChild(node);
  }

  // Remove overlap from old groups
  remove_hours_internal();

  hour_groups[key] = {
    'color': color, 'dates': dates,
    'node': node
  };
  highlighted_dates = {};
  highlight_f();
}

function remove_hours_internal() {
  if (Object.keys(highlighted_dates).length == 0) {
    return;
  }
  Object.keys(hour_groups).forEach(function (key) {
    Object.keys(highlighted_dates).forEach(function (date) {
      if (date in hour_groups[key]['dates']) {
        delete hour_groups[key]['dates'][date];
      }
    });
    if (Object.keys(hour_groups[key]['dates']).length == 0) {
      $(hour_groups[key]['node']).remove();
      delete hour_groups[key];
    }
  });
}

function remove_hours() {
  remove_hours_internal();

  highlighted_dates = {};
  highlight_f();
}

function save() {
  var generation = $('body').attr('data-generation');
  var save = {'data': JSON.stringify(hour_groups)};
  $.post('hoppdagar_save.php?generation=' + generation, save, function() {
    window.location = window.location;
  })
    .fail(function() {
      alert('Kunde inte spara schemat, det har antagligen ändrats av någon annan');
    });
}

function time2human(time) {
  var hour = Math.floor(time / 60);
  var min = time % 60;
  if (hour < 10) {
    hour = '0' + hour.toString();
  } else {
    hour = hour.toString();
  }
  if (min < 10) {
    min = '0' + min.toString();
  } else {
    min = min.toString();
  }

  return hour + ':' + min;
}

function load() {
  $.getJSON('hoppdagar_load.php', function(data) {
    hour_groups = data;
    Object.keys(hour_groups).forEach(function (key) {
      var from = time2human(parseInt(key.split(':')[0]));
      var to = time2human(parseInt(key.split(':')[1]));
      var color = hour_group_idx++;
      var legend = document.getElementById('color-legend');
      var node = document.createElement('li');
      node.innerHTML = '<span class="hl_outer_' + color + '">' +
        from + ' -> ' + to + '</span>';
      legend.appendChild(node);
      hour_groups[key]['color'] = color;
      hour_groups[key]['node'] = node;
    });
   
    console.log(hour_groups);
    highlight_f();
  });
}
