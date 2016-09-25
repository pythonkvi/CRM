<?php
   require_once('header.php');
?>

<link rel="stylesheet" type="text/css" media="all" href="styles/imgareaselect-default.css" />
<script type="text/javascript" src="js/jquery.imgareaselect.js"></script>
<script src="js/jquery.rotate-3.js"></script>
<script src="js/category.js"></script>
<script type="text/javascript">
  var categ = new CategoryLoader();
  categ.fillCategories();
  categ.prefix = "<?php echo $pageWithParams; ?>";
  $("<div/>").html("<u>Категории:</u>" + categ.buildTree()).appendTo($("#authcontainer"))
  $("#categorybar").treeview({
                        persist: "location",
                        collapsed: true,
                        unique: true
                });

</script>

<div id="content">
<div id="bigwindow"></div>
<div id="commentwindow">
  <input type="hidden" id="commentformid"></input>
  <input type="hidden" id="commentformlevel"></input>
  <textarea id="commentformtext"></textarea>
  <button id="commentformsubmit" class="button_blue">Отправить</button>
  <button id="commentformclose" class="button_blue">Закрыть</button>
</div>

<!-- searching -->
<form method="get" id="search_form" action="<?php echo $pageWithParams; ?>">
  <input type="text" class="search_string" name="q" title="поиск по новостям" value="<?php echo $_GET['q']; ?>">
  <input type="submit" class="search_button" title="поиск по новостям" value="">
  <script type="text/javascript">
    $("#search_form").find(':first-child').keydown(function(e) { if (e.keyCode == 13) { $("#search_form").submit(); } });
  </script>
</form>

<?php
  $pageSingle = isset($_GET['id']);

  if ($pageSingle) {
    $db->exec("insert into news_visit (news_id, visit_date, visit_ip) values ('".$_GET['id']."', DATETIME('now'), '".$_SERVER['REMOTE_ADDR']."')") || die($db->errorInfo());
  }

  $result = $db->query("select id, header, body, newsdate, owner, category_id, exists ( select 1 from news a where a.parent_id = '".(int)$_GET['id']."' ) cp from news where parent_id is null ".
($pageSingle ? " and id='".(int)$_GET['id']."' " : '').
(isset($_GET['date']) ? " and newsdate='".$_GET['date']."' " : '').
(isset($_GET['category_id']) ? " and category_id='".(int)$_GET['category_id']."' " : '').
(isset($_GET['q']) ? " and body like '%".mb_ereg_replace("'", "''", $_GET['q'])."%'" : '').
" order by id desc ".(isset($_GET['all']) ? '' : 'limit 10'));

  if ($pageSingle){
    if($arr = $result->fetch()) {
      $pageN = $pageUrl.'?id='.$arr['id'];
      echo '<div class="newsdate">'.$arr["owner"]."<br/>".$arr["newsdate"]."<br/>".'<button class="seems_link button_blue" onclick="show_comment_window(this,'.$arr["id"].',1)">добавить</button></div>';
      echo '<div class="newsheader">'.$arr["header"].'</div>';
      echo '<div class="newsbody">'.$arr["body"].'</div>';
      echo '<script type="text/javascript">document.title = "IValera.ru - '.$arr["header"].'";</script>'; 
 
      echo '<div class="news_attach">';
      $result2 = $db->query("select l.link_text, l2.link_text, l.id, l2.id from news_attachment n join link l on n.link2_id = l.id join link l2 on n.link_id = l2.id where n.news_id ='".$arr["id"]."' order by n.id");
      while($arr2 = $result2->fetch()) {
        echo '<img src="imageloader.php?image='.$arr2[0].'" class="news_image" alt="Нажмите для увеличения" onclick="show_big_image(this, \''.$arr2[1].'\', \''.$arr2[3].'\')"></img>';
      }
      echo '</div>';
      
      echo '<div class="news_comments" id="commentblock'.$arr["id"].'">';
      echo '<button class="seems_link button_blue" onclick="show_comments(this,'.$arr["id"].')">показать комментарии '.($arr["cp"] == 1 ? '*' : '').'</button></div>';
      echo '<div id="social"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$pageN.'" data-text="'.$arr['header'].'" data-via="pythonkvi" data-lang="ru">Твитнуть</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
      echo '<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript">
  VK.init({apiId: 2809026, onlyWidgets: true});
</script>

<!-- Put this div tag to the place, where the Like block will be -->
<div id="vk_like"></div>
<script type="text/javascript">
VK.Widgets.Like("vk_like", {type: "button", pageTitle: "'.$arr['header'].'", pageUrl: "'.$pageN.'"} );
</script>';
      echo '<a target="_blank" class="mrc__plugin_uber_like_button"  data-mrc-config="{\'type\' : \'button\', \'caption-mm\' : \'2\', \'caption-ok\' : \'1\', \'counter\' : \'true\', \'text\' : \'true\', \'width\' : \'100%\'}" href="http://connect.mail.ru/share?url='.urlencode($pageN).'&amp;title='.urlencode($arr['header']).'">Нравится</a><script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>
';
      echo '<g:plusone annotation="inline" href="'.$pageN.'"></g:plusone>';
      echo '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=312196692172071";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>';
      echo '<div class="fb-like" data-href="'.$pageN.'" data-send="true" data-width="450" data-show-faces="true"></div></div>';
    }
  } else { ?>

	<div id="newslist">
          <script type="text/javascript">
            (
              function () 
              {
		var url = parseURL(window.location);
                $.ajax({ url: "/api/newslist.php?apikey=21101983&limit=10" 
							+ ( typeof(url.params["date"]) != "undefined" ? "&date=" + url.params["date"] : "" ) 
							+ ( typeof(url.params["category_id"]) != "undefined" ? "&category_id=" + url.params["category_id"] : "" ) 
							+ ( typeof(url.params["q"]) != "undefined" ? "&q=" + url.params["q"] : "") }).done (
                                         function (newsfromdb) {
						var jsonData = $.parseJSON(newsfromdb)
						console.log(jsonData)
						for (var i = 0; i < jsonData.length; ++i) lastNewsID = jsonData[i].id;
						if (jsonData.length > 0) {
                                                	new EJS({url:'/templates/news_list.ejs'}).update ('newslist', { news : jsonData, category_id : ( typeof(url.params["category_id"]) != "undefined" ? url.params["category_id"] : null) })
						}
                                         }
                                 ); 
              }
            )();
          </script>
        </div>

<?php } ?>

