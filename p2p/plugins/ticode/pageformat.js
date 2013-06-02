
	var openPage = function(url) {
		var w = Ti.UI.createWindow({
			navBar : "y",
			barColor : data.config2.bgc1,
			backgroundColor : "#000"
		});
		var scrollView1 = Ti.UI.createScrollView({
		    contentWidth: 'auto',
		    contentHeight:'auto'
		});
		w.add(scrollView1);

		var v = Ti.UI.createView({
			layout : 'vertical',
			backgroundColor : "#000"
		});

		var loader = Ti.Network.createHTTPClient();
		loader.open("GET",url);
		loader.onload = function() {
			try {
				var data = eval('('+this.responseText+')');
				formatpage(v,data.p2p[0].details);
				scrollView1.add(v);
			} catch (e) {
				alert(e);
			}
		}
		loader.send();
		globals.open(w);
	}
	
	var formatpage = function(v,obj) {
		try{
		var leftmargin = 4;
		
		for (var i=0; i < obj.lines.length; i++) {
			var line = obj.lines[i];
			if (line.indexOf("p:") == 0) {
				var txt = Ti.UI.createLabel({  
				    text : line.substring(2),  
				    left : leftmargin, right : 4,  
				    top : 4, bottom : 4,  
				    height : Ti.UI.SIZE,  
				    textAlign : 'left',  
				    color : '#dddddd',  
				    font:{  
				        fontSize:14  
				    }
				});
				v.add(txt);

			} else if (line.indexOf("tab:") == 0) {
				leftmargin += 8;
			} else if (line.indexOf("btab:") == 0) {
				leftmargin -= 8;
			} else if (line.indexOf("s:") == 0) {
				var txt = Ti.UI.createLabel({  
				    text : line.substring(2),  
				    left : leftmargin, right : 4,  
				    top : 4, bottom : 4,  
				    height : Ti.UI.SIZE,  
				    textAlign : 'left',  
				    color : '#dddddd',  
				    font:{  
				        fontSize:10  
				    }
				});
				v.add(txt);

			} else if (line.indexOf("h11:") == 0) {
				var line_view = Ti.UI.createView({
				    top : 8, bottom : 8,  
				    height : Ti.UI.SIZE  
				});
				line_view.add(Ti.UI.createView({
					left : leftmargin,
					width : 6,
				    top : 0,
				    height : 40,
					backgroundColor : "#cc0000"
				}));
				var txt = Ti.UI.createLabel({  
				    text : line.substring(4),  
				    left : leftmargin+16, right : 4,
				    top : 0,
				    height : Ti.UI.SIZE,  
				    textAlign : 'left',  
				    color : '#dddddd',  
				    font:{  
				        fontSize:18, fontWeight : 'bold'  
				    }
				});
				line_view.add(txt);
				v.add(line_view);
				
			} else if (line.indexOf("h1:") == 0) {
				var txt = Ti.UI.createLabel({  
				    text : line.substring(3),  
				    left : leftmargin, right : 4,  
				    top : 8, bottom : 8,  
				    height : Ti.UI.SIZE,  
				    textAlign : 'left',  
				    color : '#dddddd',  
				    font:{  
				        fontSize:18, fontWeight : 'bold'  
				    }
				});
				v.add(txt);

			} else if (line.indexOf("img:") == 0) {
				var img = Ti.UI.createImageView({
					width : Ti.UI.FILL,
					image : line.substring(4)
				});
				v.add(img);

			} else if (line.indexOf("imgfullsize:") == 0) {
				var img = Ti.UI.createImageView({
					image : line.substring(12)
				});
				v.add(img);
				img.addEventListener('load',function(e){
					win.updateLayout({opacity : 1});
				})

			} else if (line.indexOf("movie:") == 0) {

				var videoPlayer = Titanium.Media.createVideoPlayer({
				    autoplay : false,
				    height : 240,
				    width : 320,
				    mediaControlStyle : Titanium.Media.VIDEO_CONTROL_DEFAULT,
				    scalingMode : Titanium.Media.VIDEO_SCALING_ASPECT_FIT
				});
				videoPlayer.url = line.substring(6);
				v.add(videoPlayer);
				
			} else if (line.indexOf("zmovie:") == 0) {
				var imgnames = line.substring(7);
				var imgs = imgnames.split(",");
				var img = Ti.UI.createImageView({
					width : Ti.UI.FILL,
					image : imgs[0],
					xmovie : imgs[1]
				});
				v.add(img);
				img.addEventListener('click',function(e) {
					var ob = {
						lines : ["movie:"+e.source.xmovie]
					}
					openWinFn(ob);
				});
			
			} else if (line.indexOf("zimg:") == 0) {
				var imgnames = line.substring(5);
				var imgs = imgnames.split(",");
				var img = Ti.UI.createImageView({
					width : Ti.UI.FILL,
					image : imgs[0],
					ximage : imgs[1]
				});
				v.add(img);
				img.addEventListener('click',function(e) {
					var ob = {
						lines : ["imgfullsize:"+e.source.ximage]
					}
					openWinFn(ob);
				});

			} else if (line.indexOf("pg:") == 0) {
				var imgnames = line.substring(3);
				var imgs = imgnames.split(",");

				var txt = Ti.UI.createLabel({  
				    text : imgs[0],  
				    left : leftmargin, right : 4,  
				    top : 4, bottom : 4,  
				    height : Ti.UI.SIZE,  
				    textAlign : 'left',  
				    color : '#dddddd',  
				    xurl : imgs[1],
				    font:{  
				        fontSize:14  
				    }
				});
				v.add(txt);
				txt.addEventListener('click', function(e){
					openPage(e.source.xurl);
				});
			
			}
		
		}
		} catch (e) { alert(e) }
	}
