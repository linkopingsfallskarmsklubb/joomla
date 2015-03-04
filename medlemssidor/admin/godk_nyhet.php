<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<!-- Html Tooltips -->
<script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/tooltip/wz_tooltip.js"></script>

<!-- Page ID. Used in Ajax server script -->
<?php
  $page = Page::getCurrentPage();
  $G_PAGE_ID = $page->getCollectionID();
?>
<script>
  var G_PAGE_ID = <?=$page->getCollectionID()?>;
</script>  




<div id='header'>
  <h1>Godkänn nyhet</h1>
  <p>Följande nyheter väntar på att godkännas.</p>
</div>

<div id='result'>
</div>

<!-- Main list -->
<div id='news'>
  <!-- Content added by ajax -->
</div>

