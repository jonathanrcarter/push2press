wiz:#name:Magento Catalogue


var fn = function(win,view) {
	win.backgroundColor = "#000";

	var search = require("/helpers/LocalStorage").getString("magento_search1");
	if (search == null) search = "Appsterdam";
	if (search == "") search = "Appsterdam";
	
	/* add the search bar */	
	var searchbar = Titanium.UI.createSearchBar({
	    barColor:'#000', 
	    showCancel:true,
	    value : search,
	    height:43,
	    top:0,
	});	
	view.add(searchbar);
	var search_action = function(e) {
		load_data(searchbar.getValue());
		searchbar.blur();
	}
	var search_cancel = function(e) {
		searchbar.blur();
	}
	searchbar.addEventListener('return',search_action);
	searchbar.addEventListener('cancel',search_cancel);

	
	var mainView = Ti.UI.createView( { 
		top : 44,
		height : Ti.UI.FILL
	});
	view.add(mainView);
	view.addEventListener('touch',search_cancel);

	@include("pageformat.js");
	
	var items = [];
	
	@include("openWinFn.js");
	

	var openWin = function(id) {
		openWinFn(items[id].details);
	}

	var load_data = function(search) {	
		require("/helpers/LocalStorage").setString("magento_search1",search);

		if (mainView.children.length > 0) {
			mainView.remove(mainView.children[0]);
		}

		var rowData = new Array();
		var twitterAPIrequest = "http://m.push2press.com/kitchensink/plugins/connectors/magento/search.php?srch="+search;
		var loader = Ti.Network.createHTTPClient();
	
		loader.open("GET",twitterAPIrequest);
	
		loader.onload = function() {
			var data = eval('('+this.responseText+')');

			var _v = Ti.UI.createView({
				layout : 'vertical'
			});
			formatpage(_v,data.p2p[0].details);
			mainView.add(_v);
	
		}
		loader.send();
	}

	load_data(search);
}



