<?php

if (!isset($_GET['id'])) die();

$db = new PDO('sqlite:site.db');
$result = $db->query("select link from file where id ='".(int)$_GET['id']."'");
 if($arr = $result->fetch()) {
// We'll be outputting a PDF
header('Content-type: application/octet-stream');

// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="'.basename($arr[0]).'"');

// content	
readfile(".".$arr[0]);
 }

$db->exec("update file set downloads=downloads + 1 where id='".(int)$_GET['id']."'")
?>
