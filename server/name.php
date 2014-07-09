<?php
require_once('auth.php');
if (!isset($_GET['imgid'])) die('');
$db = new SQLite3('monitor.db');
$row = $db->query('SELECT * FROM image WHERE id = ' . $_GET['imgid'])->fetchArray();
if (!isset($_POST['result']) || !isset($_POST['person'])) {
?><!doctype html>
<meta charset="utf-8">
<title>Name it - Monitor</title>
<h1>Name it</h1>
<form method="post" action="name.php?imgid=<?php echo $_GET['imgid']; ?>">
    <div><img src="image/<?php echo $row['hash']; ?>.jpg"></div>
    <div><label for="person">Person: </label><input type="text" id="person" name="person" value="<?php echo $row['person']; ?>"></div>
    <div><label for="result">Result: </label><input type="text" id="result" name="result" value="<?php echo $row['result']; ?>"></div>
    <div><input type="submit" value="submit"></div>
</form>
<?php
    exit;
};

$stmt1 = $db->prepare("UPDATE image SET result=?, person=? WHERE id=" . $_GET['imgid']);
$stmt1->bindParam(1, $_POST['result'], SQLITE3_TEXT);
$stmt1->bindParam(2, $_POST['person'], SQLITE3_TEXT);
if (!$stmt1->execute()) die('stmt1 failed');

$stmt2 = $db->prepare("UPDATE record SET result=?, person=? WHERE image=" . $_GET['imgid']);
$stmt2->bindParam(1, $_POST['result'], SQLITE3_TEXT);
$stmt2->bindParam(2, $_POST['person'], SQLITE3_TEXT);
if (!$stmt2->execute()) die('stmt2 failed');

header('Location: index.php');
die();
?>
