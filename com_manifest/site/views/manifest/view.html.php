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
    $sql_map = array(
  'top_loadmaster' => array(
"SELECT concat(Member.FirstName, ' ', Member.LastName), Count(Loadrole.Regdate)
  FROM $d.Member INNER JOIN $d.Loadrole ON Member.InternalNo = Loadrole.InternalNo
  WHERE year(Loadrole.Regdate) = $year AND RoleType='LOADMASTER'
  GROUP BY Member.FirstName, Member.LastName
  ORDER BY Count(LoadRole.Regdate) DESC"),

  'top_student' => array(
"SELECT concat(Member.FirstName, ' ', Member.LastName), Count(Loadjump.Regdate),
  Member.StudentJumpNo, Member.LicenseType, date(Member.FirstJumpdate)
  FROM $d.Member INNER JOIN $d.Loadjump ON Member.InternalNo = Loadjump.InternalNo
  WHERE year(Loadjump.Regdate) = $year AND (Loadjump.JumpType='M' OR
  Loadjump.JumpType='A' OR Loadjump.JumpType LIKE 'SFU%')
  GROUP BY Member.FirstName, Member.LastName,
  Member.FirstJumpdate, Member.StudentJumpNo, Member.LicenseType
  ORDER BY Count(Loadjump.Regdate) DESC"),

  'top_drop' => array(
"SELECT concat(Member.FirstName, ' ', Member.LastName),
  Ceil(Sum(Loadjump.altitude) / 1000)
  FROM $d.Member INNER JOIN $d.Loadjump ON Member.InternalNo = Loadjump.InternalNo
  WHERE Year(Loadjump.Regdate) = $year
  GROUP BY Member.FirstName, Member.LastName ORDER BY Sum(Loadjump.altitude)
  DESC LIMIT 25"),

  'top_jumps' => array(
"SELECT concat(Member.FirstName, ' ', Member.LastName), Count(Loadjump.Regdate)
  FROM $d.Member INNER JOIN $d.Loadjump ON Member.InternalNo = Loadjump.InternalNo
  WHERE year(Loadjump.Regdate) = $year GROUP BY Member.FirstName,
  Member.LastName ORDER BY Count(Loadjump.Regdate) DESC LIMIT 25"),

  'aircraft' => array(
"SELECT Load.Planereg, Count(load.Regdate),
  (SELECT Count(loadjump.LoadNo) FROM $d.LoadJump WHERE
   Loadjump.Planereg=Load.Planereg),
  round((SELECT Count(loadjump.LoadNo) FROM $d.LoadJump WHERE
   Loadjump.Planereg=Load.Planereg) / Count(load.Regdate), 2)
  FROM $d.`load`
   GROUP BY Load.PlaneReg ORDER BY Load.planereg",
"SELECT Load.PlaneReg, Year(load.RegDate), Count(Load.Regdate),
  (SELECT Count(Loadjump.loadno) FROM $d.loadjump WHERE
   loadjump.PlaneReg=load.PlaneReg AND year(load.regdate) =
   year(loadjump.regdate)),
  Round((SELECT Count(Loadjump.loadno) FROM $d.loadjump WHERE
   loadjump.PlaneReg=load.PlaneReg AND year(load.regdate) =
    year(loadjump.regdate)) / Count(load.Regdate), 2)
  FROM $d.`Load` GROUP BY Load.Planereg, Year(load.regdate)
  ORDER BY Load.PlaneReg, Year(load.regdate)"),

  'account' => array(
"SELECT ceil(t.Balance), date(t.Regdate)
  FROM $d.Member AS m INNER JOIN $d.trans AS t
  ON m.AccountNo = t.AccountNo WHERE m.InternalNo = $skywinId
  ORDER BY t.TransNo DESC LIMIT 1",
"SELECT t.TransType, t.AccountType, date(t.Regdate),
  ceil(t.Amount), ceil(t.Balance), t.Comment
  FROM $d.Member AS m INNER JOIN $d.trans AS t
  ON m.AccountNo = t.AccountNo WHERE m.InternalNo = $skywinId
  ORDER BY t.TransNo DESC"),

  'jumps' => array(
"SELECT Loadjump.Planereg, Count(Member.MemberNo), Min(loadjump.altitude),
  max(loadjump.altitude), ceil(avg(loadjump.altitude)),
  ceil(sum(loadjump.altitude) / 1000)
  FROM $d.member, $d.loadjump WHERE member.InternalNo = $skywinId AND
  loadjump.InternalNo = Member.InternalNo GROUP BY loadjump.planereg
  ORDER BY loadjump.planereg",
"SELECT date(Loadjump.Regdate), Loadjump.PlaneReg, Loadjump.Altitude, tj.jumptypename,
  Ceil(Loadjump.DiscountedPrice + Loadjump.rentalamount + Loadjump.externalamount +
       Loadjump.extraamount + Loadjump.climateamount) FROM $d.typejumps tj,
  $d.Member INNER JOIN $d.Loadjump ON Member.InternalNo = Loadjump.InternalNo
  WHERE Member.InternalNo = $skywinId AND tj.jumptype=loadjump.jumptype
  ORDER BY Regdate desc,loadjump.loadno DESC"),

  'shame' => array(
"SELECT concat(Member.FirstName, ' ', Member.LastName), Member.Balance
    FROM $d.Member WHERE Balance < 0 ORDER BY Balance LIMIT 25"),

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
