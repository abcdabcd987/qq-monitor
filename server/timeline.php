<?php
require_once('auth.php');

$db = new SQLite3('monitor.db');
$results = $db->query('SELECT * FROM record ORDER BY id DESC');

$colors = array();
$events = array();
$last_date = 'yyyy-mm-dd';
$last_result = '<empty>';
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
  $now_date = substr($row['time'], 0, 10);
  $now_result = $row['result'];
  $now_time = substr($row['time'], 11, 8);
  if ($last_date != $now_date || $last_result != $now_result) {
    $events[] = array(
      'date'   => $now_date,
      'result' => htmlspecialchars($now_result),
      'start'  => $now_time,
      'end'    => $now_time
    );
    if ($last_date != $now_date && $last_result == $now_result) {
      $events[count($events)-1]['end'] = '23:59:59';
      $events[count($events)-2]['start'] = '00:00:00';
    }
  } else {
    $events[count($events)-1]['start'] = $now_time;
  }

  $last_date = $now_date;
  $last_result = $now_result;
  if (!array_key_exists($now_result, $colors)) {
    $cnt = count($colors)+1;
    $color = "color$cnt";
    $colors[$now_result] = $color;
  }
}
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Timeline - Monitor</title>
<style>
.day {
  height: 1em;
  margin-bottom: 0;
}
.day span {
  float: left;
  margin-right: 1em;
  line-height: 1em;
}
.bars {
  height: 100%;
  overflow: hidden;
  background-color: #f5f5f5
}
.bar {
  float: left;
  height: 100%;
}
#tooltip {
  position: fixed;
  right: 0;
  top: 0;
  background-color: #000;
  color: #fff;
  text-align: right;
}
.color1 { background-color: #5cb85c; }
.color2 { background-color: #5bc0de; }
.color3 { background-color: #f0ad4e; }
.color4 { background-color: #d9534f; }
* { font-family: monospace; }
thead { font-weight: bold; }
</style>
</head>
<body>
<h1>Timeline</h1>
<table id="table">
<thead>
<tr><td>date</td><td>start</td><td>end</td><td>result</td></tr>
</thead>
<tbody>
<?php
foreach ($events as $event) {
  echo "<tr><td>" . $event['date'] . "</td><td>" . $event['start'] . "</td><td>" . $event['end'] . "</td><td>" . $event['result']. "</td></tr>" . PHP_EOL;
}
?>
</tbody>
</table>
<div id="timeline">
<?php
$last_date = 'yyyy-mm-dd';
$tot_sec = 60*60*24;
$last_sec = -1;
$rows = count($events);

$st = 0;
for ($i = 0; $i < $rows; ++$i) {
  if ($i == $rows-1 || $events[$i+1]['date'] != $events[$st]['date']) {
    for ($j = $st, $k = $i; $j < $k; ++$j, --$k) {
      list($events[$j], $events[$k]) = array($events[$k], $events[$j]);
    }
    $st = $i+1;
  }
}

for ($i = 0; $i < $rows; ++$i) {
  $event = $events[$i];
  $now_date = $event['date'];

  $st_hh = intval(substr($event['start'], 0, 2));
  $st_mm = intval(substr($event['start'], 3, 2));
  $st_ss = intval(substr($event['start'], 6, 2));
  $st_sec = $st_hh*3600 + $st_mm*60 + $st_ss;

  $ed_hh = intval(substr($event['end'], 0, 2));
  $ed_mm = intval(substr($event['end'], 3, 2));
  $ed_ss = intval(substr($event['end'], 6, 2));
  $ed_sec = $ed_hh*3600 + $ed_mm*60 + $ed_ss;
  $dur_sec = $ed_sec - $st_sec;

  if ($now_date != $last_date) {
    $last_sec = 0;
    echo <<<EOF
  <div class="day">
    <span>$now_date</span>
    <div class="bars">

EOF;
  }

  if ($last_sec != $st_sec) {
    $percent = ($st_sec-$last_sec)*100.0/$tot_sec;
    echo <<<EOF
      <div class="bar" style="width: $percent%" title="Interval"></div>

EOF;
  }

  $st = $event['start'];
  $ed = $event['end'];
  $result = $event['result'];
  $color = $colors[$result];
  $percent = $dur_sec*100/$tot_sec;
  echo <<<EOF
      <div class="bar $color" title="[$st - $ed] $result" style="width: $percent%"></div>

EOF;
  
  $last_sec = $ed_sec;
  $last_date = $now_date;
  
  if ($i == $rows-1 || $events[$i+1]['date'] != $now_date) {
    echo <<<EOF
    </div>
  </div>

EOF;
  }
}
?>
</div>
<p id="tooltip"></p>
<script>
window.onload = function() {
  var elements = document.getElementsByClassName('bar');
  var tooltip = document.getElementById('tooltip');
  for (var i = 0; i < elements.length; ++i) {
    elements[i].addEventListener('mouseover', function() {
      tooltip.innerHTML = this.title;
    });
    elements[i].addEventListener('mouseout', function() {
      tooltip.innerHTML = '';
    });
  }
};
</script>
</body>
</html>