<div>
<script type="text/javascript">
$(document).ready(function(){
    $('#header').addGlow({ textColor: '#fff', haloColor: '#000', radius: 100 });
    $('*').bind('glow:started', console.info);
    $('*').bind('glow:canceled', console.info);

    $("#bigwindow").draggable();
    $("#commentwindow").draggable();

    $("#commentformclose").click(function(e) { $("#commentwindow").hide('slow'); } );

    $("#commentformsubmit").click(function(e) {
        var textlen = $("#commentformtext").val().length
	if (textlen == 0) {
	  alert("Ничего не указано");
	  return;
	}
	if (textlen > 160) {
          alert("Слишком длинный текст");
          return;
        }
	if(typeof(session) == 'undefined' || session == null || typeof(session["user_id"]) == 'undefined' || session["user_id"] == null) {
	  alert("Добавлять комментарии могут только авторизованные пользователи");
	  return;
	}
	
	$.ajax({
	  url: "newscommentadd.php",
	  data: { "news_id": $("#commentformid").text(), "news_text": $("#commentformtext").val(), "owner" : session["last_name"] + " " + session["first_name"] },
	  type: "POST",
	  success: function (dataString){
	      data = jQuery.parseJSON(dataString)
	      level = $("#commentformlevel").text()
	      $("#commentwindow").hide('slow');
	      $("#commentblock" + data["parent_id"]).append(
'<div class="news_comments" id="commentblock' + data["news_id"] + '">' +
'<div class="newsdate news_comment_level' + level + '">' + data["owner"] + '<br/>' + new Date(data["news_date"]*1000).format('yyyy-mm-dd') + '</div>'+
'<div class="newsheader">комментарий</div>' +
'<div class="newsbody">' + data["news_text"] + '</div>' +
'</div>'
);
	      $.scrollTo($("#commentblock" + data["parent_id"]));
	  }
       })
    });

   lastNewsID = -1
   blockLastNewsID = false
   $(window).scroll(function(){
     if($(window).scrollTop() >= $(document).height() - $(window).height() && !blockLastNewsID){
		blockLastNewsID = true
                var url = parseURL(window.location);
		$.ajax({ url: "/api/newslist.php?apikey=21101983&limit=1&lastNewsID=" + lastNewsID   
                                                        + ( typeof(url.params["date"]) != "undefined" ? "&date=" + url.params["date"] : "" ) 
                                                        + ( typeof(url.params["category_id"]) != "undefined" ? "&category_id=" + url.params["category_id"] : "" ) 
                                                        + ( typeof(url.params["q"]) != "undefined" ? "&q=" + url.params["q"] : "") }).done (
                                         function (newsfromdb) {
                                                var jsonData = $.parseJSON(newsfromdb)
                                                for (var i = 0; i < jsonData.length; ++i) lastNewsID = jsonData[i].id;
						if (jsonData.length > 0) {
                                  	              var html = new EJS({url:'/templates/news_list.ejs'}).render ({ news : jsonData, category_id : ( typeof(url.params["category_id"]) != "undefined" ? url.params["category_id"] : null) })
							$("#newslist").append(html)
						}
						blockLastNewsID = false
                                         }
                                 ); 
     }
   });

});

