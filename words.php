<?php
/**
 * Created by IntelliJ IDEA.
 * User: valery
 * Date: 05.10.16
 * Time: 0:06
 */

$db = new PDO('sqlite:site.db');
$result = $db->query("select vocab from words where vocab like '%ить'");
var_dump($result->fetchAll(PDO::FETCH_ASSOC));

?>