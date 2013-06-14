wiz:#name:RijkMuseum Muse Game

var TYPE = "";

var fn = function(win,view) {
	win.backgroundColor = "#000";
	win.setOrientationModes([Titanium.UI.PORTRAIT]);
	
	@include("pageformat.js");
	
	var openWinFn = function() {
		var w = Ti.UI.createWindow({
			navBar : "y",
			orientationModes : [Titanium.UI.PORTRAIT],
			barColor : data.config2.bgc1,
			backgroundColor : "#000"
		});
		var scrollView1 = Ti.UI.createScrollView({
		    contentWidth: 'auto',
		    contentHeight:'auto'
		});
//		w.add(scrollView1);

		var v = Ti.UI.createView({
			layout : 'vertical',
			backgroundColor : "#000000"
		});
//		scrollView1.add(v);
		w.add(v);
		
		var wClose = function() {
			w.close();
		}

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
			height: 250,
			borderWidth : 6,
			borderColor : "#ffffff"
		})
		var img = Ti.UI.createImageView({
			width : 320,
			height: 250
		});
		imgHolder.add(img);
		v.add(img);
		
		var but = [];
		
		var buttonClick = function(e) {
			e.source.backgroundColor = "#cc0000";
			items[currentitem].answer = e.source.title;
			items[currentitem].score = 0;
			if (e.source.title == correct) {
				items[currentitem].score = 1;
				score++;
			}
			if (next() == false) {
				w.remove(v);
				w.remove(button_view);
				var scrollView2 = Ti.UI.createScrollView({
				    contentWidth: 'auto',
				    contentHeight:'auto'
				});
				w.add(scrollView2);
				
				var v2 = Ti.UI.createView({
					layout : 'vertical',
					backgroundColor : "#000000"
				});
				scrollView2.add(v2);
				
				v2.add(Ti.UI.createLabel({
					font : {
						fontSize:24,
						fontFamily : "Futura-CondensedMedium"
					},
					text : "Your score was "+score+" out of "+items.length+"\n\nThe Correct answers were:",
					top : 40,
					color : "#ffffff"
				}));
				for (var i=0;i < items.length; i++) {
					v2.add(Ti.UI.createImageView({
						top : 40,
						image : items[i].enclosure
					}));
					v2.add(Ti.UI.createLabel({
						font : {
							fontSize:24,
							fontFamily : "Futura-CondensedMedium"
						},
						text : items[i].title,
						top : 4,
						color : "#ffffff"
					}));
					v2.add(Ti.UI.createLabel({
						font : {
							fontSize:24,
							fontFamily : "Futura-CondensedMedium"
						},
						text : "Created by : " + items[i].correct,
						top : 4,
						height : Ti.UI.SIZE,
						bottom : 20,
						color : "#00cc00"
					}));
				}

				
				var closebutton = Ti.UI.createButton({
					font : {
						fontSize:22,
						fontFamily : "Futura-CondensedMedium"
					},
					title : "close",
					style : 0,
					horizontalWrap : true,
					color : "#ffffff",
					backgroundColor : "#333333",
					height : 32,
					borderRadius : 8,
					top : 4,
					bottom : 4,
					width : '95%'
				});
				closebutton('click',wClose);
			}
		}

		var button_view = Ti.UI.createView({
			layout : 'vertical',
			height : Ti.UI.SIZE,
			bottom : 0,
			backgroundColor : "#666666"
		});
		w.add(button_view);

		button_view.add(Ti.UI.createLabel({
			font : {
				fontSize:14,
				fontFamily : "Futura-CondensedMedium"
			},
			text : "Who creted this?",
			top : 4,
			color : "#ffffff"
		}));

		
		for (var i=0;i < 4; i++) {
			but[i] = Ti.UI.createButton({
				font : {
					fontSize:14,
					fontFamily : "Futura-CondensedMedium"
				},
				style : 0,
				horizontalWrap : true,
				color : "#ffffff",
				backgroundColor : "#333333",
				height : 32,
				borderRadius : 8,
				top : 4,
				bottom : 4,
				width : '95%'
			});
			but[i].addEventListener('click',buttonClick);
			button_view.add(but[i]);
		}
		
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
				but[i].backgroundColor = "#333333";
				but[i]._i = i;
			}
			
			w.title = (num+1)+" / of "+items.length;
		}
		
		var load_data = function() {	

			var rowData = new Array();
			var twitterAPIrequest = "http://m.push2press.com/kitchensink/plugins/connectors/rijksmuseum_muse/game.php?type="+TYPE;
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
		style : 0,
		horizontalWrap : true,
		height : 32,
		borderRadius : 8,
		top : 210,
		bottom : 4,
		width : '95%',
		title : 'Start the game',
		font : {
			fontSize:28,
			fontFamily : "Futura-CondensedMedium"
		},
		color : "#ffffff",
		backgroundColor : "#333333"
	});
	
	var playGame = function() {
		openWinFn();
	};

	var bgview = Ti.UI.createView({
	});
	var bgviewImg = Ti.UI.createImageView({
		image : "http://m.push2press.com/kitchensink/plugins/connectors/rijksmuseum_muse/bgsplash5.png",
		top : 0,
		width : 320,
		height : 750
	});
	bgview.add(bgviewImg);
	win.add(bgview);

	view.add(playButton);
	playButton.addEventListener('click',playGame);

}


