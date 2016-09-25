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

$db = new PDO('sqlite:site.db');
$result = $db->query("SELECT link FROM demotivator_dropbox ORDER BY RANDOM () LIMIT 1");
$a = $result->fetch();
$f = tmpfile();
$fileMetadata = $dbxClient->getFile($a[0], $f);
$finalpos = ftell($f);
fseek($f, 0);
echo fread($f, $finalpos);
fclose($f);
?>
