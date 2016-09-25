function ClockDigitalDigit(){
    this.images = {"A": null, "B": null, "C": null, "D": null, "E": null, "F": null, "G": null}
    this.container = this.emptyDigit()
}

/*
   A
 B   C
   D
 E   F
   G
*/

ClockDigitalDigit.prototype.emptyDigit = function(){
   var div = $("<div/>")//.width("30px").height("40px")
   var table = $("<table/>").appendTo(div).attr("cellpadding", "0").attr("cellspacing", "0")
   var tr1 = $("<tr/>").appendTo(table)
   var tr2 = $("<tr/>").appendTo(table)
   var tr3 = $("<tr/>").appendTo(table)
   var tr4 = $("<tr/>").appendTo(table)
   var tr5 = $("<tr/>").appendTo(table)

   // B
   img = $("<img/>").css("verticalAlign", "top")
   td = $("<td/>").appendTo(tr1)
   td = $("<td/>").appendTo(tr1).append(img)//.width("20px").height("5px")
   td = $("<td/>").appendTo(tr1)
   this.images["B"] = img

   // A
   var img = $("<img/>")
   var td = $("<td/>").appendTo(tr2).append(img)//.height("20px").width("5px")
   this.images["A"] = img

   tr2.append($("<td/>"))

   // C
   img = $("<img/>")
   td = $("<td/>").appendTo(tr2).append(img)//.height("20px").width("5px")
   this.images["C"] = img

   // D
   img = $("<img/>").css("verticalAlign", "center")
   td = $("<td/>").appendTo(tr3)
   td = $("<td/>").appendTo(tr3).append(img)//.width("20px").height("5px")
   td = $("<td/>").appendTo(tr3)
   this.images["D"] = img

   // E
   img = $("<img/>")
   td = $("<td/>").appendTo(tr4).append(img)//.height("20px").width("5px")
   this.images["E"] = img

   tr4.append($("<td/>"))

   // F
   img = $("<img/>")
   td = $("<td/>").appendTo(tr4).append(img)//.height("20px").width("5px")
   this.images["F"] = img

   // G
   img = $("<img/>").css("verticalAlign", "bottom")
   td = $("<td/>").appendTo(tr5)
   td = $("<td/>").appendTo(tr5).append(img)//.width("20px").height("5px")
   td = $("<td/>").appendTo(tr5)
   this.images["G"] = img

   return div
}

ClockDigitalDigit.prototype.setA = function(light){
   this.images["A"].attr("src", light ? "/images/clockON90.png" : "/images/clockOFF90.png")
}

ClockDigitalDigit.prototype.setB = function(light){
   this.images["B"].attr("src", light ? "/images/clockON.png" : "/images/clockOFF.png")
}

ClockDigitalDigit.prototype.setC = function(light){
   this.images["C"].attr("src", light ? "/images/clockON90.png" : "/images/clockOFF90.png")
}

ClockDigitalDigit.prototype.setD = function(light){
   this.images["D"].attr("src", light ? "/images/clockON.png" : "/images/clockOFF.png")
}

ClockDigitalDigit.prototype.setE = function(light){
   this.images["E"].attr("src", light ? "/images/clockON90.png" : "/images/clockOFF90.png")
}

ClockDigitalDigit.prototype.setF = function(light){
   this.images["F"].attr("src", light ? "/images/clockON90.png" : "/images/clockOFF90.png")
}

ClockDigitalDigit.prototype.setG = function(light){
   this.images["G"].attr("src", light ? "/images/clockON.png" : "/images/clockOFF.png")
}


function ClockDigital2(){
  this.container = $("<table/>").append($("<tr/>"))
}

ClockDigital2.prototype.redraw = function (){
  this.container.empty()
  var d = new Date()
  var hh = d.getHours()
  var mm = d.getMinutes()
  var ss = d.getSeconds()
  this.container.append($("<td/>").append(this.commondigit(parseInt(hh/10)).css("float", "left").css("padding", "5px")))
  this.container.append($("<td/>").append(this.commondigit(hh%10).css("float", "left").css("padding", "5px")))
  this.container.append($("<td/>").append(this.commondigit(parseInt(mm/10)).css("float", "left").css("padding", "5px")))
  this.container.append($("<td/>").append(this.commondigit(mm%10).css("float", "left").css("padding", "5px")))
  this.container.append($("<td/>").append(this.commondigit(parseInt(ss/10)).css("float", "left").css("padding", "5px")))
  this.container.append($("<td/>").append(this.commondigit(ss%10).css("float", "left").css("padding", "5px")))
}

ClockDigital2.prototype.digit0 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(true)
   digit.setB(true)
   digit.setC(true)
   digit.setD(false)
   digit.setE(true)
   digit.setF(true)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.digit1 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(false)
   digit.setB(false)
   digit.setC(true)
   digit.setD(false)
   digit.setE(false)
   digit.setF(true)
   digit.setG(false)
   return digit.container
}

ClockDigital2.prototype.digit2 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(false)
   digit.setB(true)
   digit.setC(true)
   digit.setD(true)
   digit.setE(true)
   digit.setF(false)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.digit3 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(false)
   digit.setB(true)
   digit.setC(true)
   digit.setD(true)
   digit.setE(false)
   digit.setF(true)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.digit4 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(true)
   digit.setB(false)
   digit.setC(true)
   digit.setD(true)
   digit.setE(false)
   digit.setF(true)
   digit.setG(false)
   return digit.container
}

ClockDigital2.prototype.digit5 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(true)
   digit.setB(true)
   digit.setC(false)
   digit.setD(true)
   digit.setE(false)
   digit.setF(true)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.digit6 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(true)
   digit.setB(true)
   digit.setC(false)
   digit.setD(true)
   digit.setE(true)
   digit.setF(true)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.digit7 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(false)
   digit.setB(true)
   digit.setC(true)
   digit.setD(false)
   digit.setE(false)
   digit.setF(true)
   digit.setG(false)
   return digit.container
}

ClockDigital2.prototype.digit8 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(true)
   digit.setB(true)
   digit.setC(true)
   digit.setD(true)
   digit.setE(true)
   digit.setF(true)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.digit9 = function(){
   var digit = new ClockDigitalDigit()
   digit.setA(true)
   digit.setB(true)
   digit.setC(true)
   digit.setD(true)
   digit.setE(false)
   digit.setF(true)
   digit.setG(true)
   return digit.container
}

ClockDigital2.prototype.commondigit = function(d){
  switch(d){
    case 0: return this.digit0()
    case 1: return this.digit1()
    case 2: return this.digit2()
    case 3: return this.digit3()
    case 4: return this.digit4()
    case 5: return this.digit5()
    case 6: return this.digit6()
    case 7: return this.digit7()
    case 8: return this.digit8()
    case 9: return this.digit9()
  }
}

ClockDigital2.prototype.doClock = function(clock){
  clock.redraw()
  setTimeout(function() { clock.doClock(clock) }, 1000)
}

