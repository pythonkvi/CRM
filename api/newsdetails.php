<?php
require_once('authorized.php');

($result = $db->query("select id, body, header, newsdate, owner, case when length(ifnull(category_id, '')) = 0 then 0 else category_id end as category_id from news where id='".$_REQUEST['id']."'" )) || die(json_encode($db->errorInfo(), true));

if ($result){
  print json_encode($result->fetch(PDO::FETCH_ASSOC));
} else {
  print json_encode(array());
}

?>
