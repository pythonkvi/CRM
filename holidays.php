<?php
   require_once('header.php');
?>

<div id="content">
<?php

	$result = $db->query("select a.hbody || case when cast(a.delta as integer) = 0 then '' else ' осталось ' || cast(a.delta as integer) || ' дней(дня,день)' end, case when cast(a.delta as integer) = 0 then 1 else 0 end from (select julianday(strftime('%Y', '".$holidays."') || strftime('-%m-%d', hdate))-julianday('".$holidays."') 'delta', hbody from holidays) a where a.delta < 3 and a.delta >= 0;");
	while($arr = $result->fetch()) {
	   echo '<div class="'.($arr[1] == 1 ? 'holiday_now' : 'newsbody').'">'.$arr[0].'</div>';
	}
?>
<script type="text/javascript">
$(document).ready(function(){
    $('#header').addGlow({ textColor: '#fff', haloColor: '#000', radius: 100 });
    $('*').bind('glow:started', console.info);
    $('*').bind('glow:canceled', console.info);
    VK.api("getUserSettings", {uid: userid}, function (data){
       console.log(data)
    });
    VK.api("friends.get", {uid: userid, fields: "uid, first_name, last_name, nickname, sex, bdate"}, function (data){ 
     if (data.response){
		now = new Date("<?php echo $holidays; ?>")
        now.setHours(0)
        now.setMinutes(0)
        now.setSeconds(0)
		$.each(data.response, function (k, item){
          if (typeof(item["bdate"]) != 'undefined' && item["bdate"] != null) {
            md = item["bdate"].split('.')
	    	birthday = new Date()
	    	birthday.setDate(1)
	    	if (md.length > 1) {
              	birthday.setMonth(parseInt(md[1] - 1))
	      		birthday.setDate(parseInt(md[0]))
	      		birthday.setHours(0)
              	birthday.setMinutes(0)
	      		birthday.setSeconds(0)
            }
	    	delta = (birthday.getTime() - now.getTime())/1000/86400;
            if (delta >= 0 && delta <= 10) {
	      		$("#content").append('<div class="newsbody">До дня рождения ' + item['last_name'] + ' ' + item['first_name'] + ' осталось ' + parseInt(delta) + ' дней,дня,день</div>')
            }
          }
        });
		console.log(data.response);
      } else console.log(data);
    });
});

</script>
</div>
</body>
</html>
