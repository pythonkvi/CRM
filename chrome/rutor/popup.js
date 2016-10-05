function buttonClicked(e){
	var url = "http://rutor.org/search/" + encodeURI(document.getElementById("query").value);
	chrome.tabs.create({"url": url})
}

function keyPressed(e){
	if (e.keyCode == 13) buttonClicked();
	else return false;
}

document.addEventListener('DOMContentLoaded', function () {
	document.querySelector('button').addEventListener('click', buttonClicked);
	document.getElementById('query').addEventListener('keypress', keyPressed);
});