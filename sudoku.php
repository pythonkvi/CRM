<?php
   require_once('header.php');
?>

<link rel="stylesheet" type="text/css" media="all" href="styles/sudoku.css" />

<div id="content">
<script type="text/javascript">
function InputBox(){
	var root = $("<div/>")
	var self = this
	var tdparent = undefined
	for (var i = 1; i < 10; i++) {
		root.append($("<input/>", {"type":"checkbox", "id":"usch"+i} ).addClass("checkboxInput").data("val", i))
		    .append($("<label/>", {"for": "usch"+i}).text(i))
		if (i % 3 == 0) root.append($("<br/>"))
	}
	root.append($("<button/>").text("X").click(function(){ 
		self.hide()
	}))
	this.setValue = function(a){
		root.find("input[type='checkbox']").each(function(index){ 
			$(this).removeAttr("checked")
		})
		if (typeof a == "undefined") return;
		$.each(a, function(i){
			root.children("#usch"+this).attr("checked", true)
		})
	}
	this.getValue = function(){
		var a = []
		root.find("input:checked").each(function(index){
			a.push($(this).data("val"))
		})
		return a
	}
	this.show = function(e){
		root.offset({"left": $(e).offset().left, "top" : $(e).offset().top})
			.css({"position": "absolute", "display": "block", "background": "#fff"})
			.appendTo($("body"))
		this.tdparent = e
	}
	this.hide = function(){
		var arr = this.getValue()
		$(this.tdparent).data("val", arr)
		if (arr.length == 0) {
			$(this.tdparent).text("")
		} else if (arr.length == 1){
			$(this.tdparent).text(arr[0]).css("font-size", "100%")
			$(this.tdparent).trigger('single')
		} else {
			$(this.tdparent).text(arr.join(' ')).css("font-size", "50%")
		}
		root.remove()
	}
}

