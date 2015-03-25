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
    var node = $('<td class="' + clsname + ' secondary" data-class="' + cls + '">');
    $(this).find('td[data-class="' + cls + '"]').last().after(node);
  });
}

function show_only(classes) {
  $('*[data-class]').hide();
  classes.forEach(function(cls) {
    $('*[data-class="' + cls + '"]').show();
  });
}

$(document).ready(function() {
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
});
