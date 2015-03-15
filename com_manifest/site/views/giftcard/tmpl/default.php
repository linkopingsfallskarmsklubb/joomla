<?php
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
?>
<?php if ($doc->getTitle()): ?>
<h1><?php echo $doc->getTitle(); ?></h1>
<?php endif; ?>

<p>
  <a href="#" id="new-giftcard-link"><?php echo JText::_('COM_MANIFEST_GIFTCARD_NEW_LINK'); ?></a>
</p>
<div id="new-giftcard" style="display: none">
<?php echo JText::_('COM_MANIFEST_GIFTCARD_ONLY_PERSON'); ?>
<form action="" method="POST" id="new-giftcard-form">
  <table>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_NUM'); ?></b></td>
    <td><input type="text" name="num" style="width: 3em"/></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_EXPIRES'); ?></b></td>
    <td><input type="text" name="expire" /></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_PERSON'); ?></b></td>
    <td><input type="text" name="person" /></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_CONTACT'); ?></b></td>
    <td><input type="text" name="contact" /></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_EMAIL'); ?></b></td>
    <td><input type="text" name="email" /></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_PHONE'); ?></b></td>
    <td><input type="text" name="phone" /></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_MAIL'); ?></b></td>
    <td><textarea name="mail"></textarea></td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_PRODUCTS'); ?></b></td>
    <td>
      <p>
      <input type="checkbox" name="products[]" value="jump" id="prod-jump" checked="1" />
      <label for="prod-jump">Hopp</label>
      <input type="checkbox" name="products[]" value="photo" id="prod-photo" />
      <label for="prod-photo">Foto</label>
      <input type="checkbox" name="products[]" value="video" id="prod-video" />
      <label for="prod-video">Video</label></p>
      <p>Rabatt:
      <input type="text" name="credit" value="0" style="width: 3em" /> SEK
      <a href="javascript:alert('Fyll i om kunden ska få rabatt på pris på extra produkter. Oftast ska detta fältet vara 0 SEK.');">
      ?
      </a>
      </p>
    </td>
    </tr>
    <tr>
    <td><b><?php echo JText::_('COM_MANIFEST_GIFTCARD_NOTE'); ?></b></td>
    <td><input type="text" name="note" /></td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="<?php echo JText::_('COM_MANIFEST_GIFTCARD_ADD'); ?>" /></td>
    </tr>
  </table>
</form>
</div>
<br />
<table class="manifest-giftcards">
  <tr>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th style="width: 3em"><?php echo JText::_('COM_MANIFEST_GIFTCARD_NUM'); ?></th>
    <th style="width: 6em"><?php echo JText::_('COM_MANIFEST_GIFTCARD_EXPIRES'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_PERSON'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_CONTACT'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_EMAIL'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_PHONE'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_MAIL'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_PRODUCTS'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_NOTE'); ?></th>
  </tr>
<?php foreach($this->giftcards as $r_idx => $giftcard): ?>
  <tr>
    <td>
      <input type="button" class="giftcard-remove" data-giftcard="<?php echo $giftcard['num']; ?>" value="&#x2421;" />
    </td>
    <td><?php
if ($giftcard['jumped'] == 1) {
  echo '<span style="color: green">&#x2713;</span>';
} else {
  echo '<input type="button" class="giftcard-jump" data-giftcard="'.$giftcard['num'].'" value="&#x2708;" />';
}
?></td>
    <td><?php echo $giftcard['num']; ?></td>
    <td><?php echo $giftcard['expire']; ?></td>
    <td><?php echo $giftcard['person']; ?></td>
    <td style="font-size: xx-small"><?php echo $giftcard['contact']; ?></td>
    <td style="font-size: xx-small"><?php echo $giftcard['email']; ?></td>
    <td style="font-size: xx-small"><?php echo $giftcard['phone']; ?></td>
    <td style="font-size: xx-small"><?php echo $giftcard['mail']; ?></td>
    <td>
<?php
$products = array();
if ($giftcard['product_jump'] == 1) {
  $products[] = 'H';
}
if ($giftcard['product_video'] == 1) {
  $products[] = 'V';
}
if ($giftcard['product_photo'] == 1) {
  $products[] = 'F';
}
if ($giftcard['product_credit'] != 0) {
  $products[] = $giftcard['product_credit'] . ' SEK';
}
echo implode(', ', $products);
?></td>
    <td style="font-size: xx-small"><?php echo $giftcard['note']; ?></td>
  </tr>
<?php endforeach; ?>
</table>
<form id="modify-giftcard-form" method="POST" action="">
  <input type="hidden" id="modify-action" name="action" value="" />
  <input type="hidden" id="modify-giftcard" name="giftcard" value="" />
</form>
<script>
jQuery(document).ready(function() {
  jQuery('#new-giftcard-link').click(function() {
    jQuery('#new-giftcard').toggle();
  });
  jQuery('#new-giftcard-form').submit(function() {
    if (jQuery('input[name="person"]').val().length < 2) {
      alert('<?php echo JText::_('COM_MANIFEST_GIFTCARD_FAILED_NAME'); ?>');
      jQuery('input[name="person"]').focus();
      return false;
    }
    return true;
  });
  jQuery('.giftcard-jump').click(function() {
    var num = jQuery(this).data('giftcard');
    if (confirm('Markera presentkort ' + num + ' som använt?')) {
      jQuery('#modify-action').val('jump');
      jQuery('#modify-giftcard').val(num);
      jQuery('#modify-giftcard-form').submit();
    }
  });
  jQuery('.giftcard-remove').click(function() {
    var num = jQuery(this).data('giftcard');
    if (confirm('Ta bort presentkort ' + num + '?')) {
      jQuery('#modify-action').val('remove');
      jQuery('#modify-giftcard').val(num);
      jQuery('#modify-giftcard-form').submit();
    }
  });
});
</script>
