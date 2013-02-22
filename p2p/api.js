$push2press = {};
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

