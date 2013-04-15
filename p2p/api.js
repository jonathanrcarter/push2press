$push2press = {};
push2press = {};
$push2press.loading = function() {
	var h = '<div style="padding:16px;"><br><legend>Upgrading</legend><br><br><div id="p2p_download_bar">'+
	'<div class="progress progress-striped active">'+
  	'	<div class="bar" style="width: 100%;"></div>'+
	'</div>'+
	'</div>'+
	'</div>';
	$("#modal-window2").removeClass("modalpreview");
	$("#modal-window2").removeClass("modalpreview600");
	$("#modal-window2").removeClass("modalpreview200qrcode");
	$("#modal-window2").addClass("modalpreview200");
	$("#modal-window2").html(h);
	$("#modal-window2").modal('show');
	setTimeout(function() {
		document.location.href='upgrade.php';
	},500);
};

push2press.qrcode = function() {
	var h = '<div style="padding:16px;"><br><legend>QR Code</legend>';
	h += '<table width="600"><tr>';
	h += '<td valign="top" width="300">'+$("#qrcodesmall").html();
	h += "<br>Send yourself the link by email";
	h += '<form action="api.php"><input type="text" name="emaillinkto"><input class="btn" type="submit"></form>';
	h += "</td><td valign='top'>";
	
	h += '<div id="myCarousel" class="carousel slide">';
	h += "<div class='carousel-inner'>";
	h += "<div class='item'>1.scan the code<div class='qrcode-instructions qrcode1'></div></div>";
	h += "<div class='item'>2.press the searchbar<div class='qrcode-instructions qrcode2'></div></div>";
	h += "<div class='item'>3.press OK<div class='qrcode-instructions qrcode3'></div></div>";
	h += "<div class='item'>4.Open the app<div class='qrcode-instructions qrcode4'></div></div>";
	h += "</div>";
	h += "</div>";
	
	h += "</td></tr></table>";

	$("#modal-window2").removeClass("modalpreview");
	$("#modal-window2").removeClass("modalpreview600");
	$("#modal-window2").addClass("modalpreview200qrcode");
	$("#modal-window2").html(h);
	$("#modal-window2").modal('show');
	setTimeout(function() {
		$('.carousel').carousel()
	},500);
	
};
$push2press.preview = function(URL,A,B,C) {
	var h = "";
	h += "<div style='position:relative;width:1000px;height:600px;'>";
	h += "<image src='images/iphone-preview.png' border='0' class='scaleOn' style='width:390px;position:absolute;top:-60px;left:20px;'>";
	h += "<image src='images/iphone-preview.png' border='0' class='scaleOn' style='width:390px;position:absolute;top:-60px;right:20px;'>";
	h += "<iframe frameborder='0' src='"+URL+"' class='scaleOn' style='position:absolute;top:120px;right:60px;width:320px;height:400px;'></iframe>";
	h += "<p class='scaleOn' style='position:absolute;top:140px;left:150px;width:200px;height:50px;font-size:14px;line-height:14px;'><b>"+A+"</b><br>"+B+"</p>";
	h += "<image class='scaleOn' src='"+C+"' border='0' style='position:absolute;top:140px;left:100px;width:55px;height:55px;'>";
	h += '<a href=""><button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0;right:10px;">&times;</button></a>';
	h += "</div>";
	
	$("#modal-window2").addClass("modalpreview");
	$("#modal-window2").addClass("modalpreview600");
	$("#modal-window2").html(h);
	$("#modal-window2").modal('show');
// 	alert(A+"-"+B+"-"+C);
} 
$push2press.previewpage = function(URL,A,B,C) {
	var h = "";
	h += "<div style='position:relative;width:500px;height:500px;'>";
	h += "<image src='images/iphone-preview.png' border='0' class='scaleOn' style='width:390px;position:absolute;top:-160px;right:24px;'>";
	h += "<iframe frameborder='0' src='"+URL+"' class='scaleOn' style='position:absolute;top:20px;right:60px;width:320px;height:400px;'></iframe>";

//	h += "<p style='position:absolute;top:130px;left:180px;width:200px;height:50px;font-size:14px;line-height:14px;'><b>"+A+"</b><br>"+B+"</p>";
//	h += "<image src='"+C+"' border='0' style='position:absolute;top:129px;left:115px;width:55px;height:55px;'>";
	h += '<a href=""><button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0;right:10px;">&times;</button></a>';
	h += "</div>";
	
	$("#modal-window2").removeClass("modalpreview");
	$("#modal-window2").removeClass("modalpreview600");
	$("#modal-window2").html(h);
	$("#modal-window2").modal('show');
// 	alert(A+"-"+B+"-"+C);
} 

