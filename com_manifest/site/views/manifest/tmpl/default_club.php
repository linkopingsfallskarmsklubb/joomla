<?php
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$datatype = $app->input->get('datatype' ,'' , 'string');
?>
<script>
function updateType(element) {
  window.location =
    window.location.href.split('?')[0] + '?type=' + element.value;
}
</script>
<select id="manifest-type" class="manifest-param" onchange="updateType(this);">
  <option value="alla">Alla</option>
<?php foreach($this->jumptypes as $type): ?>
  <option <?php echo $type[0] == $this->jumptype ? 'selected="1"' : ''; ?>
    value="<?php echo $type[0]; ?>"><?php echo $type[1]; ?></option>
<?php endforeach; ?>
</select>

<?php if ($doc->getTitle()): ?>
<h1><?php echo $doc->getTitle(); ?></h1>
<?php endif; ?>
<p>
<?php foreach($this->results as $r_idx => $result): ?>
  <table class="manifest-data manifest-<?php echo $datatype . '-'.$r_idx; ?>">
    <tr>
      <th>År</th>
      <th>Jan</th>
      <th>Feb</th>
      <th>Mar</th>
      <th>Apr</th>
      <th>Maj</th>
      <th>Jun</th>
      <th>Jul</th>
      <th>Aug</th>
      <th>Sep</th>
      <th>Okt</th>
      <th>Nov</th>
      <th>Dec</th>
      <th>Total</th>
    </tr>
    <tr>
<?php
echo '<td class="manifest-club-year">' . $result[0]['Y'] . '</td>';
$oldY = $result[0]['Y'];
$expectedX = 1;
$rowTotal = 0;
$allYears = array($oldY);
$yearTotals = array();
$maxTotal = 0;
foreach($result as $row) {
  if ($oldY != $row['Y']) {
    if ($expectedX != 13) {
      for ($i = $expectedX; $i < 13; $i++) {
        echo '<td>0</td>';
      }
    }
    $yearTotals[$oldY] = $rowTotal;
    $oldY = $row['Y'];
    $allYears[] = $oldY;
    $expectedX = 1;
    $maxTotal = max($maxTotal, $rowTotal);
    echo '<td class="manifest-club-total">' . $rowTotal . '</td>';
    $rowTotal = 0;
    echo '</tr><tr>';
    echo '<td class="manifest-club-year">' . $row['Y'] . '</td>';
  }
  // Missing column, replace it with zero
  if ($expectedX != $row['X']) {
    for ($i = $expectedX; $i < (int)$row['X']; $i++) {
      echo '<td>0</td>';
    }
  }
  $expectedX = (int)$row['X']+1;
?>
      <td class="manifest-column-<?php echo $idx; ?>">
<?php
$rowTotal += (int)$row[$this->jumptype];
$yearTotals[$oldY] = $rowTotal;
$maxTotal = max($maxTotal, $rowTotal);

echo $row[$this->jumptype];
?>
      </td>
<?php
}
echo '<td class="manifest-club-total">' . $rowTotal . '</td>';
?>
    </tr>
  </table>
<br />
<?php foreach($allYears as $year): ?>
<span class="manifest-club-year"><?php echo $year; ?></span>
<div class="manifest-club-bar" style="width: <?php
echo 350 * $yearTotals[$year] / $maxTotal;
?>px">
</div>
<span class="manifest-club-year-total"><?php echo $yearTotals[$year]; ?></span>
<br />

<?php endforeach; ?>
<?php endforeach; ?>

</p>
