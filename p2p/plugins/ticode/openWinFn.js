	var openWinFn = function(details) {
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
		scrollView1.add(v);
		formatpage(v,details);
		globals.open(w);
	}
