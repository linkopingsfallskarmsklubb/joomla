function add_role(this_) {
  var tr = $(this_).closest('tr');
  var cls = $(this_).data('class');
  var clsname = this_.className.replace(' secondary', '');
  var node = $('<th class="' + clsname + ' secondary" data-class="' + cls + '">');
  node.html(this_.innerHTML);
  node.find('.add').click(function(){ add_role(node[0]); });
  tr.find('*[data-class="' + cls + '"]').last().after(node);
}

function show_only(classes) {
  $('*[data-class]').hide();
  classes.forEach(function(cls) {
    $('*[data-class="' + cls + '"]').show();
  });
}

$(document).ready(function() {
  show_only(['hl', 'pilot']);

  $('.multiple').each(function() {
    var btn = $('<button class="pure-button add">+</button>');
    var this_ = this;
    btn.click(function(){ add_role(this_); });
    $(this).append(btn);
  });
  $('.multiple.secondary').each(function() {
    var btn = $('<button class="pure-button remove">-</button>');
    var this_ = this;
    btn.click(function () {
      console.log(this_);
    });
    $(this).append(btn);
  });
});
