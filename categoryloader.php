<?php
if (isset($_POST['id'])) {
	$result_array = array();
	$db = new PDO('sqlite:site.db');
	$result = $db->query("select id, category, coalesce(parent_id, 0) from news_category ".($_POST['id'] == 0 ? " where 1 = 1" : " where parent_id='".$_POST['id']."'"));
	while($arr = $result->fetch()) {
		$result_array[]= array($arr[0], $arr[1], $arr[2]);
	}
	echo json_encode($result_array);
}
?>