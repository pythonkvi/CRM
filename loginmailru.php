<div id="mailru">
<script type="text/javascript" src="http://cdn.connect.mail.ru/js/loader.js">
</script>
<a class='mrc__connectButton'></a>

<script type="text/javascript">
mailru.loader.require("api", function(){
   mailru.connect.initButton()
   mailru.connect.init('663354', 'a586652dd8a1d2037db195e05e7a4bb0');
  
   userid = mailru.session["oid"]
   mailru.common.users.getInfo(function(user_list) {
     if (user_list[0]){
       console.log(user_list[0]);
       $.ajax(	{  url: "session.php",
	           data: { "user_id": userid, "last_name": user_list[0]["last_name"], "first_name": user_list[0]["first_name"], "auth_type" : "mailru" }, 
	           type: "POST",
	           success: function (data){ }
	  })
     }
   }, userid);   

   //console.log(mailru.session);
})
</script>
</div>
