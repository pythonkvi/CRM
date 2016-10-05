chrome.tabs.onUpdated.addListener(function(tabId,selectInfo,tab) { chrome.pageAction.show(tabId);	});

