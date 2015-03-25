<?php
$slots = array(
  '2015-04-30' => array(60*10, 18*60+30),
  '2015-05-01' => array(60*10, 18*60+30),
  '2015-05-02' => array(60*10, 18*60+30),
  '2015-06-10' => array(60*10, 18*60+30),
  '2015-06-11' => array(60*10, 18*60+30),
  '2015-06-12' => array(60*10, 18*60+30)
);

function time2human($time) {
  $hour = floor($time / 60);
  $min =  $time % 60;
  if ($hour < 10) {
    $hour = '0' . $hour;
  }

  if ($min < 10) {
    $min = '0' . $min;
  }

  return $hour . ':' . $min;
}
?>
<html>
<head>
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/flick/jquery-ui.css" />
  <link rel="stylesheet" type="text/css" href="pure-min.css" />
  <link rel="stylesheet" type="text/css" href="admin_staff.css" />
</head>
<body>

<p id="show">Visa roller:
<input type="checkbox" name="show[]" checked="1" value="hl" id="show-hl" /><label for="show-hl">HL</label>
<input type="checkbox" name="show[]" checked="1" value="hm" id="show-hm" /><label for="show-hm">HM</label>
<input type="checkbox" name="show[]" checked="1" value="manifest" id="show-manifest" /><label for="show-manifest">Manifestor</label>
<input type="checkbox" name="show[]" checked="1" value="pilot" id="show-pilot" /><label for="show-pilot">Pilot</label>
<input type="checkbox" name="show[]" checked="1" value="tandem" id="show-tandem" /><label for="show-tandem">Tandem</label>
<input type="checkbox" name="show[]" checked="1" value="foto" id="show-foto" /><label for="show-foto">Foto</label>
</p>
<table>
<tr>
<th class="day">Dag</th>
<th class="time-start">Start</th>
<th class="time-split">&nbsp;</th>
<th class="time-stop">Stopp</th>
<th class="staff" data-class="hl">HL</th>
<th class="staff" data-class="hm">HM</th>
<th class="staff" data-class="manifest">Manifestor</th>
<th class="staff multiple" data-class="pilot">Pilot</th>
<th class="staff multiple" data-class="tandem">Tandem</th>
<th class="staff multiple" data-class="foto">Foto</th>
</tr>

<?php foreach($slots as $slot => $times): ?>
<tr class="first">
<td class="day" data-day="<?php echo $slot; ?>"><?php echo $slot; ?></td>
<td class="time-start" data-time="<?php echo $times[0];?>"><?php echo time2human($times[0]); ?></td>
<td class="time-split"><button class="pure-button split">&#x2702;</button></td>
<td class="time-end" data-time="<?php echo $times[1];?>"><?php echo time2human($times[1]); ?></td>
<td class="staff" data-class="hl">Adam HLsson</td>
<td class="staff" data-class="hm">Håkan HMqvist</td>
<td class="staff" data-class="manifest">Mani Festor</td>
<td class="staff multiple" data-class="pilot">Flygande Jakob</td>
<td class="staff multiple" data-class="tandem">Fågäl Mannen</td>
<td class="staff multiple" data-class="foto">Linnsl Usen</td>

</tr>

<?php endforeach ?>
</table>

<div id="split-dialog">
<h2>Dela upp hopptider</h2>
<form>
Tid 1: <span class="split-time-day"></span> <span class="split-time-start"></span> - <span class="split-time"></span>
<input id="split-time-range" type="range" style="width: 100%" step="10" />
Tid 2: <span class="split-time-day"></span> <span class="split-time"></span> - <span class="split-time-end"></span>
</div>
<script src="admin_staff.js"></script>
</body>
</html>