$push2press.preview2 = function(URL) {
	var h = "";
	
	h +="<iframe frameborder='0' src='"+URL+"' style='width:100%;height:100%;'></iframe>";
	
	$("#modal-window2").removeClass("modalpreview");
	$("#modal-window2").html(h);
	$("#modal-window2").modal('show');
}

$push2press.go = function(S) {
	document.location.href=S;
}

$push2press.ckedit = function(S,H,W) {
	CKEDITOR.replace( S, {
		toolbar : 'Basic',
		width : H,
		height : W,
		filebrowserBrowseUrl : '/bootStrap/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl : '/bootStrap/ckfinder/ckfinder.html?type=Images',
		filebrowserFlashBrowseUrl : '/bootStrap/ckfinder/ckfinder.html?type=Flash',
		filebrowserUploadUrl : '/bootStrap/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		filebrowserImageUploadUrl : '/bootStrap/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserFlashUploadUrl : '/bootStrap/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	});
}

$push2press.push = function(ID,TO,MESSID,MESS) {
	var h = "";
    h += "<div class='plain-hero-unit'>";
    h += ID;
    h += "</div>";
    
	$("#modal-window").load("api.php?action=pushwindow&id="+ID);
	$("#modal-window").modal({
		show : true
	});

}

$push2press.push1 = function(ID,TO,MESSID,MESS) {
	var h = "";
    h += "<div class='plain-hero-unit'>";
    h += ID;
    h += "</div>";
    
	$("#modal-window").load("api.php?action=pushwindow1&id="+ID);
	$("#modal-window").modal({
		show : true
	});

}
	
$push2press.push2 = function(ID,TO,MESSID,MESS) {
	var h = "";
    h += "<div class='plain-hero-unit'>";
    h += ID;
    h += "</div>";
    
	$("#modal-window").load("api.php?action=pushwindow2&id="+ID);
	$("#modal-window").modal({
		show : true
	});

}

$push2press.push3 = function(ID,TO,MESSID,MESS) {
	var h = "";
    h += "<div class='plain-hero-unit'>";
    h += ID;
    h += "</div>";
    
	$("#modal-window").load("api.php?action=pushwindow3&id="+ID);
	$("#modal-window").modal({
		show : true
	});

}

$push2press.getlist = function(catid) {
	var sortedIDs = $("#sortable").sortable( "toArray" );
//	alert(sortedIDs)
//	console.log(sortedIDs);
	var lnk = "api.php?action=do-volgorde&catid="+catid+"&order="+sortedIDs;
	document.location.href=lnk;
}

$(function() {
	$("#sortable").sortable({
		placeholder: "ui-state-highlight"
	});
//	$("#sortable").sortable({
//		placeholder : "ui-state-highlight"
//	});
	$("#sortable").disableSelection();
});


var uvOptions = {};
(function() {
	var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
	uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/wlHMAlKuZ4MLJmN2U2w2RA.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
})();


push2press.getEditorToolbar = function() {

	var retval  =
	[
		{ name: 'document', items : [ 'Source','-','Preview','-','Templates' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'SelectAll','-', 'Scayt' ] },
		{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
	        'HiddenField' ] },
//		'/',
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Blockquote','CreateDiv',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar' ] },
//		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'tools', items : [ 'ShowBlocks','-','About' ] }	
	];
	
	return retval;

}

