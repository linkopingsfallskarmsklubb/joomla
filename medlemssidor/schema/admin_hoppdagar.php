<html>
<head>
  <link rel="stylesheet" type="text/css" href="calendar.css" />
  <link rel="stylesheet" type="text/css" href="admin_hoppdagar.css" />
  <script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
  <script type="text/javascript" src="calendar.js"></script>
  <script type="text/javascript" src="admin_hoppdagar.js"></script>
<?php
$months = array(
  'Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti',
  'September', 'Oktober', 'November', 'December');

$hours = array();
for($hour = 0; $hour < 24; $hour++) {
  $hours[$hour*60] = ($hour < 10 ? '0' : '') .$hour . ':00';
  $hours[$hour*60 + 30] = ($hour < 10 ? '0' : '') .$hour . ':30';
}
?>
</head>
<body data-generation="<?php echo count(glob('data/hoppdagar.*.json')); ?>">

<div id="control">
<h2>Steg 1. Markera dagar</h2>
  <span style="display: inline-block; width: 3em">Från: </span><select id="month-from">
<?php foreach ($months as $idx => $month) { echo '<option ' . ($idx == 0 ? 'selected="1"' : '') . ' value="' . $idx . '">' . $month . '</option>'; } ?>
  </select><br />
  <span style="display: inline-block; width: 3em">Till: </span><select id="month-to">
<?php foreach ($months as $idx => $month) { echo '<option ' . ($idx == 11 ? 'selected="1"' : '') . ' value="' . $idx . '">' . $month . '</option>'; } ?>
  </select><br />
  <input type="button" value="M" onclick="select_dow([1])" />
  <input type="button" value="T" onclick="select_dow([2])" />
  <input type="button" value="O" onclick="select_dow([3])" />
  <input type="button" value="T" onclick="select_dow([4])" />
  <input type="button" value="F" onclick="select_dow([5])" />
  <input type="button" value="L" onclick="select_dow([6])" />
  <input type="button" value="S" onclick="select_dow([0])" />
<hr />
<h2>Steg 2. Välj hopptider</h2>
  <span style="display: inline-block; width: 3em">Från: </span><select id="hour-from">
<?php foreach ($hours as $idx => $hour) { echo '<option ' . ($idx == 10*60 ? 'selected="1"' : '') . ' value="' . $idx . '">' . $hour . '</option>'; } ?>
  </select><br />
  <span style="display: inline-block; width: 3em">Till: </span><select id="hour-to">
<?php foreach ($hours as $idx => $hour) { echo '<option ' . ($idx == 18*60 ? 'selected="1"' : '') . ' value="' . $idx . '">' . $hour . '</option>'; } ?>
  </select><br />
  <input type="button" value="Verkställ" onclick="apply_hours();" />
  <input type="button" value="Ta bort" onclick="remove_hours();" />
<hr />
<h2>Steg 3. Granska och spara</h2>
  <ul id="color-legend">
  </ul>
  <input type="button" value="Spara" onclick="save()" />
</div>
<div id="calendars">
<?php for ($month = 0; $month < 12; $month++): ?>
<!-- Calendar -->
<div data-title='<?php echo $months[$month]; ?>' class='cal_cont' style='<?php echo $month % 4 == 0 ? 'clear: both' : ''?>'>
</div>
<?php endfor ?>
</div>
</body>
</html>
