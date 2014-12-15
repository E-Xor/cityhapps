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

	$('.search-clear').on('click', function(){
		$('.search-large').blur();
		$('input.search-large').val('');
		$('input.main-search').val('');
	});

	$(".wide-search").submit(function(){
		$('input.main-search').val('');
		$('input.search-large').val('');
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
