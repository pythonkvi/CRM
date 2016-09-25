<?php
session_start();

if (isset($_POST['user_id']) && 
    isset($_POST['first_name']) && 
    isset($_POST['last_name']) &&
    isset($_POST['auth_type']))
{
  $_SESSION['user_id'] = $_POST['user_id'];
  $_SESSION['first_name'] = $_POST['first_name'];
  $_SESSION['last_name'] = $_POST['last_name'];
  $_SESSION['auth_type'] = $_POST['auth_type'];
}
?>