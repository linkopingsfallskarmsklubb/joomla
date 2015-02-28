<?php
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$datatype = $app->input->get('datatype' ,'' , 'string');
?>
<?php if ($doc->getTitle()): ?>
<h1><?php echo $doc->getTitle(); ?></h1>
<?php endif; ?>
<p><?php echo $datatype; ?></p>
