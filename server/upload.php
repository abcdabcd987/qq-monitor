<?php
require_once('auth.php');
if (!isset($_FILES['file']) || $_FILES['file']['error'] > 0 || $_FILES['file']['size'] == 0) {
?><!doctype html5>
<meta charset="utf-8">
<title>Upload - Monitor</title>
<h1>Upload</h1>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" value="submit">
</form>
<?php
exit;
};?>
<pre>
<?php
$db = new SQLite3('monitor.db');

$hashed = sha1_file($_FILES['file']['tmp_name']);
$row = $db->query("SELECT id, person, result FROM image WHERE hash = '$hashed';")->fetchArray();
if (!$row) {
    $db->exec("INSERT INTO image (hash, person, result) VALUES ('$hashed', '<unnamed>', '<unnamed>');");
    $imgid = $db->lastInsertRowID();
    $imgprs = '<unnamed>';
    $imgres = '<unnamed>';
    move_uploaded_file($_FILES['file']['tmp_name'], "image/$hashed.jpg");
} else {
    $imgid = $row['id'];
    $imgprs = $row['person'];
    $imgres = $row['result'];
}
$stmt = $db->prepare("INSERT INTO record (image, person, result) VALUES (?, ?, ?);");
$stmt->bindParam(1, $imgid, SQLITE3_INTEGER);
$stmt->bindParam(2, $imgprs, SQLITE3_TEXT);
$stmt->bindParam(3, $imgres, SQLITE3_TEXT);
if (!$stmt->execute()) die('INSERT fail');
echo "Upload Success";
?>
</pre>
