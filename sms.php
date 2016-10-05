<html>
<head>
<title>SMS+</title>
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
require_once('simple_html_dom.php');

    if (isset($_POST['login']) && isset($_POST['password']))
    {
        #do work
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://smsplus.megafonmoscow.ru');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
        $data = curl_exec($ch);

	$html = str_get_html($data);
        $tokArr = $html->find('input[name="authenticity_token"]');
        $authID = ($tokArr[0]->value);

        /*var_dump($data);
		file_put_contents("c:\\tmp\\log.txt", $data);
		die("tokenID=".$authID);*/
		
        curl_setopt($ch, CURLOPT_URL, 'https://smsplus.megafonmoscow.ru/session');
        curl_setopt($ch, CURLOPT_POST, true);
        $params = array( 'login' => $_POST['login'],
			 'password' => $_POST['password'],
			 'authenticity_token' => $authID, 
			 'remember_me' => '1');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $data = curl_exec($ch);

        /*var_dump($params);
	  var_dump($data);*/

        curl_setopt($ch, CURLOPT_URL, 'https://smsplus.megafonmoscow.ru/messages');
        $params = array('authenticity_token' => $authID,  
			'outgoing_message[destination]' => $_POST['to'],
			'outgoing_message[body]' => $_POST['message'],
			'send' => 'true');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $data = curl_exec($ch);

        /*var_dump($params);
	  var_dump($data);*/

        curl_close($ch);
    } else {
        print '<div><table><form action="" method="post">'.
                '<tr><td>Login:</td><td><input type="text" name="login"/></td></tr>'.
                '<tr><td>Password:</td><td><input type="text" name="password"/></td></tr>'.
                '<tr><td>To:</td><td><input type="text" name="to"/><input type="button" onclick="suggesting()" value="..."/></td></tr>'.
                '<tr><td>Text:</td><td><textarea name="message" cols="24" rows="10" maxlength="200" onkeyup="document.getElementById(\'numsymbols\').innerHTML=\'Symbols: \' + this.value.length"></textarea></td></tr>'.
                '<tr><td colspan="2"><input type="submit" value="Send"/></td></tr>'.
                '</form></table></div>'.
		'<div id="numsymbols">Symbols: 0</div>';
    }
?>

<script type="text/javascript">
function suggesting(){
  var eles = document.getElementsByTagName("div")
  for (i=0;i < eles.length;i++)
  if (eles[i].id == "suggester"){
    eles[i].parentNode.removeChild(eles[i])
  }
  if (document.forms[0].to.value.length == 0) return

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
       var result = eval(xmlhttp.responseText);

       if (result.length == 1) {
	  document.forms[0].to.value = result[0][1]
          return
       }

       var div = document.createElement("div")
       var ul = document.createElement("ul")
       div.appendChild(ul)
       div.id = "suggester"
 
       for(i=0; i<result.length; i++) {
         var e = document.createElement("li")
         e.innerText = result[i][0]
         e.data = result[i][1]
         e.onclick = function(a){
	   document.forms[0].to.value = this.data;
           div.parentNode.removeChild(div)
         }
         ul.appendChild(e)
       }

       document.forms[0].to.parentNode.appendChild(div)
    }
  }

  xmlhttp.open("GET","smsbooklist.php?login=" + document.forms[0].login.value + "&query=" +  document.forms[0].to.value, false);
  xmlhttp.send();  
}
</script>

</body>
</html>
