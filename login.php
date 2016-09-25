<div id="authcontainer">
<?php

require_once('loginvkcom.php');
require_once('loginmailru.php');
require_once('loginfacebook.php');

if (isset($_SESSION['auth_type'])){
  echo "<div>";
  if ($_SESSION['auth_type'] == "vkcom"){
    echo '<a href="#" onclick="handleLogout(\'' . $_SESSION['auth_type'] . '\')">Выйти из VK.com</a>';
  } elseif($_SESSION['auth_type'] == "mailru"){
    echo '<a href="#" onclick="handleLogout(\'' . $_SESSION['auth_type'] .'\')">Выйти из Mail.ru</a>';
  } elseif($_SESSION['auth_type'] == "fb"){
    echo '<a href="#" onclick="handleLogout(\'' . $_SESSION['auth_type'] .'\')">Выйти из Facebook.com</a>';
  }
  echo "</div>";
}

?>
</div>

<script type="text/javascript">
  // variables from session to use in JS
  var session =  $.parseJSON($.ajax( { 
    url: "sessionlist.php", 
    global: false, 
    async:false,
    success: function(data) {
        return data;
    } } ).responseText);

  //var session = {"user_id": 0, "last_name": "Тестов", "first_name": "Тест"};
  
  function handleLogout(auth_type){
    if (auth_type == "vkcom") {
      VK.Auth.logout();
    } else if (auth_type == "mailru"){
      mailru.connect.logout();
    } else if (auth_type == "fb"){
      FB.logout(function(response) {
  // user is now logged out
      });
    }

    if (auth_type.length > 0){
      $.ajax({  url: "sessiondestroy.php" })
    }
  }
</script>
