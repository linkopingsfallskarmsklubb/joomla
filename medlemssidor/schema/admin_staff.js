var split_dialog_updater = null;
var split_dialog = null;
var split_dialog_row = null;

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

function split_button_click() {
  split_dialog.dialog('open');
  var tr = $(this).closest('tr');
  var day = tr.find('td[data-day]').data('day');
  var start = parseInt(tr.find('td[data-time].time-start').data('time'));
  var end = parseInt(tr.find('td[data-time].time-end').data('time'));
  var middle = (start + end) / 2;
  $('.split-time-day').text(day);
  $('.split-time-start').text(time2human(start));
  $('.split-time-end').text(time2human(end));
  $('#split-time-range').attr('min', start+10).attr('max', end-10).val(middle);
  split_dialog_updater = setInterval(function() {
    var split_time = $('#split-time-range').val();
    $('.split-time').text(time2human(split_time));
  }, 100);
  split_dialog_row = tr;
}

function add_role(this_) {
  var tr = $(this_).closest('tr');
  var table = tr.closest('table');
  var cls = $(this_).data('class');
  var clsname = this_.className.replace(' secondary', '');
  var node = $('<th class="' + clsname + ' secondary" data-class="' + cls + '">');
  node.html(this_.innerHTML);
  node.find('.add').click(function(){ add_role(node[0]); });

  var btn = node.find('.remove');
  btn.show();
  btn.click(function () {
    // Check that nobody is scheduled in the column
    var idx = node.index();
    var ok = true;
    table.find('tr').each(function() {
      $(this).find('td,th').each(function () {
        if ($(this).index() == idx) {
          if ($(this).data('id') != undefined) {
            ok = false;
          }
          return false;
        }
      });
    });
 
    if (!ok) {
      alert('Det finns minst en schemalagd person i denna kolumnen.\nTa bort personen innan du tar bort kolumnen.');
      return;
    }
    // OK to remove
    table.find('tr').each(function() {
      $(this).find('td,th').each(function () {
        if ($(this).index() == idx) {
          $(this).remove();
          return false;
        }
      });
    });
  });

  tr.find('th[data-class="' + cls + '"]').last().after(node);

  // Now shift all data as well
  table.find('tr').each(function() {
    console.log(this);
    var node = $('<td class="' + clsname + ' secondary empty" data-class="' + cls + '">');
    $(this).find('td[data-class="' + cls + '"]').last().after(node);
  });
}

function show_only(classes) {
  $('*[data-class]').hide();
  classes.forEach(function(cls) {
    $('*[data-class="' + cls + '"]').show();
  });
}

function split(old_row) {
  var split_time = $('#split-time-range').val();
  var row = old_row.clone();
  old_row.find('.time-end').data('time', split_time).text(time2human(split_time));
  row.find('.time-start').data('time', split_time).text(time2human(split_time));

  var btn = $('<button class="pure-button remove">Slå ihop</button>');
  row.find('.day').text('').append(btn);
  btn.click(function() {
    // Check that nobody is scheduled in the row
    var ok = true;
    row.find('td').each(function () {
      if ($(this).data('id') != undefined) {
        ok = false;
      }
    });
 
    if (!ok) {
      alert('Det finns minst en schemalagd person på denna raden.\nTa bort personen innan du tar bort raden.');
    }
    // OK to remove - patch the other rows with our times
    var end_time = row.find('.time-end').data('time');
    row.prev().find('.time-end').data('time', end_time).text(time2human(end_time));
    row.remove();
  });

  // Default to no staff selected
  row.find('.staff').data('id', undefined).text('').addClass('empty');
  row.removeClass('first').addClass('later');

  row.find('.split').click(split_button_click);

  old_row.after(row);
  split_dialog.dialog('close');
}

$(document).ready(function() {
  split_dialog = $('#split-dialog').dialog({
    autoOpen: false,
    height: 300,
    width: 500,
    modal: true,
    buttons: {
      "Dela upp": function() { split(split_dialog_row); },
      "Avbryt": function() {
        split_dialog.dialog('close');
      }},
    close: function() {
      clearInterval(split_dialog_updater);
      split_dialog_updater = null;
      split_dialog_row = null;
    }});
  $('th.multiple').each(function() {
    var btn = $('<button class="pure-button add">+</button>');
    var this_ = this;
    btn.click(function(){ add_role(this_); });
    $(this).append(btn);
    var btn = $('<button class="pure-button remove">-</button>');
    btn.hide();
    $(this).append(btn);
  });
  $('#show input[type="checkbox"]').click(function() {
    show_only($('#show input[type="checkbox"]:checked').map(function() {
      return this.value;
    }).get());
  });
  $('.split').click(split_button_click);
  $('#split-dialog').keypress(function(e) {
    if (e.keyCode == $.ui.keyCode.ENTER) {
      $(this).parent().find('.ui-dialog-buttonpane button:first').click();
      return false;
    }
  });
});
