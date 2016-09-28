<?php

//curl -X POST https://api.dropboxapi.com/2/files/list_folder --header "Authorization: Bearer iFg29ADe7vkAAAAAAAANEZP8rWJMI54WLiUMy9_nWWMIqvZEtZDs3SGN0rlIuhBh" --header "Content-Type: application/json" --data "{\"path\": \"\",\"recursive\": false,\"include_media_info\": false,\"include_deleted\": false,\"include_has_explicit_shared_members\": false}"

function uploadPostImage(){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/list_folder');
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer iFg29ADe7vkAAAAAAAANEZP8rWJMI54WLiUMy9_nWWMIqvZEtZDs3SGN0rlIuhBh', 'Expect:', 'Content-Length: 129', 'Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"path\": \"\",\"recursive\": false,\"include_media_info\": false,\"include_deleted\": false,\"include_has_explicit_shared_members\": false}");

  $data = curl_exec($ch);
  var_dump($data);
  curl_close($ch);

  return $data;
}

$db = new PDO('sqlite:site.db');
$db->query("DELETE FROM demotivator_dropbox");
$folderMetadata = json_decode(uploadPostImage());

var_dump($folderMetadata );
foreach ($folderMetadata->{'entries'} as $k=>$v) {
    print "INSERT INTO demotivator_dropbox (link) values ('".$v->{"path_lower"}."')\n";
    $db->query("INSERT INTO demotivator_dropbox (link) values ('".$v->{"path_lower"}."')");
}

#$f = fopen("working-draft.txt", "w+b");
#$fileMetadata = $dbxClient->getFile("/working-draft.txt", $f);
#fclose($f);
#print_r($fileMetadata);
?>
