<?php
defined('_JEXEC') or die('Restricted access');
 
class ManifestViewManifest extends JViewLegacy {
  function display($tpl = null)
  {
    $this->msg = 'Hello World';
    parent::display($tpl);
  }
}
?>
