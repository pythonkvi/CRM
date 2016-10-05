<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>IValera.ru homepage</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" media="all" href="styles/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="styles/news.css" />
<link rel="stylesheet" type="text/css" media="all" href="styles/calendar.css" />
<link rel="stylesheet" type="text/css" media="all" href="styles/imgareaselect-default.css" />
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>-->
<script src="js/jquery.js"></script>
<script src="js/jquery.exif.js"></script>
<script src="js/jquery-ui-1.8.17.custom.js"></script>
<script src="js/jquery.scrollTo-1.4.2.js"></script>
<script src="js/jquery-glowing.js"></script>
<script src="js/jquery-color.js"></script>
<script src="js/jquery.json-2.4.js"></script>
<script src="js/date.format.js"></script>
<script src="js/category.js"></script>
<script type="text/javascript" src="js/jquery.imgareaselect.js"></script>
</head>
<body>

<form name="newsform" id="newsform" action="" method="post" enctype="multipart/form-data">
   <table>
   <tr><td><label>Категория:</label></td><td><input type="hidden" name="category_id" id="category_id" value="<?php echo htmlspecialchars($category_id); ?>"/><div id="category"></div></td></tr> 
   <tr><td><label>Заголовок:</label></td><td><input type="text" name="header" size="100" value="<?php echo htmlspecialchars($header); ?>"/></td></tr> 
   <tr><td><label>Текст:</label></td><td><textarea name="body" cols="80" rows="10"><?php echo htmlspecialchars($body); ?></textarea></td></tr>
   <tr><td><label>Дата новости:</label></td><td><input type="text" name="newsdate" id="newsdate" value="<?php echo htmlspecialchars($newsdate); ?>"/></td></tr>
   <tr><td><label>Автор:</label></td><td><input type="text" name="owner" size="100" value="<?php echo htmlspecialchars($owner); ?>"/></td></tr>
   <tr><td><label>Пароль:</label></td><td><input type="password" name="password"/></td></tr>   
   <tr><td colspan="2"><div id="attachcontainer"/></td></tr>
   <input type="hidden" name="id" value="<?php echo $id; ?>"/>
   <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>"/>
   <tr><td colspan="2"><input type="submit" name="save" value="Сохранить"/></td></tr>
</form>

<script type="text/javascript">
var cat = new CategoryLoader();
$(cat).bind("changed", function() { $("#category_id").val(cat.current) })
cat.fillCategories();
cat.container = $("#category")
<?php
if ($category_id > 0) {
	echo "cat.loadCategory(".$category_id.");\n";
} else {
	echo "cat.loadNext();\n";
}
?>
</script>

<div id="markForm" style="display: none" title="Mark on photo comment">
  <label for="name"></label><input type="text" maxlength="30" id="marked_name"/>
</div>

<script type="text/javascript">
   $( "#newsdate" ).datepicker({ dateFormat: "yy-mm-dd" });
   $("<input/>", {type: "button", value:"+"}).appendTo("#attachcontainer").click(function(){
       divele = $("<div/>").appendTo("#attachcontainer");
       $("<input/>", {type: "file", name:"attachment[]"}).appendTo(divele).change(function(){
	   var file = this.files[0]
	   var img = document.createElement("img");
	   img.className = "preview"
	   $(img).attr("exif", "true")
           $(img).imgAreaSelect({
             handles: true,
             autoHide: true,
             onSelectEnd: function (img, dimension) 
                          {
$( "#markForm" ).dialog({
  autoOpen: false,
  height: 100,
  width: 200,
  modal: true,
  buttons: {
    "Save": function() {
      dimension["text"] = $("#marked_name").val()
      var arr = $(img).data("marks")
      if (typeof (arr) == "undefined" || arr == null) arr = []
      arr.push(dimension)
      $(img).data("marks", arr)
      $(img).trigger("datamodified", dimension)
      console.log($(img).data("marks"));
      $("#marked_name").val("")
      $( this ).dialog( "close" );
    },
    Cancel: function() {
      $( this ).dialog( "close" );
    }
  }
});

$( "#markForm" ).dialog( "open" );
                          }
           });

	   $(img).bind("datamodified", function(arg, dimension){
             var itemdiv = $("<div/>") 
             itemdiv.append($("<span/>").html(dimension["text"]).addClass("mark_text"))
             itemdiv.append($("<a/>").html("X").addClass("mark_link").click(function(){ 
                               var arr = $(img).data("marks")
                               arr.splice(arr.indexOf(dimension), 1)
                               $(this).parent().remove();
                               divele.find("input[type='hidden']").first().val($.toJSON( $(img).data("marks")))
                               console.log(arr)
                             }));
             divele.append(itemdiv)
             divele.find("input[type='hidden']").first().val($.toJSON( $(img).data("marks")))
           });
	   divele.find(".preview").each(function(){ $(this).remove() })
	   divele.append(img)
           divele.append($("<input/>", {type: "hidden", name: "image_marks[]"}))

	   var dim = document.createElement("span")
           dim.className = "preview"
	   var dim2 = document.createElement("span")
           dim2.className = "preview"

	   img.onload = function() { $(dim).text(img.width + "x" + img.height + " [" + (file.size/1024.0/1024.0).toFixed(2) + " MiB]"); img.style.maxHeight = img.style.maxWidth = 100;  }

	   var reader = new FileReader();
    	   reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
    	   reader.readAsDataURL(file);

	   var fr = new FileReader();
	   fr.onloadend = function() {
           	// get EXIF data
           	var exif = EXIF.readFromBinaryFile(new BinaryFile(this.result));

           	// alert a value
           	$(dim2).text("," + exif.Model + "," + exif.DateTime);
           };

           fr.readAsBinaryString(file); // read the file

	   divele.append(dim)
	   divele.append(dim2)
       });
       $("<input/>", {type: "button", value:"-"}).appendTo(divele).click(function(){
	   $(this).parent().remove()
       });
     });
</script>

</body>
</html>
