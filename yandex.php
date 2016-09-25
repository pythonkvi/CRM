<?php

function uploadPostImage($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://api-fotki.yandex.ru/api/users/rootkvi/photos/');
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth a0dc6547692a484fbb8017084b590465'));
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
  curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
  curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
  curl_setopt($ch, CURLOPT_POST, true);
  $params =  array('image'=>'@'.$url, 'title'=>'qq', 'album'=>'urn:yandex:fotki:rootkvi:album:141403');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  $data = curl_exec($ch);

  var_dump($data);
  $xml = simplexml_load_string($data);
 
  return array($xml->xpath('//f:img[@size="S"]/@href')[0]->href, $xml->xpath('//f:img[@size="XL"]/@href')[0]->href);
}

//var_dump(uploadPostImage('/var/www/iv/images/img1.png'));

?>