function Sudoku(){
	this.matrix = [
				   [1,2,3, 4,5,6, 7,8,9],
				   [4,5,6, 7,8,9, 1,2,3],
				   [7,8,9, 1,2,3, 4,5,6],
				   
				   [2,3,1, 5,6,4, 8,9,7],
				   [5,6,4, 8,9,7, 2,3,1],
				   [8,9,7, 2,3,1, 5,6,4],
				   
				   [3,1,2, 6,4,5, 9,7,8],
				   [6,4,5, 9,7,8, 3,1,2],
				   [9,7,8, 3,1,2, 6,4,5]
				   ]
	
	this.sudoku = []
	this.sudokuuser = []
	this.rotateColumn = function(m){
		var gr = [[0,1,2], [3,4,5], [6,7,8]]
		var c0 = parseInt(Math.random()*3)%3
		var c01 = gr[c0][parseInt(Math.random()*3)%3]
		var c02 = gr[c0][(c01 + 1)%3]
		for(var i = 0; i < 9; i++){
			var x = m[i][c01]
			m[i][c01] = m[i][c02]
			m[i][c02] = x
		}
	}
	this.rotateRow = function(m){
		var gr = [[0,1,2], [3,4,5], [6,7,8]]
		var c0 = parseInt(Math.random()*3)%3
		var c01 = gr[c0][parseInt(Math.random()*3)%3]
		var c02 = gr[c0][(c01 + 1)%3]
		for(var i = 0; i < 9; i++){
			var x = m[c01][i]
			m[c01][i] = m[c02][i]
			m[c02][i] = x
		}
	}
	this.rotateBigColumn = function(m){
		var gr = [[0,1,2], [3,4,5], [6,7,8]]
		var c0 = parseInt(Math.random()*3)%3
		var c1 = (c0 + 1)%3
		for(var i = 0; i < 9; i++){
			for(var j = 0; j < 3; j++){
				var x = m[i][gr[c0][j]]
				m[i][gr[c0][j]] = m[i][gr[c1][j]]
				m[i][gr[c1][j]] = x
			}
		}
	}
	this.rotateBigRow = function(m){
		var gr = [[0,1,2], [3,4,5], [6,7,8]]
		var c0 = parseInt(Math.random()*3)%3
		var c1 = (c0 + 1)%3
		for(var i = 0; i < 9; i++){
			for(var j = 0; j < 3; j++){
				var x = m[gr[c0][j]][i]
				m[gr[c0][j]][i] = m[gr[c1][j]][i]
				m[gr[c1][j]][i] = x
			}
		}
	}
	this.performRotate = function(m){
		var f = [this.rotateColumn, this.rotateRow, this.rotateBigColumn, this.rotateBigRow]
		var count = parseInt(Math.random()*5) + 5
		for(var i = 0; i<count; i++){
			f[parseInt(Math.random()*4)%4](m);
		}
	}
	this.printMatrix = function(m){
		var div = $("<div/>")
		var table = $("<table/>").addClass("sudoku")
		var mainObj = this
		
		div.append($("<button/>").text("Check").click(function(){
			if (mainObj.equalMatrix(mainObj.sudoku, mainObj.sudokuuser)){
				alert("Yes");
			} else {
				alert("No");
			}
		}))
		
		div.append($("<button/>").text("New game").click(function(){
			var level = prompt("What level [1-8]?", 5)
			if (level < 1 || level > 8) return
			$(this).parent().empty()
			mainObj.sudoku = mainObj.copyMatrix(mainObj.matrix)
			mainObj.performRotate(mainObj.sudoku)
			mainObj.sudokuuser = mainObj.copyMatrix(mainObj.sudoku)
			mainObj.hide(mainObj.sudokuuser, level)
			mainObj.printMatrix(mainObj.sudokuuser)
		}))
		
		for(var i = 0; i < 9; i++){
			var tr = $("<tr/>").appendTo(table)
			for(var j = 0; j < 9; j++){
				tr.append($("<td/>")
					.text(m[i][j] == 0 ? " " : m[i][j])
					.addClass(m[i][j] == 0 ? "user" : "entered")
					.data("pos", [i, j])
					.click(function(){
						if ($(this).hasClass("entered")) return;

						var ib = new InputBox()
						ib.show(this)
						ib.setValue($(this).data("val"))
					})
					.bind('single', function(){
						console.log("set " + $(this).data("pos")[0] + "," + $(this).data("pos")[1] + " val " + $(this).data("val")[0])
						mainObj.sudokuuser[$(this).data("pos")[0]][$(this).data("pos")[1]] = $(this).data("val")[0]
					}))				
			}
		}
		div.append(table)
		$("body").append(div)
	}
	this.hide = function(m, level){
		var bounds = [ [0, 3, 0, 3], [0, 3, 3, 6], [0, 3, 6, 9],
					   [3, 6, 0, 3], [3, 6, 3, 6], [3, 6, 6, 9],
					   [6, 9, 0, 3], [6, 9, 3, 6], [6, 9, 6, 9]]
		for(var i = 0; i<9; i++){
			for (var j = 9 - level; j > 0; j--) {
				var x = parseInt(Math.random()*3)%3
				var y = parseInt(Math.random()*3)%3
				m[x + bounds[i][2]][y + bounds[i][0]] = 0
			}
		}
	}
	this.copyMatrix = function(m0){
		var m1 = []
		for(var i = 0; i < 9; i++)
		{
			var arr = []
			for(var j = 0; j < 9; j++)
			{
				arr.push(m0[i][j])
			}
			m1.push(arr)
		}
		return m1
	}
	this.equalMatrix = function(m0, m1){
		var eq = true
		for(var i = 0; i < 9; i++){
			for(var j = 0; j < 9; j++)
			{
				eq = eq && (m0[i][j] == m1[i][j])
				if (m0[i][j] != m1[i][j]) console.log(m0[i][j] + " vs " + m1[i][j] + " at " + i + "," + j)
			}
		}
		return eq
	}
}
var m = new Sudoku()
m.sudoku = m.copyMatrix(m.matrix)
m.performRotate(m.sudoku)
m.sudokuuser = m.copyMatrix(m.sudoku)
m.hide(m.sudokuuser, 5)
m.printMatrix(m.sudokuuser)
</script>
</div>
</body>
</html>

