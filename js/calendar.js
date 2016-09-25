function Calendar(month, year) {
	this.prefix = ""
	if (typeof(month) == "undefined" || typeof(year) == "undefined") {
		var dt = new Date()
		this.month = typeof(month) == "undefined" ? dt.getMonth() + 1 : month
		this.year = dt.getYear() + 1900
	} else {
		this.month = month;
		this.year = year;
	}
	this.toString = function () {
		var month1 = ["", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]
		return  month1[this.month] + " " + this.year + " года" 
	}
	this.toSelectString = function () {
		var month1 = ["", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]
		var cont = $("<span/>")
		var savedcalendar = this
		cont.append($("<span/>", {"id":"calendar_month"}).text(month1[this.month] + " ").hover(function(){$(this).css("cursor", "hand")}).click(function(){
			if (savedcalendar.editmode1) return;
			savedcalendar.editmode1 = true
			var select1 = $("<select/>")
			for(var j=1;j<=12;j++){
				select1.append($("<option/>", {"value": j}).text(month1[j]))
			} 
			select1.val(savedcalendar.month)
			select1.change(function(){
				savedcalendar.month = select1.val()
				savedcalendar.editmode1 = false
				savedcalendar.editmode2 = false
				$(savedcalendar).trigger("changedate")
				select1.remove()
			})
			$(this).append(select1)
		}))
		cont.append($("<span/>", {"id":"calendar_year"}).text(this.year).hover(function(){$(this).css("cursor", "hand")}).click(function(){
			if (savedcalendar.editmode2) return;
                        savedcalendar.editmode2 = true
			var select2 = $("<select/>")
			for(var j=1900;j<=2020;j++){
				select2.append($("<option/>", {"value": j}).text(j))
			} 
			select2.val(savedcalendar.year)
			select2.change(function(){
				savedcalendar.year = select2.val()
				savedcalendar.editmode1 = false
				savedcalendar.editmode2 = false
				$(savedcalendar).trigger("changedate")
				select2.remove()
			})
			$(this).append(select2)
		}))
		cont.append($("<span/>").text(" года"))
		return cont 
	}
	this.drawMonth = function (mon) {
		var wd = ["пн","вт","ср","чт","пт","сб","вс"]
		var wdclass = ["cell_usual", "cell_usual", "cell_usual", "cell_usual", "cell_usual", "cell_saturday", "cell_sunday"]
		var daycount = [0, 31, (this.year % 4== 0 ? 1 : 0) + 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
		
		if (typeof(mon) != "undefined") {
			this.month = mon;
		}
		
		var cont = $("<div/>", {'id':'month_container'})
		var table = $("<table/>").appendTo(cont)
		var thr = $("<tr/>").appendTo($("<thead/>").appendTo(table))
		for (var i=0;i<7;i++) {
			$("<th/>").appendTo(thr).text(wd[i]).addClass(wdclass[i])
		}
		var startwd = new Date(this.year, this.month-1, 1).getDay()
		startwd = (startwd + 6) % 7
		console.log(this.month + " has " + startwd)
		
		var currentwd = startwd
		var currentRow = $("<tr/>").appendTo(table)
		for(var i=0; i<startwd && startwd > 0;i++) {
			$("<td/>").appendTo(currentRow).text(" ").addClass(wdclass[i])
			currentwd = ( currentwd + 1) % 7 
		}
		currentwd = startwd
		for (var i=1; i <= daycount[this.month]; i++){
                        var urlParts = null
			var url = null
                        if (typeof(this.prefix) != "undefined" && this.prefix.length > 0)
			{
			    urlParts = parseURL(this.prefix)
			    urlParts.params["date"] = this.year + "-" + ("0" + this.month).slice(-2) + "-" + ("0"+i).slice(-2)
			    url = formURL(urlParts)
			}                

			$("<td/>").addClass(wdclass[currentwd]).addClass(this.isMarked(i) ? "cell_today" : null).appendTo(currentRow).append($("<a/>", {"href": url }).text(i))
			currentwd = ( currentwd + 1) % 7
			if (currentwd == 0) {
				currentRow = $("<tr/>").appendTo(table)
			}
		}
		for(var i=currentwd; i<7;i++) {
			$("<td/>").appendTo(currentRow).text(" ").addClass(wdclass[currentwd])
			currentwd = ( currentwd + 1) % 7 
		}
		return cont
	}
	this.isMarked = function (arg_day) {
		var checkDate = new Date(this.marked)
		return checkDate.getYear()+1900 == this.year && checkDate.getMonth()+1 == this.month && checkDate.getDate() == arg_day;
	}
	this.drawYear = function(){
		var cont = $("<div/>", {'id':'year_container'})
		cont.append($("<p/>").text(this.year)).addClass("label_year")
		var month = ["", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]
		for(var j=1;j<=12;j++){
			var table = $("<table/>").appendTo(cont)
			$("<p/>").text(month[j]).appendTo($("<td/>").appendTo($("<tr/>").appendTo(table))).addClass("label_month")
			$("<td/>").appendTo($("<tr/>").appendTo(table)).append(this.drawMonth(j))
		}
		return cont
	}
}

Calendar.prototype.print = function(){
	document.write(this.toString());
}
