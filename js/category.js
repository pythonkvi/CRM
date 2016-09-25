// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

function CategoryLoader(){
	this.current = 0;
	this.selector = [];
	this.selectorIndex = 1
	this.categories = []
	this.container = null
	this.prefix = null
	
	this.fillCategories = function(){
		var mainObject = this
		$.ajax({
			url: "categoryloader.php",
			data: { "id": this.current },
			type: "POST",
			async: false,
			success: function (dataString){
				var dataArray = jQuery.parseJSON(dataString)
				$.each(dataArray, function (k, data){
					mainObject.categories.push(data)
				})
			}
       		})
	}

	this.loadCategory = function(id){
		if (typeof (id) == "undefined" || id == null || id == 0) return

		var parents = this.getParents(id)
		parents.push([0, ""])

		for (var i = parents.length - 1; i > 0; i--) {
			this.current = parents[i][0]
			var sele = this.loadNext()
			this.current = parents[i-1][0]
			sele.val(this.current)
		}
	}

	this.buildTree = function () {
		var tcat = this.categories
		tcat.push ([0, "\u2192", null])
		var url = parseURL(this.prefix)

		processNode = function(ele, cat) {
			var c = ""
			var celename = ""
			for (var i = 0; i < cat.length; ++i) {
				if (cat[i][0] == ele) {
                                        celename = cat[i][1]
                                } 
				if (cat[i][2] == ele) {
					c += processNode(cat[i][0], cat) 
				}
			}
			if (ele == 0) {
				delete url.params['category_id'];
			} else { 
				url.params['category_id'] = ele;
			}
			return "<li><a href='" + formURL(url) + "'>" + celename + "</a>" + (c != "" ? "<ul>" + c + "</ul>" : "") + "</li>"
		}
		return "<ul id='categorybar'>" + processNode(0, tcat) + "</ul>"
        }

	this.getParents = function (id) {
		if (typeof (id) == "undefined" || id == null || id == 0) return

		var parents = []
                var currid = id
                var flag = true
                while (flag){
                        for(var i = 0 ; i < this.categories.length; i++)
                                if (this.categories[i][0] == currid) {
                                        parents.push([parseInt(this.categories[i][0]), this.categories[i][1]])
                                        currid = this.categories[i][2]
                                        if (this.categories[i][2] == 0) {
                                                flag = false
                                        }
                                        break
                                }
                }

		console.log(parents)
		return parents
	}

	this.loadROCategory = function(id){
		if (typeof (id) == "undefined" || id == null || id == 0) return
		
		var parents = this.getParents(id)

                clearURL = parseURL(this.prefix)
                delete clearURL.params["category_id"]
                clearURL = formURL(clearURL)

		var resultArr = {}
                if (parents.length > 0){
                  //this.container.append($("<a/>", {"href": clearURL}).text("\u2218\u2192"))
		  resultArr["\u2218\u2192"] = clearURL
	        }

		for (var i = parents.length - 1; i > -1; i--) {
			var urlParts = null
                        var url = null
                        if (typeof(this.prefix) != "undefined" && this.prefix.length > 0)
                        {
                            urlParts = parseURL(this.prefix)
                            urlParts.params["category_id"] = parents[i][0]
                            url = formURL(urlParts)
                        }

			this.current = parents[i][0]
			//this.container.append($("<a/>", {"href": url}).text(parents[i][1] + (i > 0 ? "\u2192" : "")))
			resultArr[parents[i][1] + (i > 0 ? "\u2192" : "")] = url
		}

		return resultArr
	}
	
	this.loadNext = function(){
		var mainObject = this
		var category = []
		for (var i = 0; i < mainObject.categories.length; i++) {
			if (mainObject.categories[i][2] == mainObject.current) {
				category.push(mainObject.categories[i])
			}
		}			
		var select = $("<select/>", {"name" : "n" + mainObject.selectorIndex++})
		$.each(category, function (k, data){
			select.append($("<option/>", {"value": data[0]}).text(data[1]))
		})
		
		if (category.length > 0) {
			select.appendTo(mainObject.container)
			mainObject.selector.push(select)
			mainObject.current = category[0][0]
			$(mainObject).trigger("changed")
		}
		
		select.change(function(){
			var position = -1
			for (var j = 0 ; j < mainObject.selector.length; j++) {
				if ($(mainObject.selector[j]).attr("name") == this.name) {
					position = j
					break
				}
			}
			console.log(position + " " + this + " " + mainObject.selector.length)
			if (position >= 0) {
				for(var j = position + 1; j < mainObject.selector.length; j++) {
					$(mainObject.selector[j]).remove()
				}
			}
			mainObject.current = $(this).val()
			$(mainObject).trigger("changed")
			mainObject.loadNext()
		})
		
		return select
	}
}

