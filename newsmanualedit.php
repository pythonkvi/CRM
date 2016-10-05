<?php

require_once('newsmanualpassword.php');

if (isset($_REQUEST['save']) && $md5password == md5($_REQUEST['password'])){
  $db = new PDO('sqlite:site.db');
  $db->exec("update news set header = '" .mb_ereg_replace("'", "''", $_REQUEST['header']). "', body = '" .mb_ereg_replace("'", "''", $_REQUEST['body']). "', newsdate = '".mb_ereg_replace("'", "''", $_REQUEST['newsdate']). "', owner = '".mb_ereg_replace("'", "''", $_REQUEST['owner'])."', category_id = '".$_REQUEST['category_id']."' where id = '".(int)$_REQUEST['id']."'")  or die(print_r($db->errorInfo(), true));
  echo "Информация обновлена";
}

if (isset($_REQUEST['id'])){
  $db = new PDO('sqlite:site.db');
  $result = $db->query("select id, header, body, newsdate, owner, parent_id, category_id from news where id ='".(int)$_REQUEST['id']."'");
  if($arr = $result->fetch()) {
    $id = $arr['id'];
    $header = $arr['header'];
    $body = $arr['body'];
    $newsdate = $arr['newsdate'];
    $owner = $arr['owner'];
    $parent_id = $arr['parent_id'];
    $category_id = $arr['category_id'];
  }
}

require_once("newsmanualform.php");
?>
