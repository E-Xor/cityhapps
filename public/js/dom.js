$(function(){

	$(".registerModal").on("click", function(){

		$('.register').modal("show");

	});

	$('.registerForm').submit(function(){

		$('.register').modal("hide");

	});


	$(".loginModal").on("click", function(){

		$('.login').modal("show");

	});

	$('.loginForm').submit(function(){

		$('.login').modal("hide");

	});
	

});