function fn(win,view) {

	@include("pageformat.js");

	var content = {
		lines : getlines()
	};

	view.layout = 'vertical';
	formatpage(view,content);


}

