<?php
defined('_JEXEC') or die('Restricted access');
 
class ManifestViewManifest extends JViewLegacy {
  function display($tpl = null)
  {
    $app = JFactory::getApplication();
    $datatype = $app->input->get('datatype' ,'alla' , 'string');

    $user = JFactory::getUser();
    $profile = JUserHelper::getProfile($user->id);

    $jinput = JFactory::getApplication()->input;
    $year = $jinput->get('year', (int)date('Y'), 'int');
    $jumptype = $jinput->get('type', 'alla', 'string');

    // Only use the year dropdown for top lists
    $this->showYears = strpos($datatype, 'top_') !== false;

    // 1999 - Now are valid years
    $this->years = array();
    for($i = (int)date('Y'); $i >= 1999; $i--) {
      $this->years[] = $i;
    }

    // Mangle invalid years to current year
    if (!in_array($year, $this->years)) {
      $year = (int)date('Y');
    }
    $this->year = $year;

    $skywinId = $profile->lfk['skywin_id'];

    // Database prefix to use to access the skywin database
    $d = 'skywin';

    // Construct the Datatype -> SQL(s) map
    $sql_map = array
  'top_loadmaster' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), Count(loadrole.Regdate)
  FROM $d.member INNER JOIN $d.loadrole ON member.InternalNo = loadrole.InternalNo
  WHERE year(loadrole.Regdate) = $year AND RoleType='LOADMASTER'
  GROUP BY member.FirstName, member.LastName
  ORDER BY Count(loadrole.Regdate) DESC"),

