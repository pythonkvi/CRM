<div id="fb-root"></div>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '312196692172071', // App ID
      channelUrl : '//ivalera.ru/fbchannel.php', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

   // Additional initialization code here
  FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
   FB.login(function(response) {
     if (response.authResponse) {
       FB.api('/me', function(response) {
       console.log(response);
       $.ajax( {  url: "session.php",
                   data: { "user_id": response.id, "last_name": response["last_name"], "first_name": response["first_name"], "auth_type" : "fb" },
                   type: "POST",
                   success: function (data){ }
          })
     });
     } else {
       console.log('User cancelled login or did not fully authorize.');
     }
   });
 }
 });
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/ru_RU/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

<div class="fb-login-button" data-show-faces="true" data-width="200" data-max-rows="1"></div>
