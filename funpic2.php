<?php

# Include the Dropbox SDK libraries
require_once "guzzle/vendor/psr/http-message/src/MessageInterface.php";
require_once "guzzle/vendor/psr/http-message/src/RequestInterface.php";
require_once "guzzle/vendor/psr/http-message/src/ResponseInterface.php";
require_once "guzzle/vendor/psr/http-message/src/UriInterface.php";
require_once "guzzle/vendor/psr/http-message/src/StreamInterface.php";
require_once "guzzle/vendor/guzzlehttp/psr7/src/MessageTrait.php";
require_once "guzzle/vendor/guzzlehttp/psr7/src/Request.php";
require_once "guzzle/vendor/guzzlehttp/psr7/src/Response.php";
require_once "guzzle/vendor/guzzlehttp/psr7/src/Uri.php";
require_once "guzzle/vendor/guzzlehttp/psr7/src/functions_include.php";
require_once "guzzle/vendor/guzzlehttp/psr7/src/Stream.php";
require_once "guzzle/vendor/guzzlehttp/promises/src/functions_include.php";
require_once "guzzle/vendor/guzzlehttp/promises/src/TaskQueue.php";
require_once "guzzle/vendor/guzzlehttp/promises/src/PromiseInterface.php";
require_once "guzzle/vendor/guzzlehttp/promises/src/Promise.php";
require_once "guzzle/vendor/guzzlehttp/promises/src/FulfilledPromise.php";
require_once "dropbox-php-sdk/src/Dropbox/Dropbox.php";
require_once "dropbox-php-sdk/src/Dropbox/DropboxApp.php";
require_once "dropbox-php-sdk/src/Dropbox/DropboxClient.php";
require_once "dropbox-php-sdk/src/Dropbox/Security/RandomStringGeneratorInterface.php";
require_once "dropbox-php-sdk/src/Dropbox/Security/RandomStringGeneratorTrait.php";
require_once "dropbox-php-sdk/src/Dropbox/Security/RandomStringGeneratorFactory.php";
require_once "dropbox-php-sdk/src/Dropbox/Security/OpenSslRandomStringGenerator.php";
require_once "dropbox-php-sdk/src/Dropbox/Store/PersistentDataStoreInterface.php";
require_once "dropbox-php-sdk/src/Dropbox/Store/PersistentDataStoreFactory.php";
require_once "dropbox-php-sdk/src/Dropbox/Store/SessionPersistentDataStore.php";
require_once "dropbox-php-sdk/src/Dropbox/Authentication/DropboxAuthHelper.php";
require_once "dropbox-php-sdk/src/Dropbox/Authentication/OAuth2Client.php";
require_once "dropbox-php-sdk/src/Dropbox/DropboxRequest.php";
require_once "dropbox-php-sdk/src/Dropbox/DropboxResponse.php";
require_once "dropbox-php-sdk/src/Dropbox/Http/DropboxRawResponse.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/ModelInterface.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/BaseModel.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/MediaMetadata.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/MediaInfo.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/PhotoMetadata.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/FileMetadata.php";
require_once "dropbox-php-sdk/src/Dropbox/Models/File.php";
require_once "dropbox-php-sdk/src/Dropbox/Http/Clients/DropboxHttpClientInterface.php";
require_once "dropbox-php-sdk/src/Dropbox/Http/Clients/DropboxHttpClientFactory.php";
require_once "dropbox-php-sdk/src/Dropbox/Http/Clients/DropboxGuzzleHttpClient.php";
require_once "dropbox-php-sdk/src/Dropbox/Exceptions/DropboxClientException.php";
require_once "guzzle/src/functions_include.php";
require_once "guzzle/src/Handler/Proxy.php";
require_once "guzzle/src/Handler/CurlMultiHandler.php";
require_once "guzzle/src/Handler/CurlFactoryInterface.php";
require_once "guzzle/src/Handler/CurlHandler.php";
require_once "guzzle/src/Handler/StreamHandler.php";
require_once "guzzle/src/Handler/CurlFactory.php";
require_once "guzzle/src/Handler/EasyHandle.php";
require_once "guzzle/src/Exception/GuzzleException.php";
require_once "guzzle/src/Exception/TransferException.php";
require_once "guzzle/src/Exception/RequestException.php";
require_once "guzzle/src/Exception/BadResponseException.php";
require_once "guzzle/src/Exception/ClientException.php";
require_once "guzzle/src/PrepareBodyMiddleware.php";
require_once "guzzle/src/Middleware.php";
require_once "guzzle/src/RedirectMiddleware.php";
require_once "guzzle/src/ClientInterface.php";
require_once "guzzle/src/HandlerStack.php";
require_once "guzzle/src/Client.php";
require_once "guzzle/src/RequestOptions.php";


use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;

//Configure Dropbox Application
$accessToken = "iFg29ADe7vkAAAAAAAANEZP8rWJMI54WLiUMy9_nWWMIqvZEtZDs3SGN0rlIuhBh";
$app = new DropboxApp("olvdqp49tf0ml9q", "93s63onrwzgvrzq", $accessToken);

//Configure Dropbox service
$dropbox = new Dropbox($app);

//DropboxAuthHelper
$authHelper = $dropbox->getAuthHelper();

#echo "1. Go to: " . $authorizeUrl . "\n";
#echo "2. Click \"Allow\" (you might have to log in first).\n";
#echo "3. Copy the authorization code.\n";
#$authCode = \trim(\readline("Enter the authorization code here: "));

#list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
#print "Access Token: " . $accessToken . "\n";

/*$db = new PDO('sqlite:site.db');
$result = $db->query("SELECT link FROM demotivator_dropbox ORDER BY RANDOM () LIMIT 1");
$a = $result->fetch();*/
$a = array();
$a[0] = "/nomer.jpg";
$f = tmpfile();
$fileMetadata = $dropbox->download($a[0]);
fwrite($f, $fileMetadata->getContents());
$finalpos = ftell($f);
fseek($f, 0);
echo fread($f, $finalpos);
fclose($f);
?>
