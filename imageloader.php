<?php

if (isset($_GET["image"]) && preg_match("/http:/", $_GET["image"])){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_GET["image"]);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    
    $image = curl_exec($ch);
      
    header('Content-type: image/png');
    echo $image;
}

?>
