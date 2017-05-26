$("#submit").click(function(){
	//$.post('/user/login',$('.login-form').serialize())
	console.log($('.login-form').serializeJSON());
});