<?php

//curl -X POST https://content.dropboxapi.com/2/files/download --header "Authorization: Bearer iFg29ADe7vkAAAAAAAANEZP8rWJMI54WLiUMy9_nWWMIqvZEtZDs3SGN0rlIuhBh"     --header "Dropbox-API-Arg: {\"path\": \"/nomer.jpg\"}"

function uploadPostImage($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/download');
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer iFg29ADe7vkAAAAAAAANEZP8rWJMI54WLiUMy9_nWWMIqvZEtZDs3SGN0rlIuhBh', 'Dropbox-API-Arg: {"path": "'.$url.'"}', 'Expect:', 'Content-Length:', 'Content-Type:'));
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
  curl_setopt($ch, CURLOPT_POST, true);
  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

$db = new PDO('sqlite:site.db');
$result = $db->query("SELECT link FROM demotivator_dropbox ORDER BY RANDOM ()");
$a = $result->fetch();
$fileMetadata = uploadPostImage($a[0]);
echo $fileMetadata;

?>
