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
<tr><td>id</td><td>person</td><td>result</td><td>datetime</td></tr>
</thead>
<tbody>
<?php
$db = new SQLite3('monitor.db');
$param_person = isset($_GET['person']) ? rawurldecode($_GET['person']) : NULL;
if ($param_person == NULL) {
    $stmt = $db->prepare('SELECT * FROM record ORDER BY id DESC;');
} else {
    $stmt = $db->prepare('SELECT * FROM record WHERE person=? ORDER BY id DESC;');
    $stmt->bindParam(1, $param_person, SQLITE3_TEXT);
}
$results = $stmt->execute();
while ($row = $results->fetchArray()) {
    $person = htmlspecialchars($row['person']);
    $person_encoded = rawurlencode($person);
    $result = htmlspecialchars($row['result']);
    echo "<tr><td>{$row['id']}</td><td><a href='index.php?person=$person_encoded'>$person</a></td><td><a href='name.php?imgid={$row['image']}'>$result</a></td><td>{$row['time']}</td></tr>" . PHP_EOL;
}
?>
</tbody>
</table>
