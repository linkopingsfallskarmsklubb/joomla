<html>
<head>
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../themes/lfk/main.css" />
  <link rel="stylesheet" type="text/css" href="../../includes/fonticons/css/font-awesome.css" />
<?php
define("DIR_REL", '/marten');
include("admin_hoppdagar_head.php");

$months = array(
  'Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti',
  'September', 'Oktober', 'November', 'December');
?>
</head>
<body>

<div id="control">
  <span style="display: inline-block; width: 3em">Från: </span><select id="month-from">
<?php foreach ($months as $idx => $month) { echo '<option ' . ($idx == 0 ? 'selected="1"' : '') . ' value="' . $idx . '">' . $month . '</option>'; } ?>
  </select><br />
  <span style="display: inline-block; width: 3em">Till: </span><select id="month-to">
<?php foreach ($months as $idx => $month) { echo '<option ' . ($idx == 11 ? 'selected="1"' : '') . ' value="' . $idx . '">' . $month . '</option>'; } ?>
  </select><br />
  <input type="button" value="Helg" onclick="select_dow([0,6])" />
  <input type="button" value="Vardag" onclick="select_dow([1,2,3,4,5])" />
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
