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


	//register modal
	$(".registerModal").on("click", function(){
		$('.register').modal("show");
	});

	$('.registerForm').on("submit", function(){
		$('.register').modal("hide");
	});

	//login modal
	$(".loginModal").on("click", function(){
		$('.login').modal("show");
	});

	//login modal close on submit 
	$('.loginForm').on("submit", function(){
		$('.login').modal("hide");
	});
	







});