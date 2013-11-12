var p2psetup = {};
p2psetup.prepareinput = function(input,checkfunction) {
	var obj = $(input);
	$(obj).find('input').focus();
	$(obj).find('input').on('keyup',function(evt) {
		if (evt.keyCode === 13) {
			if (checkfunction) {
				checkfunction.call(this,{});
				if (evt.stopPropagation)    evt.stopPropagation();
				if (evt.cancelBubble!=null) evt.cancelBubble = true;				
				return;
			}
		}
		if ($(this).val() != '') {
			$(obj).find('#submit-button').removeClass('disabled').removeProp('disabled');
		} else {
			$(obj).find('#submit-button').addClass('disabled').prop('disabled',true);
		}
		
		$(obj).find('.help-block').html("");
		$(obj).removeClass('error');
	});
	if (checkfunction) {
		$(obj).find('#submit-button').click(checkfunction);
	}
	return obj;
}

p2psetup.spinnerInit = function(obj) {
	var opts = {
	   lines: 10, // The number of lines to draw
	   length: 4, // The length of each line
	   width: 4, // The line thickness
	   radius: 6, // The radius of the inner circle
	   corners: 1, // Corner roundness (0..1)
	   rotate: 0, // The rotation offset
	   color: '#333', // #rgb or #rrggbb
	   speed: 1, // Rounds per second
	   trail: 32, // Afterglow percentage
	   shadow: false, // Whether to render a shadow
	   hwaccel: false, // Whether to use hardware acceleration
	   className: 'spinner', // The CSS class to assign to the spinner
	   zIndex: 2e9, // The z-index (defaults to 2000000000)
	   top: 'auto', // Top position relative to parent in px
	   left: 'auto', // Left position relative to parent in px
	   visibility: true
   };

   $(obj).html(new Spinner(opts).spin().el);
}

