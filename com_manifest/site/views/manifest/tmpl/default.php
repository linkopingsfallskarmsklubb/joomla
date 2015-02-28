<?php
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$datatype = $app->input->get('datatype' ,'' , 'string');
?>
<?php if ($doc->getTitle()): ?>
<h1><?php echo $doc->getTitle(); ?></h1>
<?php endif; ?>
<p>
<?php foreach($this->results as $r_idx => $result): ?>
  <table class="manifest-data manifest-<?php echo $datatype . '-'.$r_idx; ?>">
    <tr>
      <?php foreach($result[0] as $idx => $col): ?>
      <th>
<?php echo JText::_(
  'COM_MANIFEST_' . strtoupper($datatype) . '_' . $r_idx . '-' . $idx . '_LABEL'); ?>
      </th>
      <?php endforeach; ?>
    </tr>
    <?php foreach($result as $row): ?>
    <tr>
      <?php foreach($row as $idx => $col): ?>
      <td class="manifest-column-<?php echo $idx; ?>"><?php echo $col; ?></td>
      <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
  </table>
  <hr />
<?php endforeach; ?>

</p>
