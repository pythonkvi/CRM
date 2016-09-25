<?php
   require_once('header.php');
?>

<div id="content">
<ul>
<li><a href="tetris.php">Тетрис</a></li>
<li><a href="sudoku.php">Судоку</a></li>
<li><a href="kakuro.php">Какуро</a></li>
<li><a href="games/cbg1/ColorBallsWeb.html">Шарики</a></li>
<li><a href="games/cbg2/ColorBallsWeb.html">Шарики - 2</a></li>
<li><a href="games/ochko/OchkoWeb.html">Очко</a></li>
<li><a href="/viktorina/chat.html">Викторина</a></li>
</ul>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#header').addGlow({ textColor: '#fff', haloColor: '#000', radius: 100 });
});
</script>

</body>
</html>

