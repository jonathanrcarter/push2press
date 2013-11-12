wiz:#name:Magento Item


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

	var tableview = Ti.UI.createTableView( { 
		top: 43,
		separatorColor : 'transparent',
		height : Ti.UI.FILL
	} );  
	view.add(tableview);
	view.addEventListener('touch',search_cancel);

	@include("pageformat.js");
	
	var items = [];
	
	@include("openWinFn.js");
	

	var openWin = function(id) {
		openWinFn(items[id].details);
	}
	
	var table_click = function(e) {
		openWin(e.row.xindex);
	}
	tableview.addEventListener('click',table_click);



	var load_data = function(search) {	
		require("/helpers/LocalStorage").setString("magento_search1",search);

		tableview.setData([], {animate : true});

		var rowData = new Array();
		var twitterAPIrequest = "http://m.push2press.com/kitchensink/plugins/connectors/magento/search.php?srch="+search;
		var loader = Ti.Network.createHTTPClient();
	
		loader.open("GET",twitterAPIrequest);
	
		loader.onload = function() {
			var data = eval('('+this.responseText+')');
			items = data.p2p;
		
			for (var i = 0; i < items.length; i++) {  
		
				var item  = items[i];
				var avatar = "";
			
				// Create a row and set its height to auto  
				var row = Ti.UI.createTableViewRow({
					backgroundColor : "#000",
					classname: 'tableRow',
					xindex : i,
					height:Ti.UI.SIZE
				});  
			
				// Create the view that will contain the text and avatar  
				var post_view = Ti.UI.createView({ height:Ti.UI.SIZE, layout:'vertical',top:5, right:5, bottom:65, left:5 });  

				formatpage(post_view,item.summary);

				// Add the post view to the row  
				row.add(post_view);  

				// Add row to the rowData array  
				rowData[i] = row;
			}
		
			// Create the table view and set its data source to "rowData" array  
			tableview.setData(rowData, {animate : true});
	
		}
		loader.send();
	}

	load_data(search);
}


