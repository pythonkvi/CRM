<?php

//var_dump($_REQUEST);
require_once('newsmanualpassword.php');
echo "Post limit:".ini_get("post_max_size")."<br/>";
echo "File limit:".ini_get("upload_max_filesize")."<br/>";

if (isset($_REQUEST['save']) && $md5password == md5($_REQUEST['password'])){
  $images = array();
  foreach($_FILES['attachment']['tmp_name'] as $file){
      $images[] = $file;
  }

  if (mb_ereg('^http://.+$', $_REQUEST['body'])){
     $c = curl_init();
     curl_setopt($c, CURLOPT_BINARYTRANSFER, true);
     curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($c, CURLOPT_URL, $_REQUEST['body']);
     $contents = curl_exec($c);
     curl_close($c);

     $tmpfname = tempnam("/tmp", "img");
     $handle = fopen($tmpfname, "w");
     fwrite($handle, $contents);
     fclose($handle);

     $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
     if (mb_ereg('image',  finfo_file($finfo, $tmpfname))) {
        $images[] = $tmpfname;

     	$_REQUEST['body'] = "<a href='" .$_REQUEST['body']. "' target='_blank'>".$_REQUEST['body']."</a>";
     }
     finfo_close($finfo);
  }

  $db = new PDO('sqlite:site.db');
  $db->exec("insert into news (header, body, newsdate, owner, category_id) values ('" .mb_ereg_replace("'", "''", $_REQUEST['header']). "','" .mb_ereg_replace("'", "''", $_REQUEST['body']). "','".mb_ereg_replace("'", "''", $_REQUEST['newsdate']). "', '".mb_ereg_replace("'", "''", $_REQUEST['owner'])."', '".$_REQUEST['category_id']."') ") or die(print_r($db->errorInfo(), true));
  $news_id = $db->lastInsertId();
  
  require_once('yandex.php');
  for($i = 0; $i < count($images); ++$i){
    $file = $images[$i];
    $upl_imgs = uploadPostImage($file);
    // 1 is big image,  0 is small
    $link_id = md5($upl_imgs[0]);
    $link_text = $upl_imgs[0];
    $link2_id = md5($upl_imgs[1]);
    $link2_text = $upl_imgs[1];

    $marks = json_decode($_REQUEST["image_marks"][$i]);
    foreach($marks as $mark) {
      $db->exec("insert into link_mark (link_id, x1, x2, y1, y2, width, height, mark) values ('" .$link_id. "','".$mark->x1."','".$mark->x2."','".$mark->y1."','".$mark->y2."','".$mark->width."','".$mark->height."','" .mb_ereg_replace("'", "''", $mark->text)."')");
    }

    $db->exec("insert into link (id, link_text) values ('" .$link_id. "','" .mb_ereg_replace("'", "''", $link_text)."') ");
    $db->exec("insert into link (id, link_text) values ('" .$link2_id. "','" .mb_ereg_replace("'", "''", $link2_text)."') ");
    $db->exec("insert into news_attachment (news_id, link_id, link2_id) values ('" .$news_id. "','" .$link2_id."','" .$link_id."') ");
  }
}

date_default_timezone_set('Asia/Dubai');
$now = new DateTime();
$newsdate = $now->format('Y-m-d');
require_once("newsmanualform.php");
?>
