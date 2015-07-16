<?php
header('Content-Type: application/javascript');
$generation = count(glob('data/hoppdagar.*.json'));
echo file_get_contents('data/hoppdagar.' . $generation . '.json');
?>
