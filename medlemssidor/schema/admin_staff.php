<?php
$staff_order = array('hl', 'hm', 'manifest', 'pilot', 'tandem', 'foto');

$staff_labels = array(
  'hl' => 'HL',
  'hm' => 'HM',
  'manifest' => 'Manifestor',
  'pilot' => 'Pilot',
  'tandem' => 'Tandem',
  'foto' => 'Foto'
);

$generation = count(glob('data/schedule.*.json'));
$slots = json_decode(file_get_contents('data/schedule.' . $generation . '.json'), true);
$func_count_staff = function($day) {
  $m = 0;
  foreach($day as $split) {
    $m = max($m, array_map(count, $split['staff']));
  }
  return $m;
};
$func_assoc_max = function($a, $b) {
  $ret = $b;
  foreach($a as $k => $v) {
    $ret[$k] = max($v, isset($b[$k]) ? $b[$k] : $v);
  }
  return $ret;
};

$staff_size = array_reduce(array_map($func_count_staff, $slots),
  $func_assoc_max);
?>
<html>
<head>
  <script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
  <script src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/flick/jquery-ui.css" />
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" type="text/css" href="pure-min.css" />
  <link rel="stylesheet" type="text/css" href="admin_staff.css" />
</head>
<body data-generation="<?php echo $generation; ?>">

<div id="title">Schemaläggning</div>
<div id="top">
<table>
<tr id="show-quick">
<td style="text-align: left; padding: 0">Visa bara:</td>
<td class="spacer"></td>
<td colspan="3"><button data-types="hl,hm,manifest" class="show-quick-btn">Mark</button></td>
<td class="spacer"></td>
<td colspan="2"><button data-types="pilot-am,pilot-pm" class="show-quick-btn">Pilot</button></td>
<td class="spacer"></td>
<td colspan="2"><button data-types="tandem,foto" class="show-quick-btn">Tandem</button></td>
</tr>
<tr id="show">
<td>Visa roller:</td>
<td class="spacer"></td>
<td><input type="checkbox" name="show[]" checked="1" value="hl" id="show-hl" /><label for="show-hl">HL</label></td>
<td><input type="checkbox" name="show[]" checked="1" value="hm" id="show-hm" /><label for="show-hm">HM</label></td>
<td><input type="checkbox" name="show[]" checked="1" value="manifest" id="show-manifest" /><label for="show-manifest">Manifestor</label></td>
<td class="spacer"></td>
<td><input type="checkbox" name="show[]" checked="1" value="pilot-am" id="show-pilot-am" /><label for="show-pilot-am">Pilot (FM)</label></td>
<td><input type="checkbox" name="show[]" checked="1" value="pilot-pm" id="show-pilot-pm" /><label for="show-pilot-pm">Pilot (EM)</label></td>
<td class="spacer"></td>
<td><input type="checkbox" name="show[]" checked="1" value="tandem" id="show-tandem" /><label for="show-tandem">Tandem</label></td>
<td><input type="checkbox" name="show[]" checked="1" value="foto" id="show-foto" /><label for="show-foto">Foto</label></td>
</tr>
</table>
</div>
<table id="schedule">
<tr id="schedule-header">
<th class="day">Dag</th>
<th class="time-start">Start</th>
<th class="time-split">&nbsp;</th>
<th class="time-stop">Stopp</th>

<?php
foreach($staff_order as $staff_type) {
  $classes = 'staff';
  if ($staff_type == 'pilot' || $staff_type == 'tandem' ||
      $staff_type == 'foto') {
    $classes .= ' multiple';
  }
  for($i = 0; $i < $staff_size[$staff_type]; $i++) {
    $extra_classes = '';
    if ($i > 0) {
      $extra_classes .= ' secondary';
    }
    echo '<th class="' . $classes . $extra_classes . '" ';
    echo 'data-class="' . $staff_type . '">';
    echo $staff_labels[$staff_type];
    echo '</th>';
  }
}
?>
</tr>
</tr>

<?php foreach($slots as $slot => $data): ?>
<?php $data = $data[0]; ?>
<tr class="first">
<td class="day" data-day="<?php echo $slot; ?>"><?php echo $slot; ?></td>
<td class="time-start" data-time="<?php echo $data['start'];?>"></td>
<td class="time-split"><button class="pure-button split">&#x2702;</button></td>
<td class="time-end" data-time="<?php echo $data['stop'];?>"></td>
<?php
foreach($staff_order as $staff_type) {
  $classes = 'staff';
  if ($staff_type == 'pilot' || $staff_type == 'tandem' ||
      $staff_type == 'foto') {
    $classes .= ' multiple';
  }

  $staff = (isset($data['staff'][$staff_type]) ?
    $data['staff'][$staff_type] : array());
  for($i = 0; $i < $staff_size[$staff_type]; $i++) {
    $person = isset($staff[$i]) ? $staff[$i] : null;
    $extra_classes = '';
    if ($person === null) {
      $extra_classes .= ' empty';
    }
    if ($i > 0) {
      $extra_classes .= ' secondary';
    }

    echo '<td class="' . $classes . $extra_classes . '" ';
    echo 'data-class="' . $staff_type . '" ';
    if ($person === null) {
      echo '>';
    } else {
      echo 'data-id="' . $person . '">';
      echo 'Laddar ..';
    }
    echo '</td>';
  }
}
?>
</tr>

<?php endforeach ?>
</table>

<div id="split-dialog" style="display: none">
<h2>Dela upp hopptider</h2>
<form>
Tid 1: <span class="split-time-day"></span> <span class="split-time-start"></span> - <span class="split-time"></span>
<input id="split-time-range" type="range" style="width: 100%" step="10" />
Tid 2: <span class="split-time-day"></span> <span class="split-time"></span> - <span class="split-time-end"></span>
</div>
<div id="staff-dialog" style="display: none">
<br />
<table cellpadding="0" cellspacing="0" border="0" class="display" id="staff-table"></table>
</div>
<button id="save">Spara</button>
<script src="admin_staff.js"></script>
</body>
</html>
