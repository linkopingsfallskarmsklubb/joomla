<!-- Variables used in javascript and ajax server script -->
<script>
  var type = "aff";
</script>

<?php
 $header = "AFF-instruktörer";
?>


<!-- Include common html -->
<?php
  $path = $_SERVER['REQUEST_URI'] ;
  $path = str_replace(DIR_REL,'/single_pages',$path);
  $path = rtrim($path, '/');
  $path = preg_replace('/(.*)(\/.*$)/','$1',$path);
  $path = $_SERVER["DOCUMENT_ROOT"] . DIR_REL . '/' . $path .'/common_personal.php';
  if (file_exists($path)) {
    include($path);
  }
?>
