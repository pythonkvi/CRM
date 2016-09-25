<?php
require_once('authorized.php');

($result = $db->query("select l.link_text, l2.link_text as link2_text from news_attachment n join link l on n.link2_id = l.id join link l2 on n.link_id = l2.id where n.news_id ='".$_REQUEST["id"]."' order by n.id")) || die(json_encode($db->errorInfo(), true));

if ($result){
  print json_encode($result->fetchAll(PDO::FETCH_ASSOC));
} else {
  print json_encode(array());
}

?>
