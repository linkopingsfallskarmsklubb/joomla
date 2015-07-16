<?php

$generation = $_GET['generation'];
$current_generation = count(glob('data/schedule.*.json'));
if ($generation != $current_generation) {
  http_response_code('500');
  die('Generation changed during edit');
}

$generation++;

// Decode and re-encode to validate
$data = json_decode($_POST['data'], true);
$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

file_put_contents('data/schedule.' . $generation . '.json', $json);
?>
