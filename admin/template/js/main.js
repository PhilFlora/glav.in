$(function() {

	// Show navigation
	$('#nav-button').click(function() {

		var $btn = $(this);

		if($btn.hasClass('nav-button-close')) {

			$btn.removeClass('nav-button-close');

			$('#nav-container nav ul li').each(function(i) {
				$(this).delay(i*100).slideUp(); 
			});

		} else {

			$btn.addClass('nav-button-close');

			$('#nav-container nav ul li').each(function(i) {
				$(this).delay(i*100).slideDown(); 
			});

		}

	});

	// Slide down about Glav.in
	$('.about-link').click(function() {

		$('#about-wrap').slideToggle(300, function() {
			
			$('#nav-button').removeClass('nav-button-close');

			$('#nav-container nav ul li').each(function(i) {
				$(this).delay(i*100).slideUp(); 
			});		

		});
	});

	$('#about-close-btn').click(function() {
		$('#about-wrap').slideToggle(300);
	});

});
