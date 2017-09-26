$(document).ready(function(){
	var touch 	= $('#resp-menu');
	var menu 	= $('.menu');
	var menu_div 	= $('#menu_div');

	$(touch).on('click', function(e) {
		e.preventDefault();
		menu_div.removeAttr('style');
		menu.slideToggle();
	});

	$(window).resize(function(){
		var w = $(window).width();
		if(w > 767 && menu.is(':hidden')) {
			menu.removeAttr('style');
		}
	});

});
