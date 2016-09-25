function ClockDigital(){
}

ClockDigital.prototype.redraw = function (){
	now = new Date();
	numbers = [parseInt(now.getHours()/10), 
			   now.getHours()%10, 
			   parseInt(now.getMinutes()/10), 
			   now.getMinutes()%10, 
			   parseInt(now.getSeconds()/10), 
			   now.getSeconds()%10]
    container = document.createElement("div")
	for (i = 0; i < numbers.length; i++) {
		divele = document.createElement("div")
		divele.className = "animated_digit"
		imgele = document.createElement("img")
		imgele.src = "images/numbers.png"
		imgele.className = "clip pos-" + numbers[i]
		imgele.id = "animation" + i
		divele.appendChild(imgele)
		container.appendChild(divele)

		if (i % 2 == 1 && i < 5){
			delele = document.createElement("div")
			delele.className = "animated_digit";
			delele.innerHTML = "<strong>:</strong>";
			container.appendChild(delele)
		}
	}
	$("#clock_content").html(container)

	now.setTime(now.getTime() + 1000)
	nextnumbers = [parseInt(now.getHours()/10), 
			   now.getHours()%10, 
			   parseInt(now.getMinutes()/10), 
			   now.getMinutes()%10, 
			   parseInt(now.getSeconds()/10), 
			   now.getSeconds()%10]

	for (i = 0; i < numbers.length; i++) {
		if (nextnumbers[i] != numbers[i]) {
			$("#animation" + i).animate({"top": "-=22px", "opacity": "0"}, {duration: 1000 });	
		}
	}
}

ClockDigital.prototype.doClock = function(clock){
  clock.redraw()
  setTimeout(function() { clock.doClock(clock) }, 1000)
}
