<?php

function uploadPostImage($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/download');
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer iFg29ADe7vkAAAAAAAANEZP8rWJMI54WLiUMy9_nWWMIqvZEtZDs3SGN0rlIuhBh', 'Dropbox-API-Arg: {"path": "'.$url.'"}', 'Expect:', 'Content-Length:', 'Content-Type:'));
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
  curl_setopt($ch, CURLOPT_POST, true);
  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

var_dump(uploadPostImage('/nomer.jpg'));

?>
