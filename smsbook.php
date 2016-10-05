<html>
<head>
<title>SMS+ book</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<style type="text/css">
div{
  clear:both!important;
  display:block!important;
  width:100%!important;
  float:none!important;
  margin:0!important;
  padding:0!important;
}
body{
-webkit-text-size-adjust:none;
font-family:Helvetica, Arial, Verdana, sans-serif;
padding:5px;
background: lightgreen;
}
</style>
</head>
<body>
<?php

    if (isset($_POST['login']) && isset($_POST['password']))
    {
	if ($_POST['login'] == '9262571142'){
          echo "Add value...";
          $db = new PDO('sqlite:site.db');
          $db->exec("insert into phonebook (login, phone, alias) values ('".$_POST['login']."', '".$_POST['to']."', '".$_POST['message']."')") or die(print_r($db->errorInfo(), true));
	}
    } else {
        print '<div><table><form action="" method="post">'.
                '<tr><td>Login:</td><td><input type="text" name="login"/></td></tr>'.
                '<tr><td>Password:</td><td><input type="text" name="password"/></td></tr>'.
                '<tr><td>To:</td><td><input type="text" name="to" maxlength="11"></td></tr>'.
                '<tr><td>Text:</td><td><textarea name="message" cols="24" rows="10" maxlength="200" onkeyup="document.getElementById(\'numsymbols\').innerHTML=\'Symbols: \' + this.value.length"></textarea></td></tr>'.
                '<tr><td colspan="2"><input type="submit" value="Send"/></td></tr>'.
                '</form></table></div>'.
		'<div id="numsymbols">Symbols: 0</div>';
    }
?>
</body>
</html>
