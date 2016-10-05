<?php
	if (isset($_GET['login'])){
          $db = new PDO('sqlite:site.db');
          $result = $db->query("select alias, phone from phonebook where login='".$_GET['login']."' and alias like '%".$_GET['query']."%' union select alias, phone from phonebook where login='".$_GET['login']."' and phone like '%".$_GET['query']."%'") or die(print_r($db->errorInfo(), true));
          $arr = array();
	  if ($result){
	      while ($line = $result->fetch()){
        	  array_push($arr, array($line['alias'], $line['phone']));
	      }
	  }
  
  usort($arr, "ArrayItem::comparing");
  echo json_encode($arr);

	}
?>
