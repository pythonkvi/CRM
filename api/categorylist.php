<?php
require_once('authorized.php');

($result = $db->query("select id, category, coalesce(parent_id, 0) as parent_id from news_category order by id")) || die(json_encode($db->errorInfo(), true));

if ($result){
  print json_encode($result->fetchAll(PDO::FETCH_ASSOC));
} else {
  print json_encode(array());
}

?>
