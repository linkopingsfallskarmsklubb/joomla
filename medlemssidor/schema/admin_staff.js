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

function add_role(this_) {
  var tr = $(this_).closest('tr');
  var cls = $(this_).data('class');
  var clsname = this_.className.replace(' secondary', '');
  var node = $('<th class="' + clsname + ' secondary" data-class="' + cls + '">');
  node.html(this_.innerHTML);
  node.find('.add').click(function(){ add_role(node[0]); });

  var btn = node.find('.remove');
  btn.show();
  btn.click(function () {
    console.log(this_);
  });

  tr.find('th[data-class="' + cls + '"]').last().after(node);

  // Now shift all data as well
  var table = tr.closest('table');
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
  old_row.find('.time-end').data('time-end', split_time).text(time2human(split_time));
  row.find('.time-start').data('time-start', split_time).text(time2human(split_time));
  row.find('.day').text('');

  // Default to no staff selected
  row.find('.staff').data('id', '').text('').addClass('empty');
  row.removeClass('first').addClass('later');

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
  $('.split').click(function() {
    split_dialog.dialog('open');
    var tr = $(this).closest('tr');
    var day = tr.find('td[data-day]').data('day');
    var start = tr.find('td[data-time].time-start').data('time');
    var end = tr.find('td[data-time].time-end').data('time');
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
  });
  $('#split-dialog').keypress(function(e) {
    if (e.keyCode == $.ui.keyCode.ENTER) {
      $(this).parent().find('.ui-dialog-buttonpane button:first').click();
      return false;
    }
  });
});
