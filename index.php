<?php
   require_once('header.php');
?>

<div id="content">
<div id="menu"/>
<?php
	$allarr = array(); 
	$maxWidth = 0; 
	$maxHeight = 0;

	$result = $db->query("select imagepath, alias, posx, posy, pagename from startpage where pagename <> 'index.php'");
	while($arr = $result->fetch()) {
  		array_push($allarr, $arr);
  		if ($maxWidth < $arr[2]) $maxWidth = $arr[2];
  		if ($maxHeight < $arr[3]) $maxHeight = $arr[3];
	}
?>

<script type="text/javascript">
  var renderdata = { "maxWidth" : <?php echo $maxWidth; ?>, "maxHeight" : <?php echo $maxHeight; ?>, "data" : <?php echo json_encode($allarr); ?> }
  new EJS({url:'/templates/index_menu.ejs'}).update ('menu', renderdata)
</script>

<script type="text/javascript">
function welcomeText(){
    	now = new Date();
    	hour = now.getHours();
    	userlogin = "<?php echo $_SESSION['first_name']; ?>"
    	if (hour > 22 || hour < 7) text = "Доброй ночи";
    	else if (hour >= 7 && hour < 12) text = "Доброе утро"; 
    	else if (hour >= 12 && hour < 18) text = "Добрый день";
    	else text = "Добрый вечер";
    	$('#header').text(text + ( userlogin.length > 0 ? ', ' + userlogin : '') + '!!!');	
}

$(document).ready(function(){
    	$(".image img").fadeTo("slow", 0.2); // Устанавливаем непрозрачность миниатюр до 60% при загрузке страницы.
    	$(".image img").hover(function(){
        	$(this).fadeTo("slow", 1.0); // При наведении курсора, непрозрачность становится 100%.
    	},function(){
        $(this).fadeTo("slow", 0.2); // Пр потере фокуса непрозрачность опять становится 60%.
    	});
    	$('#header').addGlow({ textColor: '#fff', haloColor: '#000', radius: 100 });
    	$('*').bind('glow:started', console.info);
    	$('*').bind('glow:canceled', console.info);
    	welcomeText();
});

</script>
</div>

</body>
</html>
