<?php

# Include the Dropbox SDK libraries
require_once "Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfo = dbx\AppInfo::loadFromJsonFile("dropbox.json");
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

$authorizeUrl = $webAuth->start();

#echo "1. Go to: " . $authorizeUrl . "\n";
#echo "2. Click \"Allow\" (you might have to log in first).\n";
#echo "3. Copy the authorization code.\n";
#$authCode = \trim(\readline("Enter the authorization code here: "));

#list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
#print "Access Token: " . $accessToken . "\n";

$accessToken = "EGstxc6sviYAAAAAAAAAAapJ5Oq4K5NqS9WeuxV7Z-Be9HYkUQHWBnl0AMw2T97b";
$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();

print_r($accountInfo);

#$f = fopen("working-draft.txt", "rb");
#$result = $dbxClient->uploadFile("/working-draft.txt", dbx\WriteMode::add(), $f);
#print_r($result);

$db = new PDO('sqlite:site.db');
$db->query("DELETE FROM demotivator_dropbox");
$folderMetadata = $dbxClient->getMetadataWithChildren("/Photos");

#var_dump($folderMetadata );
foreach ($folderMetadata["contents"] as $k=>$v) {
  if ($v["is_dir"] != 1) {
    print "INSERT INTO demotivator_dropbox (link) values ('".$v["path"]."')\n";
    $db->query("INSERT INTO demotivator_dropbox (link) values ('".$v["path"]."')");
  }
}

#$f = fopen("working-draft.txt", "w+b");
#$fileMetadata = $dbxClient->getFile("/working-draft.txt", $f);
#fclose($f);
#print_r($fileMetadata);
?>