  'top_student' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), Count(loadjump.Regdate),
  member.StudentJumpNo, member.LicenseType, date(member.FirstJumpdate)
  FROM $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE year(loadjump.Regdate) = $year AND (loadjump.JumpType='M' OR
  loadjump.JumpType='A' OR loadjump.JumpType LIKE 'SFU%')
  GROUP BY member.FirstName, member.LastName,
  member.FirstJumpdate, member.StudentJumpNo, member.LicenseType
  ORDER BY Count(loadjump.Regdate) DESC"),

  'top_drop' => array(
"SELECT concat(member.FirstName, ' ', member.LastName),
  Ceil(Sum(loadjump.altitude) / 1000)
  FROM $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE Year(loadjump.Regdate) = $year
  GROUP BY member.FirstName, member.LastName ORDER BY Sum(loadjump.altitude)
  DESC LIMIT 25"),

  'top_jumps' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), Count(loadjump.Regdate)
  FROM $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE year(loadjump.Regdate) = $year GROUP BY member.FirstName,
  member.LastName ORDER BY Count(loadjump.Regdate) DESC LIMIT 25"),
     
  'top_fun' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), Count(loadjump.Regdate)
  FROM $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE year(loadjump.Regdate) = $year AND (loadjump.JumpType='O' OR loadjump.JumpType='WING')
  GROUP BY member.FirstName, member.LastName 
  ORDER BY Count(loadjump.Regdate) 
  DESC LIMIT 25"),

  'top_fun_alltime' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), Count(loadjump.Regdate)
  FROM $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE loadjump.JumpType='O' OR loadjump.JumpType='WING'
  GROUP BY member.FirstName, member.LastName 
  ORDER BY Count(loadjump.Regdate) 
  DESC LIMIT 25"),

  'top_work' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), Count(loadjump.Regdate)
  FROM $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE year(loadjump.Regdate) = $year AND (loadjump.JumpType='T' OR loadjump.JumpType='V' OR loadjump.JumpType='I' OR loadjump.JumpType='HMI' OR loadjump.JumpType='TANDEM-EJ')
  GROUP BY member.FirstName, member.LastName 
  ORDER BY Count(loadjump.Regdate) 
  DESC LIMIT 25"),

  'aircraft' => array(
"SELECT load.Planereg, Count(load.Regdate),
  (SELECT Count(loadjump.LoadNo) FROM $d.loadjump WHERE
   loadjump.Planereg=load.Planereg),
  round((SELECT Count(loadjump.LoadNo) FROM $d.loadjump WHERE
   loadjump.Planereg=load.Planereg) / Count(load.Regdate), 2)
  FROM $d.`load`
   GROUP BY load.PlaneReg ORDER BY load.planereg",
"SELECT load.PlaneReg, Year(load.RegDate), Count(load.Regdate),
  (SELECT Count(loadjump.loadno) FROM $d.loadjump WHERE
   loadjump.PlaneReg=load.PlaneReg AND year(load.regdate) =
   year(loadjump.regdate)),
  Round((SELECT Count(loadjump.loadno) FROM $d.loadjump WHERE
   loadjump.PlaneReg=load.PlaneReg AND year(load.regdate) =
    year(loadjump.regdate)) / Count(load.Regdate), 2)
  FROM $d.`load` GROUP BY load.Planereg, Year(load.regdate)
  ORDER BY load.PlaneReg, Year(load.regdate)"),

  'account' => array(
"SELECT ceil(t.Balance), date(t.Regdate)
  FROM $d.member AS m INNER JOIN $d.trans AS t
  ON m.AccountNo = t.AccountNo WHERE m.InternalNo = $skywinId
  ORDER BY t.TransNo DESC LIMIT 1",
"SELECT t.TransType, t.AccountType, date(t.Regdate),
  ceil(t.Amount), ceil(t.Balance), t.Comment
  FROM $d.member AS m INNER JOIN $d.trans AS t
  ON m.AccountNo = t.AccountNo WHERE m.InternalNo = $skywinId
  ORDER BY t.TransNo DESC"),

  'jumps' => array(
"SELECT loadjump.Planereg, Count(member.memberNo), Min(loadjump.altitude),
  max(loadjump.altitude), ceil(avg(loadjump.altitude)),
  ceil(sum(loadjump.altitude) / 1000)
  FROM $d.member, $d.loadjump WHERE member.InternalNo = $skywinId AND
  loadjump.InternalNo = member.InternalNo GROUP BY loadjump.planereg
  ORDER BY loadjump.planereg",
"SELECT date(loadjump.Regdate), loadjump.PlaneReg, loadjump.Altitude, tj.jumptypename,
  Ceil(loadjump.DiscountedPrice + loadjump.rentalamount + loadjump.externalamount +
       loadjump.extraamount + loadjump.climateamount) FROM $d.typejumps tj,
  $d.member INNER JOIN $d.loadjump ON member.InternalNo = loadjump.InternalNo
  WHERE member.InternalNo = $skywinId AND tj.jumptype=loadjump.jumptype
  ORDER BY Regdate desc,loadjump.loadno DESC"),

  'shame' => array(
"SELECT concat(member.FirstName, ' ', member.LastName), member.Balance
    FROM $d.member WHERE Balance < 0 ORDER BY Balance LIMIT 25"),

  'club' => array('CALL club_stats'));

    $db = JFactory::getDbo();
    $this->results = array();

    $db->setQuery("SELECT JumpType, JumpTypeName FROM $d.typejumps");
    try {
      $results = $db->loadRowList();
    } catch (RuntimeException $e) {
      throw new Exception($e->getMessage());
    }
    $this->jumptypes = $results;
    $this->jumptype = 'alla';
    foreach($this->jumptypes as $row){
      if ($row[0] == $jumptype) {
        $this->jumptype = $jumptype;
        break;
      }
    }

    foreach($sql_map[$datatype] as $sql) {
      $db->setQuery($sql);
      try {
        if ($datatype == 'club') {
          $results = $db->loadAssocList();
        } else {
          $results = $db->loadRowList();
        }
      } catch (RuntimeException $e) {
        throw new Exception($e->getMessage());
      }
      $this->results[] = $results;
    }

    if ($datatype == 'club') {
      $tpl = 'club';
    }
    parent::display($tpl);
  }
}
?>
