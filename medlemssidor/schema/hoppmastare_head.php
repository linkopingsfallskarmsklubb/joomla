<!-- Include common html -->
<?php
  $path = $_SERVER['REQUEST_URI'] ;
  $path = str_replace(DIR_REL,'/single_pages',$path);
  $path = rtrim($path, '/');
  $path = preg_replace('/(.*)(\/.*$)/','$1',$path);
  $path = $_SERVER["DOCUMENT_ROOT"] . DIR_REL . '/' . $path .'/common_personal_head.php';
  if (file_exists($path)) {
    include($path);
  }
?>
