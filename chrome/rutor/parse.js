var arrayOfA = document.getElementsByTagName("a")
var emptyOfA = []
var sizeOfA = []

for (i = 0; i < arrayOfA.length; i++ ){
	if (/rutor.org\/torrent\/\d+\//.test(arrayOfA[i].href))
	{
		emptyOfA.push(arrayOfA[i])
		if (arrayOfA[i].parentNode.nextSibling.nextSibling.nextSibling.nextSibling == null) {
			sizeOfA.push(arrayOfA[i].parentNode.nextSibling.nextSibling)
		} else {
			sizeOfA.push(arrayOfA[i].parentNode.nextSibling.nextSibling.nextSibling.nextSibling)
		}
		console.log(arrayOfA[i].href);
		console.log(arrayOfA[i].parentNode.nextSibling.nextSibling.nextSibling.nextSibling)
	}
}

var bodyTag = document.getElementsByTagName("body")[0]
var table = document.createElement("table")
var tcaption = document.createElement("caption")
tcaption.innerText = "Список"
table.appendChild(tcaption)

var theader = document.createElement("thead")
var theadrow = document.createElement("tr")
var theadd1 = document.createElement("th")
var theadd2 = document.createElement("th")
theadd1.innerText = "Название"
theadd2.innerText = "Размер"
theadrow.appendChild(theadd1)
theadrow.appendChild(theadd2)
theader.appendChild(theadrow)
table.setAttribute('style', "border: 1px solid #000; border-collapse:collapse; padding:0;")
table.appendChild(theader)

while (bodyTag.firstChild) bodyTag.removeChild(bodyTag.firstChild);

for (i=0; i<emptyOfA.length; i++) {
	emptyOfA[i].innerText = emptyOfA[i].innerText
	emptyOfA[i].href = "http://d.rutor.org/download/" + emptyOfA[i].href.replace(/(.+)\/torrent\/(\d+)\/(.+)/, "$2")
	emptyOfA[i].setAttribute('style', "text-decoration: none;")
	sizeOfA[i].href = emptyOfA[i].href
	
	var tr = document.createElement("tr")
	var td1 = document.createElement("td")
	var td2 = document.createElement("td")
	tr.appendChild(td1)
	tr.appendChild(td2)
    td1.appendChild(emptyOfA[i])
	td2.appendChild(sizeOfA[i])
	td1.setAttribute('style', "border: 1px solid #000; padding:0;")
    td2.setAttribute('style', "border: 1px solid #000; padding:0;")
	
	table.appendChild(tr)
}

bodyTag.appendChild(table)
