<?php
defined('_JEXEC') or die('Restricted access');
 
class ManifestViewGiftcard extends JViewLegacy {
  function display($tpl = null)
  {
    $user = JFactory::getUser();
    $profile = JUserHelper::getProfile($user->id);

    $db = JFactory::getDbo();
    $app = JFactory::getApplication();
    if (isset($_POST['action'])) {
      // Modify giftcard
      $action = $app->input->get('action', '', 'string');
      $giftcard = $app->input->get('giftcard', 0, 'int');
      $query = $db->getQuery(true);
      if ($action == 'jump') {
        $query->update($db->quoteName('#__giftcards'))
          ->set(array($db->quoteName('jumped') . ' = 1'))
          ->where(array($db->quoteName('num') . ' = ' . $giftcard));
      }
      else if ($action == 'remove') {
        $query->delete($db->quoteName('#__giftcards'))->where(array(
          $db->quoteName('num') . ' = ' . $giftcard));
      } else {
        $this->_subject->setError('Unknown action passed to view');
        return false;
      }
      $db->setQuery($query);
      $db->execute();
      // Redirect to GET
      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit();
    } else if (isset($_POST['num'])) {
      // New giftcard
      $num = $app->input->get('num', 0, 'int');
      $person = $app->input->get('person', '', 'string');
      $expire = $app->input->get('expire', '', 'string');
      $contact = $app->input->get('contact', '', 'string');
      $email = $app->input->get('email', '', 'string');
      $phone = $app->input->get('phone', '', 'string');
      $mail = $app->input->get('mail', '', 'string');
      $note = $app->input->get('note', '', 'string');
      $products = $app->input->get('products', array(), 'array');
      $credit = $app->input->get('credit', 0, 'int');

      $columns = array();
      $values = array();
      if ($num != 0) {
        $columns[] = 'num';
        $values[] = $num;
      }
      $columns[] = 'expire';
      if ($expire != '') {
        $values[] = $db->quote($expire);
      } else {
        $values[] = 'DATE_ADD(CURDATE(), INTERVAL 1 YEAR)';
      }
      if ($person != '') {
        $columns[] = 'person';
        $values[] = $db->quote($person);
      }
      if ($email != '') {
        $columns[] = 'email';
        $values[] = $db->quote($email);
      }
      if ($contact != '') {
        $columns[] = 'contact';
        $values[] = $db->quote($contact);
      }
      if ($phone != '') {
        $columns[] = 'phone';
        $values[] = $db->quote($phone);
      }
      if ($mail != '') {
        $columns[] = 'mail';
        $values[] = $db->quote($mail);
      }
      if ($note != '') {
        $columns[] = 'note';
        $values[] = $db->quote($note);
      }
      if (in_array('jump', $products)) {
        $columns[] = 'product_jump';
        $values[] = 1;
      }
      if (in_array('photo', $products)) {
        $columns[] = 'product_photo';
        $values[] = 1;
      }
      if (in_array('video', $products)) {
        $columns[] = 'product_video';
        $values[] = 1;
      }
      $columns[] = 'product_credit';
      $values[] = $credit;

      $query = $db->getQuery(true);
      $query
        ->insert($db->quoteName('#__giftcards'))
        ->columns($db->quoteName($columns))
        ->values(implode(',', $values));
      $db->setQuery($query);
      $db->execute();
      // Redirect to GET
      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit();
    }

    $db->setQuery('SELECT *, expire < NOW() as expired FROM '.
      '#__giftcards ORDER BY num DESC');

    try {
      $this->giftcards = $db->loadAssocList();
    } catch (RuntimeException $e) {
      $this->_subject->setError($e->getMessage());
      return false;
    }

    parent::display($tpl);
  }
}
?>
