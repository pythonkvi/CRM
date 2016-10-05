<?php

require_once "twitteroauth/twitteroauth.php";

define("CONSUMER_KEY", "nUwQcQMOFjk1UvTFq0QWw");
define("CONSUMER_SECRET", "OiKlNrzeKuAuqW6wrpeqR0sZzG9KKxZbWdOkibY");
define("OAUTH_TOKEN", "190291252-XFrbhC5NpHShIInwoPSQjbK6mBHm5kmOcCTjkvoJ");
define("OAUTH_SECRET", "3Mtt8JNadL19ooUkELqG6eY7CkdvU2k7rt8iMtM87h4");

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
$content = $connection->get('account/verify_credentials');

if ($twittertext != FALSE) {
  $connection->post('statuses/update', array('status' => $twittertext));
}
?>
