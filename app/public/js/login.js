$("#submit").click(function(){
	$.post('/user/login',$('.login-form').serializeJSON(),function(d){
		if(d.code){
			var $alertDanger = $('.alert-danger', $('.login-form'));
			$alertDanger.find('span').html(d.msg);
			$alertDanger.show();
		}
	},'json');
});