$(function() {
	var ready = [0,0,0,0,0];

	var isready = function() {
		console.log(ready);
		if (ready[0] == 1 && ready[1] == 1 && ready[2] == 1) {
			console.log($("#toStep3"));
			$("#toStep3").removeClass('disabled');
			try { $("#toStep3").removeProp('disabled'); } catch(E) {console.log(E);}
		} else {
			console.log($("#toStep3"));
			$("#toStep3").addClass('disabled');
			try { $("#toStep3").prop('disabled'); } catch(E) {console.log(E);}
		}
	}
	
	/*
	var title = p2psetup.prepareinput("#step2-title", function(evt) {
		var val = $("#step2-title input").val();
		if (val == "") return isready();
		p2psetup.spinnerInit("#step2-title .spinner");
		isready();
	});
	*/
	var title = p2psetup.prepareinput("#step2-title");
	var title_fn = function(){
		ready[0] = 0;
		var val = $("#step2-title input").val();
		if (val == "") {
			$("#step2-title").removeClass("success");
			isready();
			return;
		}
		ready[0] = 1;
		$("#step2-title").addClass("success");
		isready();
	};
	
	$(title).on('change',title_fn);
	title_fn.call(this,{});

	/* ADMIN EMAIL */	
	var adminemail = p2psetup.prepareinput("#step2-adminemail");
	var adminemail_fn = function(){
		ready[1] = 0;
		var val = $("#step2-adminemail input").val();
		if (val == "") return isready();
		p2psetup.spinnerInit("#step2-adminemail .spinner");
		if (val == "") {
			$("#step2-adminemail").removeClass("success");
			return isready();
		}
		
		$.ajax({
			url : "plugins/connectors/push2press_setup/isemail.php?val="+val,
			dataType : 'json',
			success : function(data) {
				$('#step2-adminemail').find('.spinner').html("");
				if (data.status == 0) {
					$("#step2-adminemail").addClass("success");
					$('#step2-adminemail .help-block').html(data.autodetect);
					if (data.autodetect.twitterusername) {
						if ($("#step2-twitter input").val() == "") {
							$("#step2-twitter input").val(data.autodetect.twitterusername);
						}
					}
					/*
					if (data.autodetect.facebookusername) {
						if ($("#step2-facebook input").val() == "") {
							$("#step2-facebook input").val(data.autodetect.facebookusername);
						}
					}
					if (data.autodetect.linkedinurl) {
						if ($("#step2-linkedin input").val() == "") {
							$("#step2-linkedin input").val(data.autodetect.linkedinurl);
						}
					}
					*/
					ready[1] = 1;
					isready();
					
				} else {
					$("#step2-adminemail").removeClass("success");
					$('#step2-adminemail .help-block').html(data.statusMsg);
					$('#step2-adminemail').addClass('error');
					isready();
				}
			},
			cache: false
		});
				
		
	}
	$(adminemail).on('change',adminemail_fn);
	adminemail_fn.call(this,{});
	
	
	/* PASSWORD */
	var password = $("#step2-password input");
	var password_fn = function(){
		ready[2] = 0;
		var val = $("#step2-password password").val();
		if (val == "") {
			$("#step2-password").removeClass("success");
			isready();
			return;
		}
		ready[2] = 1;
		$("#step2-password").addClass("success");
		isready();
	};
	
	$(password).on('change',password_fn);
	password_fn.call(this,{});

	/* WEBSITE URL */

	var website = p2psetup.prepareinput("#step2-website");
	var website_fn = function(){
		ready[3] = 0;
		var val = $("#step2-website input").val();
		if (val == "") return isready();
		p2psetup.spinnerInit("#step2-website .spinner");
		if (val == "") {
			$("#step2-website").removeClass("success");
			return isready();
		}
		
		$.ajax({
			url : "plugins/connectors/push2press_setup/checkurl.php?val="+val,
			dataType : 'json',
			success : function(data) {
				$('#step2-website').find('.spinner').html("");
				if (data.status == 0) {
					$("#step2-website").addClass("success");
					$('#step2-website .help-block').html(data.tags);
					ready[3] = 1;
					isready();
				} else {
					$("#step2-website").removeClass("success");
					$('#step2-website .help-block').html(data.statusMsg);
					$('#step2-website').addClass('error');
					isready();
				}
			},
			cache: false
		});
	};
	$(website).on('change',website_fn);
	website_fn.call(this,{});



/*

	var facebook = p2psetup.prepareinput("#step2-facebook");
	$(facebook).on('change',function(){
		console.log("facebook oauth changed");
	});
	$("#facebook-oauth").bind('click', function(e){

		console.log("facebook oauth clicked");

	    var win;
	    var checkConnect;
	    var $connect = $("#some_button");
//		var oAuthURL = "plugins/connectors/push2press_setup/oauth-facebook.php";
		var oAuthURL = "plugins/connectors/push2press_setup/fb.php";
		win = window.open(oAuthURL, 'SomeAuthentication', 'width=972,height=660,modal=yes,alwaysRaised=yes');

	    checkConnect = setInterval(function() {
	        if (!win || !win.closed) return;
	        clearInterval(checkConnect);
//	        window.location.reload();
	    }, 100);
	})

	var linkedin = p2psetup.prepareinput("#step2-linkedin");
	$(linkedin).on('change',function(){
		console.log("oauth changed");
	});
	$("#linkedin-oauth").bind('click', function(e){

		console.log("oauth clicked");

	    var win;
	    var checkConnect;
	    var $connect = $("#some_button");
		var oAuthURL = "plugins/connectors/push2press_setup/oauth-linkedin.php";
		win = window.open(oAuthURL, 'SomeAuthentication', 'width=972,height=660,modal=yes,alwaysRaised=yes');

	    checkConnect = setInterval(function() {
	        if (!win || !win.closed) return;
	        clearInterval(checkConnect);
//	        window.location.reload();
	    }, 100);
	})
*/

	$("#toStep3").bind('click', function(e){
		title_fn.call(this,{});
		//adminemail_fn.call(this,{});
		password_fn.call(this,{});
		//website_fn.call(this,{});
		console.log("ready",ready);
		console.log("isready",isready());
		$("#setup-form").submit();
	});
	
	isready();

	
});


