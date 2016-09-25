<?php
	md5($_REQUEST['apikey']) == "0e50a1328705e2b0902f923cd38a2be2" or die(json_encode(array("Non authorized")));
	($db = new PDO('sqlite:../site.db')) || die(json_encode($db->errorInfo(), true));
?>
