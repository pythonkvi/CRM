<?php

class ArrayItem{
  public function __construct($item, $level = 0){
    $this->item = $item;
    $this->level = $level;
  }

  public static function comparing($c1, $c2){
    if ($c1['id'] > $c2['id']) return 1;
    else if ($c1['id'] < $c2['id']) return -1;
    else 0;
  }
}

//$_POST = array();
//$_POST["news_id"] = 61;

if (isset($_POST["news_id"]))
{
  $db = new PDO('sqlite:site.db');
  $result_array = array();
  $temp_array = array();

  $result = $db->query("select id, header, body, newsdate, owner from news where id ='".(int)$_POST["news_id"]."' order by id desc");
  if ($result){
    array_push($temp_array, new ArrayItem($result->fetch()));
  }  

  while(count($temp_array) > 0){
    $news_item = array_shift($temp_array);
    $news_item->item['level'] = $news_item->level;
    
    if ($news_item->item['id'] != (int)$_POST["news_id"]){
      array_push($result_array, $news_item->item);
    }

    $result = $db->query("select id, header, body, newsdate, owner, parent_id from news where parent_id ='".$news_item->item['id']."' order by id desc");
    if ($result){
      while ($line = $result->fetch()){
	array_push($temp_array, new ArrayItem($line,  $news_item->level + 1));
      }
    }
  }
  usort($result_array, "ArrayItem::comparing");
  echo json_encode($result_array);
}
?>