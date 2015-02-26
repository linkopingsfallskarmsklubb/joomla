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
    if (isset($data->lfk) or $userId <= 0) {
      return true;
    }

    $data->lfk = array();
    if (!$this->loadData($userId, $data->lfk)) {
      return false;
    }

    return $this->loadSkywinData($data->lfk['skywin_id'], $data->lfk);
  }

  private function loadData($userId, &$data) {
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

    foreach ($results as $v) {
      $k = str_replace('lfk.', '', $v[0]);
      $data[$k] = $v[1];
    }

    return true;
  }

  private function loadSkywinData($skywinId, &$data) {

    $db = JFactory::getDbo();
    $db->setQuery('SELECT ' .
      'PID, FirstName, LastName, NickName, Address1, Address2, CountryCode, ' .
      'NationalityCode, Emailaddress, Sex, Year, Club, LicenseType, ' .
      'Weight, Balance, FirstJumpDate, ContactName, ContactPhone, Postcode, ' .
      'Posttown FROM skywin.member WHERE InternalNo = "' . (int)$skywinId . '"');

    try {
      $results = $db->loadRowList();
    } catch (RuntimeException $e) {
      $this->_subject->setError($e->getMessage());
      return false;
    }

    // TODO: Check for multiple matches, and alert.
    $result = $results[0];

    $data['jumps'] = 140;
    $data['pid'] = $result[0];
    $data['firstname'] = $result[1];
    $data['lastname'] = $result[2];
    $data['nickname'] = $result[3];
    $data['address1'] = $result[4];
    $data['address2'] = $result[5];
    $data['country'] = $result[6];
    $data['nationality'] = $result[7];
    $data['email'] = $result[8];
    $data['sex'] = $result[9];
    $data['reg_year'] = $result[10];
    $data['reg_club'] = $result[11];
    $data['license_type'] = $result[12];
    $data['weight'] = $result[13];
    $data['balance'] = $result[14];
    $data['first_jump_date'] = explode(' ', $result[15])[0];
    $data['nok_name'] = $result[16];
    $data['nok_phone'] = $result[17];
    $data['zipcode'] = $result[18];
    $data['city'] = $result[19];

    $db->setQuery('SELECT ' .
      'PhoneNo FROM skywin.memberphone WHERE PhoneType = "M" AND ' .
      'InternalNo = "' . (int)$skywinId . '"');

    try {
      $results = $db->loadRowList();
    } catch (RuntimeException $e) {
      $this->_subject->setError($e->getMessage());
      return false;
    }

    if (isset($results[0])) {
      $data['phone'] = $results[0][0];
    }
    return true;
  }

  public function onContentPrepareForm($form, $data) {
    if (!($form instanceof JForm)) {
      $this->_subject->setError('JERROR_NOT_A_FORM');
      return false;
    }

    $name = $form->getName();
    if (!in_array($name, array(
         'com_admin.profile', 'com_users.user', 'com_users.profile',
         'com_users.registration'))) {
      return true;
    }

    JForm::addFormPath(__DIR__ . '/profiles');
    $form->loadFile('user_lfk', false);

    $fields = array(
      'license',
      'jumps',
      'pid',
      'firstname',
      'lastname',
      'nickname',
      'address1',
      'address2',
      'zipcode',
      'city',
      'country',
      'nationality',
      'email',
      'sex',
      'reg_year',
      'reg_club',
      'license_type',
      'first_jump_date',
      'balance',
      'weight',
      'nok_name',
      'nok_phone',
      'phone',
      'skywin_id');

    foreach ($fields as $field) {
      if ($name == 'com_users.user') {
        // Only show the license field in the administrator UI
        $app = JFactory::getApplication();
        if (!$app->isSite() && $field != 'license' && $field != 'skywin_id') {
          $form->removeField($field, 'lfk');
        }
      } else if ($name == 'com_users.registration') {
        if ($field == 'license') {
          $form->setFieldAttribute($field, 'required', 'required', 'lfk');
        } else {
          $form->removeField($field, 'lfk');
        }
      } elseif ($name == 'com_users.profile' || $name == 'com_admin.profile') {
        if ($field == 'skywin_id') {
          $form->removeField($field, 'lfk');
          continue;
        }

        // TODO: Currently all fields are read-only after creation
        // but that is likely to change.
        $form->setFieldAttribute($field, 'readonly', 'true', 'lfk');
        $form->setFieldAttribute($field, 'required', 'required', 'lfk');
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

      // Only allow changing some stuff in the back-end
      $app = JFactory::getApplication();
      $this->loadData($userId, $oldData);

      if ($app->isSite() && !$isNew) {
        $data['lfk']['skywin_id'] = $oldData['skywin_id'];
        $data['lfk']['license'] = $oldData['license'];
      }

      // If we're changing the license number from the admin site
      // or we're a new user, resolve the internal ID.
      if ($isNew || (!$app->isSite() &&
                     $data['lfk']['license'] != $oldData['license'])) {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT InternalNo FROM skywin.member WHERE ' .
          'MemberNo = "' . (int)$data['lfk']['license'] . '"');
        try {
          $results = $db->loadRowList();
        } catch (RuntimeException $e) {
          $this->_subject->setError($e->getMessage());
          return false;
        }

        // TODO: Check for matches != 1, and alert.
        $result = $results[0];
        $data['lfk']['skywin_id'] = $result[0];
      }

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
