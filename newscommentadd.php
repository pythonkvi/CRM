<?php
if (isset($_POST["news_id"]) && isset($_POST["news_text"])){
  $db = new PDO('sqlite:site.db');
  $db->exec("insert into news (body, newsdate, parent_id, owner) values ('".$_POST["news_text"]."', date('now'), '".(int)$_POST["news_id"]."', '".$_POST["owner"]."')");
  $news_id = $db->lastInsertId();
  echo json_encode(array("news_id"=>$news_id, "news_text"=>$_POST["news_text"], "news_date" => time(), "parent_id" => (int)$_POST["news_id"], "owner" => $_POST["owner"]));
}
?>
