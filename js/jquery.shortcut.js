jQuery(function ($) {

	$(document).bind("keydown","alt+ctrl+g", function(e){
		$("#content").insertAtCaret('[gist id="" file=""]');
	});

	$('textarea#content').bind("keydown","alt+ctrl+g", function(e){
		$(this).insertAtCaret('[gist id="" file=""]');
	});

});