function show_big_image(element, image, link_id){
  $("#bigwindow").empty()

  var img = new Image()
  img.src = "/imageloader.php?image=" + image + "&b=1"
  img.onload = function() { 
	var offset = $(element).offset()
	$("#bigwindow").offset({top: offset.top + $(element).height(), left: offset.left +  $(element).width()});
  }

  var cf = 0
  var rb = $("<button/>").text("Повернуть вправо").addClass("button_blue").click(function(e){
      console.log("rotating image")
      cf = ++cf % 4

      $(img).rotate({ animateTo: cf * 90})
  })
  var lb = $("<button/>").text("Повернуть влево").addClass("button_blue").click(function(e){
      console.log("rotating image")
      cf = --cf % 4

      $(img).rotate({ animateTo: cf * 90})
  })


  var cb = $("<button/>").text("Закрыть").addClass("button_blue").click(function(e){
      $("#bigwindow").hide('slow');      
  })

  var qrimg = $("<img/>").attr("src", "http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=" + escape(img.src))
  var markscontainer = $("<div/>")
  $.ajax({url: "/api/imagemark.php", 
          data: {"link_id": link_id, "operation": "list", "apikey": "21101983"},
          type: "POST",
          success: function (dataString){
            dataArray = jQuery.parseJSON(dataString)
            $.each(dataArray, function(k,mark){
              console.log(mark)
              var ias = $(img).imgAreaSelect({ instance: true, handles: true })
              markscontainer.append($("<span/>").html(mark.mark).addClass("mark_text").hover(function(){
                ias.setSelection(mark.x1, mark.y1, mark.x2, mark.y2, true);
                ias.setOptions({ show: true });
                ias.update();
                console.log("show mark on photo")
              }, function() {
                ias.cancelSelection()
                ias.update() 
                console.log("hide mark on photo")
              })).append($("<a/>").text("X").addClass("mark_link").css({display: "none"}))
          })
        }
  })
 
  var table = $("<table/>").append($("<tr/>").append($("<td/>").append(img).append(qrimg)))
                           .append($("<tr/>").append($("<td/>").append(lb).append(rb).append(cb).append(markscontainer)))
  $("#bigwindow").append(table).show('slow');

  $.scrollTo($(element));
}

function show_comment_window(element, news_id, level){
  var offset = $(element).offset()
  console.log(offset);
  offset['top'] += $(element).outerHeight()
  offset['left'] += $(element).outerWidth()
  $("#commentwindow").offset(offset);
  $("#commentformid").text(news_id);
  $("#commentformlevel").text(level);
  $("#commentwindow").show('slow');
  $.scrollTo($(element));
}

function show_comments(element, news_id){
  $.ajax({
    url: "newscommentlist.php",
	data: { "news_id": news_id },
	type: "POST",
	success: function (dataString){
	dataArray = jQuery.parseJSON(dataString)
	  $("#commentblock" + news_id).empty()
	  $.each(dataArray, function (k, data){
	      level = data['level']
		
		$("#commentblock" + data["parent_id"]).append(
'<div class="news_comments" id="commentblock' + data["id"] + '">' +
'<div class="newsdate news_comment_level' + level +'">'+ data["owner"] + "<br/>" + data["newsdate"] + 
'<button class="seems_link button_blue" onclick="show_comment_window(this,' + data["id"] + ','+ parseInt(level+1) +')">добавить</button></div>'+
'<div class="newsheader">комментарий</div>' +
'<div class="newsbody">' + data["body"] + '</div>' +
'</div>'
);
		    
		  })
	      $.scrollTo($("#commentblock" + news_id));
	  }
       })
}
</script>
</div>
</body>
</html>
