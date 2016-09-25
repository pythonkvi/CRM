
function ClockAnalog(cnv){
    this.canvas = cnv
	this.canvas.width = this.canvas.height = 200
	this.radius = this.canvas.width / 2
}

ClockAnalog.prototype.clockStyle = "#000"
ClockAnalog.prototype.numberStyle = "#000"
ClockAnalog.prototype.arrowStyle = "#000"

ClockAnalog.prototype.redraw = function () {
	var context = this.canvas.getContext('2d');
	var r = this.radius

	context.clearRect(0, 0, this.canvas.width, this.canvas.height)
	context.strokeStyle = this.clockStyle
	
	context.beginPath()
	context.arc(this.canvas.width / 2, this.canvas.height / 2, r, 0, 2* Math.PI)
	context.stroke()

	context.beginPath()
	context.arc(this.canvas.width / 2, this.canvas.height / 2, 5, 0, 2* Math.PI)
	context.stroke()

	context.translate(r, r)
	
	for (var i = 0; i < 60; i++){
		context.lineWidth = 1 + (i % 5 == 0 ? 2 : 0)
		if (i % 5 == 0) {
			var digit = (i == 0 ? 12 : (i / 5))
			context.translate(-r, -r)
			context.translate(r - 7, r + 7)
			context.font = "20px Arial"
			context.strokeStyle = this.numberStyle
			context.strokeText(digit, 0.8 * r * Math.cos(2 * Math.PI * i / 60 - Math.PI / 2), 0.8 * r * Math.sin(2 * Math.PI * i / 60 - Math.PI / 2))
			context.translate(7, -7)
		}
		
		context.strokeStyle = this.clockStyle

		context.beginPath()
		context.moveTo(r * Math.cos(2 * Math.PI * i / 60 + Math.PI / 2), r * Math.sin(2 * Math.PI * i / 60 + Math.PI / 2))
		context.lineTo(0.9 * r * Math.cos(2 * Math.PI * i / 60 + Math.PI / 2), 0.9 * r * Math.sin(2 * Math.PI * i / 60 + Math.PI / 2))
		context.stroke()
	}

	context.translate(-r, -r)

	var d = new Date()
	var s = d.getSeconds()
	var m = d.getMinutes() + s / 60.0 
	var h = (d.getHours() % 12 + m / 60.0) * 5
	
	context.translate(r, r)
	context.strokeStyle = this.arrowStyle
	
	// hour
	context.lineWidth = 5;
	
	context.beginPath()
	context.moveTo(0.5 * r * Math.cos(2 * Math.PI * h / 60 - Math.PI / 2), 0.5 * r * Math.sin(2 * Math.PI * h / 60 - Math.PI / 2))
	context.lineTo(0, 0)
	context.stroke()
	
	// minutte
	context.lineWidth = 3;
	
	context.beginPath()
	context.moveTo(0.6 * r * Math.cos(2 * Math.PI * m / 60 - Math.PI / 2), 0.6 * r * Math.sin(2 * Math.PI * m / 60 - Math.PI / 2))
	context.lineTo(0, 0)
	context.stroke()
	
	// second
	context.lineWidth = 1;
	
	context.beginPath()
	context.moveTo(0.7 * r * Math.cos(2 * Math.PI * s / 60 - Math.PI / 2), 0.7 * r * Math.sin(2 * Math.PI * s / 60 - Math.PI / 2))
	context.lineTo(0, 0)
	context.stroke()	
	
	context.translate(-r, -r)
}


ClockAnalog.prototype.doClock = function(clock){
  clock.redraw()
  setTimeout(function() { clock.doClock(clock) }, 1000)
}
