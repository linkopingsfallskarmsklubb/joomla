<?php
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
?>
<?php if ($doc->getTitle()): ?>
<h1><?php echo $doc->getTitle(); ?></h1>
<?php endif; ?>

<p>
  <a href="XX"><?php echo JText::_('COM_MANIFEST_GIFTCARD_NEW_LINK'); ?></a>
</p>
<table class="manifest-data manifest-giftcards">
  <tr>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_REMOVE'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_CHANGE'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_NUM'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_JUMPED'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_PERSON'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_MEDIA'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_EXPIRES'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_CONTACT'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_PHONE'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_MAIL'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_AMOUNT'); ?></th>
    <th><?php echo JText::_('COM_MANIFEST_GIFTCARD_NOTE'); ?></th>
  </tr>
<?php foreach($this->giftcards as $r_idx => $giftcard): ?>
  <tr>
    <td>Ett kort/<td>
  </tr>
<?php endforeach; ?>
</table>
