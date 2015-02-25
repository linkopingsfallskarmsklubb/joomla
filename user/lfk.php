<?php
defined('JPATH_BASE') or die;

class PlgUserLfk extends JPlugin {

  protected $autoloadLanguage = true;

  public function onContentPrepareData($context, $data) {
    if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile'))) {
      return true;
    }

    if (!is_object($data)) {
      return true;
    }

    $userId = isset($data->id) ? $data->id : 0;

    if (!isset($data->profile) and $userId > 0) {
      // Load the profile data from the database.
      $db = JFactory::getDbo();
      $db->setQuery(
        'SELECT profile_key, profile_value FROM #__user_profiles' .
          ' WHERE user_id = ' . (int) $userId . " AND profile_key LIKE 'lfk.%'" .
          ' ORDER BY ordering'
      );

      try {
        $results = $db->loadRowList();
      } catch (RuntimeException $e) {
        $this->_subject->setError($e->getMessage());
        return false;
      }

      // Merge the profile data.
      $data->lfk = array();

      foreach ($results as $v) {
        $k = str_replace('lfk.', '', $v[0]);
        $data->lfk[$k] = $v[1];
      }
    }

    return true;
  }

  public function onContentPrepareForm($form, $data) {
    if (!($form instanceof JForm)) {
      $this->_subject->setError('JERROR_NOT_A_FORM');
      return false;
    }

    $name = $form->getName();
    if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration'))) {
      return true;
    }

    JForm::addFormPath(__DIR__ . '/profiles');
    $form->loadFile('user_lfk', false);

    $fields = array('license');

    foreach ($fields as $field) {
      if ($name == 'com_users.user') {
        // Remove the field if it is disabled in registration and profile
        if ($this->params->get('register-require_' . $field, 1) == 0
          && $this->params->get('profile-require_' . $field, 1) == 0) {
          $form->removeField($field, 'lfk');
        }
      } elseif ($name == 'com_users.registration') {
        // Toggle whether the field is required.
        if ($this->params->get('register-require_' . $field, 1) > 0) {
          $form->setFieldAttribute($field, 'required', ($this->params->get('register-require_' . $field) == 2) ? 'required' : '', 'lfk');
        } else {
          $form->removeField($field, 'lfk');
        }
      } elseif ($name == 'com_users.profile' || $name == 'com_admin.profile') {
        // Toggle whether the field is required.
        if ($this->params->get('profile-require_' . $field, 1) > 0) {
          $form->setFieldAttribute($field, 'required', ($this->params->get('profile-require_' . $field) == 2) ? 'required' : '', 'lfk');
        } else {
          $form->removeField($field, 'lfk');
        }
      }
    }

    return true;
  }

  public function onUserBeforeSave($user, $isnew, $data) {
    return true;
  }

  public function onUserAfterSave($data, $isNew, $result, $error) {
    $userId = JArrayHelper::getValue($data, 'id', 0, 'int');

    if ($userId && $result && isset($data['lfk']) && (count($data['lfk']))) {
      try {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
          ->delete($db->quoteName('#__user_profiles'))
          ->where($db->quoteName('user_id') . ' = ' . (int) $userId)
          ->where($db->quoteName('profile_key') .
            ' LIKE ' . $db->quote('lfk.%'));

        $db->setQuery($query);
        $db->execute();

        $tuples = array();
        $order = 1;

        foreach ($data['lfk'] as $k => $v) {
          $tuples[] = '(' . $userId . ', ' . $db->quote('lfk.' . $k) . ', '
                          . $db->quote($v) . ', ' . ($order++) . ')';
        }

        $db->setQuery('INSERT INTO #__user_profiles VALUES ' . implode(', ', $tuples));
        $db->execute();
      }
      catch (RuntimeException $e) {
        $this->_subject->setError($e->getMessage());
        return false;
      }
    }

    return true;
  }

  public function onUserAfterDelete($user, $success, $msg) {
    if (!$success) {
      return false;
    }

    $userId = JArrayHelper::getValue($user, 'id', 0, 'int');

    if ($userId) {
      try {
        $db = JFactory::getDbo();
        $db->setQuery(
          'DELETE FROM #__user_profiles WHERE user_id = ' . $userId .
            " AND profile_key LIKE 'lfk.%'"
        );

        $db->execute();
      } catch (Exception $e) {
        $this->_subject->setError($e->getMessage());
        return false;
      }
    }

    return true;
  }
}
