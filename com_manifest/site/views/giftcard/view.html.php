<?php
defined('_JEXEC') or die('Restricted access');
 
class ManifestViewGiftcard extends JViewLegacy {
  function display($tpl = null)
  {
    $user = JFactory::getUser();
    $profile = JUserHelper::getProfile($user->id);

    parent::display($tpl);
  }
}
?>
