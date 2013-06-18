wiz:#name:Twitter Time Line
wiz:Twitter Screen Name:twitterUser:push2press


var fn = function(win,view) {
	var scrollView1 = Ti.UI.createScrollView({
	    contentWidth:'auto',
	    contentHeight:'auto'
	});
	view.add(scrollView1);
	
	
	var rowData = new Array();
	var twitterAPIrequest = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name="+twitterUser;
	var loader = Ti.Network.createHTTPClient();
	
	loader.open("GET",twitterAPIrequest);
	loader.setRequestHeader("Authorization","Bearer AAAAAAAAAAAAAAAAAAAAAODwRQAAAAAAQei%2BqyASxOmNkesqU26WXXh6l5A%3DMawjJFnPgNxPv7ddhAh5cgOeCseGbLSmT3aK9CSvCo");
	
	loader.onload = function() {
		var tweets = eval('('+this.responseText+')');
		for (var i = 0; i < tweets.length; i++) {  
			var tweet  = tweets[i].text; // The tweet message  
			var user   = tweets[i].user.screen_name; // The screen name of the user  
			var avatar = tweets[i].user.profile_image_url; // The profile image  

			// Create a row and set its height to auto  
			var row = Ti.UI.createTableViewRow({
				classname: 'tableRow',
				backgroundGradient: css.ROWGRAD,
				height:Ti.UI.SIZE
			});  
			// Create the view that will contain the text and avatar  
			var post_view = Ti.UI.createView({ height:Ti.UI.SIZE, layout:'vertical', top:5, right:5, bottom:5, left:5 });  
			// Create image view to hold profile pic  
			var av_image = Ti.UI.createImageView({  
			    url:avatar, // the image for the image view  
			    top:'0dp',  
			    left:'0dp',  
			    height:'48dp',  
			    width:'48dp'  
			});  
			post_view.add(av_image); 
			// Create the label to hold the screen name  
			var user_lbl = Ti.UI.createLabel({  
			    text:user,  
			    left:'54dp',  
			    width:'120dp',  
			    top:'-48dp',  
			    bottom:'2dp',  
			    height:'16dp',  
			    textAlign:'left',  
			    color:'#444444',  
			    font:{  
			        fontSize:14,fontWeight:'bold'  
			    }  
			});  
			post_view.add(user_lbl);
			// Create the label to hold the tweet message  
			var tweet_lbl = Ti.UI.createLabel({  
			    text: tweet,  
			    left: '54dp',  
			    top: '0dp',  
			   // bottom: '2dp',  
			    bottom:'6dp',  
			    height: Ti.UI.SIZE,  
			    width: '236dp',  
			    textAlign: 'left',  
			    font:{  
			       fontSize:14,fontWeight:'normal'  
			    }  
			});  
			post_view.add(tweet_lbl);
			// Add the post view to the row  
			row.add(post_view);  
			// Give each row a class name  
			row.className = "twitterRow";  
			// Add row to the rowData array  
			rowData[i] = row;
		}
		// Create the table view and set its data source to "rowData" array  
		var twitterview = Ti.UI.createTableView( { data : rowData } );  
		//Add the table view to the window  
		scrollView1.add(twitterview);
	
	}
	loader.send();
}


