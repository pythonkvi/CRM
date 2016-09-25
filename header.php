<?php 
session_start(); 
error_reporting(E_ERROR | E_PARSE);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>IValera.ru homepage</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" media="all" href="styles/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="styles/news.css" />
<link rel="stylesheet" type="text/css" media="all" href="styles/calendar.css" />
<link rel="stylesheet" type="text/css" media="all" href="styles/jquery.treeview.css" />
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>-->

<script type="text/javascript">
(function(__global){
    if (!__global.console || (__global.console && !__global.console.log)) {
         __global.console = {
         log: (__global.opera && __global.opera.postError)
             ? __global.opera.postError
         : function(){ }
        }
    }
})(this);
</script>

<!-- common used includes -->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.8.17.custom.js"></script>
<script src="js/jquery.scrollTo-1.4.2.js"></script>
<script src="js/jquery-glowing.js"></script>
<script src="js/jquery-color.js"></script>
<script src="js/date.format.js"></script>
<script src="js/clock.js"></script>
<script src="js/clock_analog.js"></script>
<script src="js/clock_digital.js"></script>
<script src="js/url.js"></script>
<script src="js/calendar.js"></script>
<script src="js/ejs.js"></script>
<script src="js/ejs.view.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/jquery-treeview.js"></script>

<!-- social -->
<script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'ru'}
</script>

</head>
<body>
<?php
  	require_once('login.php');
  
  	date_default_timezone_set('Asia/Dubai');
  	$now = new DateTime();
	$pageUrl = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$pageWithParams =  "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$currentName = basename($_SERVER['PHP_SELF']);
	$db = new PDO('sqlite:site.db');
?>

<!-- add clock -->
<script type="text/javascript">
	if ($.browser.msie && $.browser.version < 9) {
        	var clock0 = new ClockDigital()
		$("#authcontainer").append($("<div/>", {"id": "clock_content"}))
		clock0.doClock(clock0)
	} else {
		//var canvas4clock = document.createElement("canvas")
        	//$("#authcontainer").append($("<div/>", {"id": "clock_content"}).append($(canvas4clock)))
	        //var clock1 = new ClockAnalog(canvas4clock)
		//$("#clock_content").width($(canvas4clock).width())
		//$("#clock_content").height($(canvas4clock).height())
        	//clock1.doClock(clock1)
		var clock1 = new ClockDigital2()
		$("#authcontainer").append($("<div/>", {"id": "clock_content"}).append(clock1.container))
		clock1.doClock(clock1)
	}
</script>
<!-- add calendar -->

<?php
	$holidays = $now->format('Y-m-d');
	if (isset($_GET['date'])){
		$holidays = $_GET['date'];
	}
	$holidaysDate = new DateTime($holidays);
?>

<script type="text/javascript">
	var calendar = new Calendar(<?php echo $holidaysDate->format('m') ?>, <?php echo $holidaysDate->format('Y') ?>)
	calendar.prefix = "<?php echo $pageWithParams; ?>"
	calendar.marked = "<?php echo $holidays; ?>"
	$("#authcontainer").append($("<p/>", {"id" : "month_container_label"}).addClass("label_month"))
        $("#authcontainer").append($("<div/>", {"id" : "month_container"}))
	$(calendar).bind("changedate", function(){
		$("#month_container").empty()
		$("#month_container_label").empty()
		$("#month_container_label").html(calendar.toSelectString())
		$("#month_container").append(calendar.drawMonth())
	})
	$(calendar).trigger("changedate")
</script>

<div id="navigation" class="small_row">
<?php

	if (!strstr($_SERVER['PHP_SELF'], "index.php")) {
		$result = $db->query("select alias, pagename, imagepath from startpage where pagename <> '".basename($_SERVER['PHP_SELF'])."' order by alias");
		while($arr = $result->fetch()) {
			echo '<div class="small_cell"><a href="'.$arr[1].'"><div class="small_image"><img class="small_image" src="'.$arr[2].'"/></div><div class="small_imagetext">'.$arr[0].'</div></a></div>';
		}		
	}

?>
</div>

<!-- weather -->
<?php
    require_once('geo/geo.php');
    $o1 = array(); // опции. необзятательно.
    $o1['charset'] = 'utf-8'; // нужно указать требуемую кодировку, если она отличается от windows-1251

    $geo = new Geo($o1); // запускаем класс
    $result = $db->query("select id, name from gismeteo_city where name='".$geo->get_value('city')."'");
    $meteocity = "4368%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0";
    while($arr = $result->fetch()) {
        $meteocity = array($arr['id'], urlencode($arr['name']));
    } 
?>

<!-- Gismeteo informer START -->
<link rel="stylesheet" type="text/css" href="http://www.gismeteo.ru/static/css/informer2/gs_informerClient.min.css">
<div id="gsInformerID-rfD5w68LivS5Sy" class="gsInformer" style="width:210px;height:105px">
  <div class="gsIContent">
   <div class="gsLinks">
     <table>
       <tr>
         <td>
           <div class="leftCol">
             <a href="http://www.gismeteo.ru/" target="_blank">
               <img alt="Gismeteo" title="Gismeteo" src="http://www.gismeteo.ru/static/images/informer2/logo-mini2.png" align="absmiddle" border="0" />
               <span>Gismeteo</span>
             </a>
           </div>
           <div class="rightCol">
             <a href="http://www.gismeteo.ru/city/weekly/<?php echo $meteocity[0]; ?>/" target="_blank">Прогноз на 2 недели</a>
           </div>
           </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<script async="true" src="http://www.gismeteo.ru/ajax/getInformer/?hash=rfD5w68LivS5Sy" type="text/javascript"></script>
<!-- Gismeteo informer END -->

<script type="text/javascript">
  $("#gsInformerID-rfD5w68LivS5Sy").css("marginTop", "10px").appendTo($("#authcontainer"))
</script>
	
<?php

	$result = $db->query("select alias from startpage where pagename = '".$currentName."'");
	while($arr = $result->fetch()) {
		echo '<div id="header">'.$arr[0].'</div>';
		echo '<script type="text/javascript">document.title = ("IValera.ru - '.$arr[0].'");</script>';
	}

?>

