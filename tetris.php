<?php
   require_once('header.php');
?>

<div id="content">
<script type="text/javascript">
function Tetris(){
	this.figure1 = [[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [0, 0, 0, 0], 
					 [1, 1, 1, 1] ], 
					[ 
	                 [1, 0, 0, 0], 
	                 [1, 0, 0, 0], 
					 [1, 0, 0, 0], 
					 [1, 0, 0, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [0, 0, 0, 0], 
					 [1, 1, 1, 1] ], 
					[ 
	                 [1, 0, 0, 0], 
	                 [1, 0, 0, 0], 
					 [1, 0, 0, 0], 
					 [1, 0, 0, 0] ]]
	this.figure2 = [[ 
	                 [0, 0, 0, 0], 
	                 [1, 1, 0, 0], 
					 [1, 0, 0, 0], 
					 [1, 0, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 1, 0], 
					 [0, 0, 1, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 1, 0, 0], 
					 [0, 1, 0, 0], 
					 [1, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 0, 0, 0], 
					 [1, 1, 1, 0] ]]
	this.figure3 = [[ 
	                 [0, 0, 0, 0], 
	                 [1, 1, 0, 0], 
					 [0, 1, 0, 0], 
					 [0, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [0, 0, 1, 0], 
					 [1, 1, 1, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [1, 0, 0, 0], 
					 [1, 0, 0, 0], 
					 [1, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 1, 0], 
					 [1, 0, 0, 0] ]]				 
	this.figure4 = [[ 
	                 [0, 0, 0, 0], 
	                 [0, 1, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 0, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [0, 1, 1, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 1, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 0, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [0, 1, 1, 0] ]]
	this.figure5 = [[ 
	                 [0, 0, 0, 0], 
	                 [1, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [0, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [0, 1, 1, 0], 
					 [1, 1, 0, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [1, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [0, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [0, 1, 1, 0], 
					 [1, 1, 0, 0] ]]
	this.figure6 = [[ 
	                 [0, 0, 0, 0], 
	                 [1, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 0, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 1, 0], 
					 [0, 1, 0, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 1, 0, 0], 
					 [1, 1, 0, 0], 
					 [0, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [0, 1, 0, 0], 
					 [1, 1, 1, 0] ]]
	this.figure7 = [[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 1, 0, 0] ],
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 1, 0, 0] ], 
					[ 
	                 [0, 0, 0, 0], 
	                 [0, 0, 0, 0], 
					 [1, 1, 0, 0], 
					 [1, 1, 0, 0] ]]				 
	this.board = []
	this.inGame = false
	this.initBoard = function(){
		for(var j = 0; j < 20; j++) this.board.push(this.emptyLine())
		this.inGame = true
	}
	this.emptyLine = function(){
		var temparray = []
		for(var i = 0; i < 10; i++) temparray.push(0)
		return temparray
	}
	this.fullLine = function(){
		var temparray = []
		for(var i = 0; i < 10; i++) temparray.push(1)
		return temparray
	}
	this.score = 0
	this.checkBoard = function(){
		var lines = 0
		var hasdeleterow = true
		while (hasdeleterow){
			hasdeleterow = false
			for (var i = 0; i < 20; i++) {
				if (this.board[i].indexOf(0) == -1) { 
					lines++
					this.board.splice(i, 1)
					this.board.push(this.emptyLine())
					hasdeleterow = true
					break
				}
			}
		}
		var linesscore = [0, 100, 200, 300, 500]
		return linesscore[lines]
	}
	this.currentFigure = null
	this.currentPosition = [0, 0]
	this.currentFigureRotate = 0
	this.canLeft = function(){
			var lastlinefigure = [0, 0, 0, 0]
			var allgood = true
			for (var offset = 0; offset < 4; offset++) {
				lastlinefigure[0] = this.currentFigure[this.currentFigureRotate][0][offset]
				lastlinefigure[1] = this.currentFigure[this.currentFigureRotate][1][offset]
				lastlinefigure[2] = this.currentFigure[this.currentFigureRotate][2][offset]
				lastlinefigure[3] = this.currentFigure[this.currentFigureRotate][3][offset]
				if (lastlinefigure.indexOf(1) == -1) continue

				console.log(lastlinefigure)
				console.log(this.currentPosition)
			
				var lastposition = []
				for (var i = 0; i < lastlinefigure.length; i++){
					if (lastlinefigure[i] == 1) lastposition.push(i)
				}
				for (var i = 0; i < lastposition.length; i++){
					if (this.currentPosition[0] + offset - 1 < 0 || 
						this.board[3 - lastposition[i] + this.currentPosition[1]][this.currentPosition[0] + offset - 1] == 1) allgood = false; 
				}
			}
			return allgood		
	}
	this.canRight = function(){
			var lastlinefigure = [0, 0, 0, 0]
			var allgood = true
			for (var offset = 3; offset >= 0; offset--) {
				lastlinefigure[0] = this.currentFigure[this.currentFigureRotate][0][offset]
				lastlinefigure[1] = this.currentFigure[this.currentFigureRotate][1][offset]
				lastlinefigure[2] = this.currentFigure[this.currentFigureRotate][2][offset]
				lastlinefigure[3] = this.currentFigure[this.currentFigureRotate][3][offset]
				if (lastlinefigure.indexOf(1) == -1) continue
			
				console.log(lastlinefigure)
				console.log(this.currentPosition)
				
				var lastposition = []
				for (var i = 0; i < lastlinefigure.length; i++){
					if (lastlinefigure[i] == 1) lastposition.push(i)
				}
				for (var i = 0; i < lastposition.length; i++){
					if (this.currentPosition[0] + offset + 1 >= 10 || 
						this.board[3 - lastposition[i] + this.currentPosition[1]][this.currentPosition[0] + offset + 1] == 1) allgood = false; 
				}
			}
			return allgood	
	}
	this.canDown = function() {
			var lastlinefigure = []
			var allgood = true
			for (var offset = 3; offset >= 0; offset--) {
				lastlinefigure = this.currentFigure[this.currentFigureRotate][offset]
				if (lastlinefigure.indexOf(1) == -1) continue
			
				console.log(lastlinefigure)
				console.log(this.currentPosition)
			
				var lastposition = []
				for (var i = 0; i < lastlinefigure.length; i++){
					if (lastlinefigure[i] == 1) lastposition.push(i)
				}
			
				for (var i = 0; i < lastposition.length; i++){
					if (this.currentPosition[1] + (3 - offset) - 1 < 0 || 
						this.board[this.currentPosition[1] + (3 - offset) - 1][lastposition[i] + this.currentPosition[0]] == 1) allgood = false; 
				}
			}
			return allgood
	}
	this.moveFigure = function(direction) {
		if (direction == "left") {
			var allgood = this.canLeft()
			if (allgood) this.currentPosition[0]--
			return allgood
		} else if (direction == "right") {
			var allgood = this.canRight()
			if (allgood) this.currentPosition[0]++
			return allgood
		} else if (direction == "down") {
			var allgood = this.canDown()
			if (allgood) this.currentPosition[1]--
			return allgood
		} else if (direction == "up") {
			var allgood = true
			var figure = this.currentFigure[( this.currentFigureRotate + 1 ) % 4]
			for(var i = 0; i < 4; i++)
				for(var j = 0; j < 4; j++)
					if (figure[i][j] == 1 && 
						( this.board[this.currentPosition[1] + 3 - i][this.currentPosition[0] + j] == 1 ||
							this.currentPosition[1] + 3 - i < 0 || 
							this.currentPosition[0] + j >= 10 || 
							this.currentPosition[0] + j < 0
						)){	
							allgood = false
					}
					
			if (allgood) this.currentFigureRotate = ( this.currentFigureRotate + 1 ) % 4
			return allgood
		}
	}
	this.nextFigure = function(){
		var figure = parseInt(Math.random() * 6) + 1
		switch(figure){
			case 1: this.currentFigure = this.figure1; break;
			case 2: this.currentFigure = this.figure2; break;
			case 3: this.currentFigure = this.figure3; break;
			case 4: this.currentFigure = this.figure4; break;
			case 5: this.currentFigure = this.figure5; break;
			case 6: this.currentFigure = this.figure6; break;
			case 7: this.currentFigure = this.figure7; break;
		}
		this.currentPosition = [2, 16]
		this.currentFigureRotate = 0
		this.inGame = this.canDown()
		if (!this.inGame) alert("Game over")
	}
	this.saveBoard = function(){
		var figure = this.currentFigure[this.currentFigureRotate]
		for(var i = 0; i < 4; i++)
			for(var j = 0; j < 4; j++)
				if (figure[i][j] == 1)
					this.board[this.currentPosition[1] + 3 - i][this.currentPosition[0] + j] = figure[i][j]		
	}
	this.drawWholeBoard = function(){
		var boardwithfigure = []
		for(var i = 0; i < 20; i++){
			boardwithfigure[i] = []
			for(var j = 0; j < 10; j++)
				boardwithfigure[i].push(this.board[i][j])
		}
		
		var figure = this.currentFigure[this.currentFigureRotate]
		for(var i = 0; i < 4; i++)
			for(var j = 0; j < 4; j++)
				if (figure[i][j] == 1)
					boardwithfigure[this.currentPosition[1] + 3 - i][this.currentPosition[0] + j] = figure[i][j]
		
		var cont = $("#tetris")
		cont.empty()
		cont.append($("<table/>"))
		for(var i = 19; i >= 0; i--) {
			for(var j = 0; j <= 10; j++) {
				if (j < 10)
					cont.append($("<td/>").css("background", boardwithfigure[i][j] == 1 ? "black" : "cyan").width("16px").height("16px"))
				//else 
				//	cont.append($("<td/>").text(i))
			}
			cont.append($("<tr/>"))
		}
		//var lowrow = $("<tr/>")
		//cont.append(lowrow)
		//for(var j = 0; j < 10; j++)
		//	lowrow.append($("<td/>").text(j))
			
		cont.append($("<p/>", {"id": "score"}).text("Score:" + this.score))
		//this.printArray(this.board)
	}
	this.processNext = function(){
		this.saveBoard()
		this.score += this.checkBoard()
		this.nextFigure()		
	}
	this.printArray = function(a){
		for(var i = 0; i < a.length; i++) {
			var str = ""
			for(var j = 0; j < a[i].length; j++) {
				str += "," + a[i][j]
			}
			console.log(str)
		}
	}
	this.pause = false
}

var tetris = new Tetris()
function timeOutEvent(){
	if (!tetris.pause) {
		if (!tetris.moveFigure("down")) tetris.processNext()
		tetris.drawWholeBoard()
	}
	if (tetris.inGame) setTimeout("timeOutEvent()", 3000)
}

$("body").keyup(function(event){
	if (tetris.inGame){
		if (event.keyCode == 37) {
			tetris.moveFigure("left") 
		}		
		if (event.keyCode == 38) {
			tetris.moveFigure("up")
		}	
		if (event.keyCode == 39) {
			tetris.moveFigure("right")
		}
		if (event.keyCode == 40) {
			if (!tetris.moveFigure("down")){
				tetris.processNext()
			}
		}
		if (event.keyCode == 32){
			while(tetris.moveFigure("down"));
			tetris.processNext()
		}
		tetris.drawWholeBoard()
	}
	if (event.keyCode == 78) { // new game
		tetris.initBoard()
		tetris.nextFigure()
		setTimeout("timeOutEvent()", 3000)
	}
	if (event.keyCode == 80) { // pause
		tetris.pause = !tetris.pause
		if (tetris.pause) $("#score").text("Paused")
	}
}) 
</script>
<div id="tetris">Press N to begin; Arrows - to action with figure; Space - drop figure; P - pause game</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
    	$('#header').addGlow({ textColor: '#fff', haloColor: '#000', radius: 100 });
    	$('*').bind('glow:started', console.info);
    	$('*').bind('glow:canceled', console.info);
	});
        $(document).keydown(function(e) {
  	    var k = e.keyCode;
            if(k >= 37 && k <= 40 || k == 32) {
                return false;
            }
        })
</script>

</body>
</html>

