<div id="vkcom">
<script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript"></script>

<a id="login_button" onclick="VK.Auth.login(authInfo, 3);"></a>

<script language="javascript">
userid = 0
VK.init({
  apiId: 2727666
});
function authInfo(response) {
  if (response.session) {
    userid = response.session.mid
    VK.api("getProfiles", {uids: userid, fields: "uid, first_name, last_name, contacts"}, function (data){
      if (data.response){
        console.log(data.response);
	$.ajax(	{  url: "session.php",
	           data: { "user_id": userid, "last_name": data.response[0]["last_name"], "first_name": data.response[0]["first_name"], "auth_type" : "vkcom" }, 
	           type: "POST",
	           success: function (data){ }
	  })
      }
})
    //console.log(response.session)
  } else {
    console.log('not auth');
  }
}
VK.Auth.getLoginStatus(authInfo);
VK.UI.button('login_button');
</script>
</div>
