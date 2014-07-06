<?php
require_once('auth.php');
if (!isset($_GET['imgid'])) die('');
$db = new SQLite3('monitor.db');
$row = $db->query('SELECT * FROM image WHERE id = ' . $_GET['imgid'])->fetchArray();
if (!isset($_POST['result'])) {
?><!doctype html>
<meta charset="utf-8">
<title>Name it - Monitor</title>
<h1>Name it</h1>
<form method="post" action="name.php?imgid=<?php echo $_GET['imgid']; ?>">
    <div><img src="image/<?php echo $row['hash']; ?>.jpg"></div>
    <div><input type="text" name="result" value="<?php echo $row['result']; ?>"></div>
    <div><input type="submit" value="submit"></div>
</form>
<?php
    exit;
};

$stmt1 = $db->prepare("UPDATE image SET result=? WHERE id=" . $_GET['imgid']);
$stmt1->bindParam(1, $_POST['result'], SQLITE3_TEXT);
if (!$stmt1->execute()) die('stmt1 failed');

$stmt2 = $db->prepare("UPDATE record SET result=? WHERE image=" . $_GET['imgid']);
$stmt2->bindParam(1, $_POST['result'], SQLITE3_TEXT);
if (!$stmt2->execute()) die('stmt2 failed');

header('Location: index.php');
die();
?>