<?php
require_once('auth.php');
?><!doctype html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,user-scalable=no">
<title>Record - Monitor</title>
<style>
* { font-family: monospace; }
thead { font-weight: bold; }
</style>
<h1>Record</h1>
<table>
<thead>
<tr><td>id</td><td>result</td><td>datetime</td></tr>
</thead>
<tbody>
<?php
$db = new SQLite3('monitor.db');
$results = $db->query('SELECT * FROM record ORDER BY id DESC;');
while ($row = $results->fetchArray()) {
    echo "<tr><td>" . $row['id'] . "</td><td><a href='name.php?imgid=" . $row['image'] . "'>" . htmlspecialchars($row['result']) . "</a></td><td>" . $row['time'] . "</td></tr>" . PHP_EOL;
}
?>
</tbody>
</table>
