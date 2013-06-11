wiz:#name:RijkMuseum Muse Game

var fn = function(win,view) {
	win.backgroundColor = "#000";
	
	@include("pageformat.js");
	
	var openWinFn = function() {
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
			backgroundColor : "#ccc"
		});
		scrollView1.add(v);
		
/*
		var webv = Ti.UI.createWebView({
			height: 400,
			top : 20,
			width : 320,
			backgroundColor : "#eee"
		});
		v.add(webv);
*/

		globals.open(w);
		
		var items = [];
		var currentitem = 0;
		var correct = "";
		var score = 0;
		var next = function(e) {
			currentitem++;
			if (currentitem >= items.length) return false;
			displayitem(currentitem);
		};
		
		var imgHolder = Ti.UI.createView({
			width : 320,
			height: 250
		})
		var img = Ti.UI.createImageView({
			width : 320,
			height: 250
		});
		imgHolder.add(img);
		v.add(img);
		
		var but = [];
		
		var buttonClick = function(e) {
			items[currentitem].answer = e.source.title;
			items[currentitem].score = 0;
			if (e.source.title == correct) {
				items[currentitem].score = 1;
				score++;
			}
			if (next() == false) {
				alert ("Your score was "+score+" out of "+items.length)
			}
		}
		for (var i=0;i < 4; i++) {
			but[i] = Ti.UI.createButton({
				height : 32,
				width : 300
			});
			but[i].addEventListener('click',buttonClick);
			v.add(but[i]);
		}
		
		
		
//		Ti.App.addEventListener('next',next);
//		w.addEventListener('close', function(e) {
//			Ti.App.removeEventListener('next',next);
//		});
		
		alert("3");
		
		var displayitem = function(num) {
			currentitem = num;
			var itm = items[num];
			img.image = itm.enclosure;
			correct = itm.options[0];
			items[num].correct = correct;
			
			var arr2 = itm.options.sort(function (a,b) { 
				if (Math.random()<.5) return -1; else return 1;
			});
			for (var i=0; i < arr2.length; i++) {
				but[i].title = arr2[i];
				but[i]._i = i;
			}


/*
			var h = "";
			h += "<html><head><style>* { line-height : 1.4em; }</style>";
			h += "<script>";
			h += "function nxt(n,name) {";
			h += " Ti.App.fireEvent('next',{num:"+num+",n:n,name:name});";
			h += "}";
			h += "</script>";
			h += "</head><body>";
			h += "<h2>"+(num+1)+" / of "+items.length+"</h2>";
			h += "<img height='250' src='"+itm.enclosure+"'>";
			h += "<div>"+itm.title+"</div>";
			h += "<div>Who Created this?</div>";
			h += "<form>";
			var arr2 = itm.options.sort(function (a,b) { 
				if (Math.random()<.5) return -1; else return 1;
			});
			
			for (var i=0; i < arr2.length; i++) {
				h += "<div><a href='javascript:nxt("+i+");'>"+arr2[i]+" </a></div>"
			}
			h += "</form>";
			h += "</body>";
			h += "</html>";
			webv.setHtml(h);
*/
		}
		
		var load_data = function() {	

			var rowData = new Array();
			var twitterAPIrequest = "http://m.push2press.com/kitchensink/plugins/connectors/rijksmuseum_muse/game.php";
			var loader = Ti.Network.createHTTPClient();
	
			loader.open("GET",twitterAPIrequest);
	
			loader.onload = function() {
				var data = eval('('+this.responseText+')');
				//alert(data.data.data.items);
				items = data.data.data.items;
				displayitem(0);
				currentitem = 0;
				correct = "";
				score = 0;
	
			}
			loader.send();
		}

		load_data();		
		
	}

	var openWin = function(id) {
		openWinFn(items[id].p2pitem.details);
	}

	var playButton = Ti.UI.createButton({
		title : 'play the painter game'
	});
	
	var playGame = function() {
		openWinFn();
	};
	view.add(playButton);
	playButton.addEventListener('click',playGame);

}


