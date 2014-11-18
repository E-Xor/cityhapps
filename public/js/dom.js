$(function(){

	//fullWidth Search
	$(".main-search").on("focus", function(){
		$(".header-search").fadeOut("fast", function(){
			$('.wide-search').fadeIn("fast");
			$(".search-large").focus();
		});
	});

	$(".search-large").blur(function(){
		$('.wide-search').fadeOut("fast", function(){
			$(".header-search").fadeIn("fast");
		});
	});

	//Mobile Version

	$(".mobile-search").on("click", function(){
		$(".mobile-header").fadeOut("fast", function(){
			$('.wide-search').fadeIn("fast");
			$(".search-large").focus();
		});
	});

	$(".search-large").blur(function(){
		$('.wide-search').fadeOut("fast", function(){
			$(".mobile-header").fadeIn("fast");
		});
	});

});


$(document).ready(function(){
    //Share overlay

    //$(".share").on('click', function(){
    //    alert('firing');
    //    $('.share-overlay').toggle();
    //});

});
