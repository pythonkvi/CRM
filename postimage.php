<?php

function uploadPostImage($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://postimage.org/profile.php');
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
  curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
  curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
  curl_setopt($ch, CURLOPT_POST, true);
  $params =  array('login'=>'news@ivalera.ru', 'password'=>'12qwaszx');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  curl_exec($ch);
  
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_URL, 'http://postimage.org/index.php');
  $params = array('mode'=>'local',
		  'areaid'=>'',
		  'forumurl'=>'http://www.postimage.org/',
		  'tpl'=>'.',
		  'code'=>'',
		  'content'=>'',
		  'ver'=>'',
		  'addform'=>'',
		  'mforum'=>'',
		  'um'=>'computer',
                  'upload_error'=>'',
		  'gallery_id'=>'8r4yzf8g',
		  'session_upload'=>'',
		  'ui'=>'',
		  'hash'=>"251",
		  'MAX_FILE_SIZE'=>"10485760",
		  'upload[]'=> "@$url",
		  'optsize'=>4,
		  'adult'=>'no',
		  'gallery'=>'1mkvz7tc',
		  'submit'=>'Загрузить!',
		  'save'=>1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  $data = curl_exec($ch);

  require_once('simple_html_dom.php');
  $html = str_get_html($data);
  $tokArr = $html->find('textarea[id="code_2"]');
 
  var_dump($data); 
  preg_match("/\[url=([A-z0-9:\/\_\.]+)\]\[img\]([A-z0-9:\/\_\.]+)\[\/img\]\[\/url\]/",$tokArr[0]->text(), $matches);

  curl_setopt($ch, CURLOPT_URL, 'http://postimage.org/profile.php?logout=1');
  curl_exec($ch);

  return array($matches[2], $matches[1]);
}

var_dump( uploadPostImage("/var/www/iv/images/img0.png"));

?>
