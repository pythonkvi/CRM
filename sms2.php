<html>
<head>
<title>SMS</title>
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
	if ($_POST['login'] != '9262571142' && $_POST['password'] != 'kwkcjchx') die("Invalid auth");

	define('SMSPILOT_APIKEY', 'IKS643Q44DG56T0IE00VR3E841955PXY696F963S4549N6NL6XCP7E69R5TFB657');
	define('SMSPILOT_FROM', '79262571142'); // new in 1.8.1

	require_once('smspilot.php');
	if (sms($_POST['to'], $_POST['message'])) {
	
                $result = 'Ваше сообщение успешно отправлено, ответ сервера: '.sms_success();

                $status = sms_status();

                $ids = '';
                foreach ( $status as $s ) $ids .= $s['id'].',';

                $result .= '<p>ID: '.substr($ids,0,-1).'</p>';

                $result .= '<pre>'.print_r( $status , true ).'</pre>';

        } else {

                $result = '<span style="color: red">Ошибка! ' . sms_error() .'</span>';
}
	echo $result;
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
