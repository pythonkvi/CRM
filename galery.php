<?php
   require_once('header.php');
?>

<div id="content">
<div id="bigwindow"></div>

<a href="demotivator.php">Демотиватор</a>

<?php
 echo '<div class="news_attach">';
      $result2 = $db->query("select l.link_text, l2.link_text, n1.newsdate from news_attachment n join link l on n.link2_id = l.id join link l2 on n.link_id = l2.id join news n1 on n.news_id = n1.id ".
(isset($_GET['date']) ? " where n1.newsdate='".$_GET['date']."'" : "").
" order by n1.newsdate desc");
      $currentDate = null;
      while($arr2 = $result2->fetch()) {
          if ($currentDate != $arr2[2]){
              echo "<p class='label_month'>".$arr2[2]."</p>";
              $currentDate = $arr2[2];
	  }
          echo '<img src="imageloader.php?image='.$arr2[0].'" class="news_image" alt="Нажмите для увеличения" onclick="show_big_image(this, \''.$arr2[1].'\')"></img>';
      }
      echo '</div>';
?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
    	$('#header').addGlow({ textColor: '#fff', haloColor: '#000', radius: 100 });
    	$('*').bind('glow:started', console.info);
    	$('*').bind('glow:canceled', console.info);

    $("#bigwindow").draggable();
    $("#bigwindow").click(function() { $("#bigwindow").hide('slow'); } );
	});

function show_big_image(element, image){
  var offset = $(element).offset()
  console.log(offset);
  offset['top'] += $(element).outerHeight()
  offset['left'] += $(element).outerWidth()
  $("#bigwindow").offset(offset);
  $("#bigwindow").html('<img src="imageloader.php?image=' + image + '&b=1" class="news_big_image" alt="Нажмите для закрытия">').show('slow');
  $.scrollTo($(element));
}

</script>

</body>
</html>

