<?php
  session_start();
  if (isset($_GET['token'])){
    $_SESSION['gtoken'] = $_GET['token'];
  }
?>
