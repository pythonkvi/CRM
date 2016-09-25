<?php
require_once('authorized.php');

($result = $db->query("select n.id, n.header, strftime('%d/%m/%Y', n.newsdate) as newsdate, n.owner, case when length(ifnull(n.category_id, '')) = 0 then 0 else n.category_id end as category_id, (select l.link_text from link l join news_attachment na where l.id = na.link2_id and na.news_id = n.id limit 1) thumb_img from news n where n.parent_id is null ".
(isset($_REQUEST['month']) && $_REQUEST['month'] != '-' ? " and strftime('%m', n.newsdate)='".$_REQUEST['month']."' " : '').
(isset($_REQUEST['year']) && $_REQUEST['year'] != '-' ? " and strftime('%Y', n.newsdate)='".$_REQUEST['year']."' " : '').
(isset($_REQUEST['date']) ? " and n.newsdate='".$_REQUEST['date']."' " : '').
(isset($_REQUEST['q']) ? " and n.body like '%".mb_ereg_replace("'", "''", $_REQUEST['q'])."%'" : '').
(isset($_REQUEST['category_id']) ? " and n.category_id='".$_REQUEST['category_id']."' " : '').
(isset($_REQUEST['lastNewsID']) ? " and n.id < '".$_REQUEST['lastNewsID']."' " : '').
" order by n.id desc".
(isset($_REQUEST['limit']) ? " limit ".$_REQUEST['limit'] : ""))) || die(json_encode($db->errorInfo(), true));

if ($result){
  print json_encode($result->fetchAll(PDO::FETCH_ASSOC));
} else {
  print json_encode(array());
}

?>
