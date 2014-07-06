<?php
require_once('auth.php');?>
<pre>
<?php
$db = new SQLite3('monitor.db');

$result = $db->exec("CREATE TABLE record (
    id INTEGER PRIMARY KEY, 
    image INTEGER NOT NULL, 
    result TEXT, 
    time DATETIME DEFAULT (DATETIME('now', 'localtime'))
)");
echo "create table record: " . $result . PHP_EOL;

$result = $db->exec("CREATE TABLE image (
    id INTEGER PRIMARY KEY, 
    hash TEXT UNIQUE NOT NULL, 
    result TEXT
)");
echo "create table image: " . $result . PHP_EOL;
?>
</pre>