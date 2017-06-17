<div class="note note-warning">
    <h4 class="block">请设置密码</h4>
    <p>你可能是第一次登录,或者密码被管理员重置，请设置密码.</p>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>请设置密码 
                </div>
            </div>
            <div class="portlet-body">
                <form class="form-horizontal" role="form">
        			<div class="form-group">
        				<label class="col-md-2 control-label">输入密码</label>
        				<div class="col-md-10">
        					<input type="password" class="form-control" name="pwd1">
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="col-md-2 control-label">密码确认</label>
        				<div class="col-md-10">
        					<input type="password" class="form-control" name="pwd2">
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="col-md-2 control-label"></label>
        				<div class="col-md-10">
        					<button type="button" class="submit btn green">提交</button>
        				</div>
        			</div>
        		</form>
            </div>
        </div>
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
$('.submit').on('click',function(){
	var formData = {pwd1:$('[name="pwd1"]').val(),pwd2:$('[name="pwd2"]').val()}
	$.post('/self/init_password',formData,function(d){
	    if(d.code){
	  		return alert(d.msg);
	    }
	    window.location = '/'
	},'json')	
})

</script>
<?php gvar('js',ob_get_clean());?>