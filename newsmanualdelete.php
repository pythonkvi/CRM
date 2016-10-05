<?php

require_once('newsmanualpassword.php');

if (isset($_REQUEST['save']) && $md5password == md5($_REQUEST['password']) && isset($_POST['id'])){
  $db = new PDO('sqlite:site.db');
  $db->exec("delete from link where id in (select link_id from news_attachment where news_id = '".(int)$_POST['id']."')"); //or die(print_r($db->errorInfo(), true));
  $db->exec("delete from link where id in (select link2_id from news_attachment where news_id = '".(int)$_POST['id']."')");// or die(print_r($db->errorInfo(), true));
  $db->exec("delete from news_attachment where news_id = '".(int)$_POST['id']."'");// or die(print_r($db->errorInfo(), true));
  $db->exec("delete from news where id = '".(int)$_POST['id']."'"); // or die(print_r($db->errorInfo(), true));
  echo "Информация удалена";
}

?>

<form name="newsform" action="" method="post" enctype="multipart/form-data">
   <table>
   <tr><td><label>ID:</label></td><td><input type="text" name="id"/></td></tr> 
   <tr><td><label>Пароль:</label></td><td><input type="password" name="password"/></td></tr>   
   <tr><td colspan="2"><div id="attachcontainer"/></td></tr>
   <tr><td colspan="2"><input type="submit" name="save" value="Сохранить"/></td></tr>
</form>
