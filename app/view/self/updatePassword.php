<div class="row">
    <div class="col-md-12">
        <div class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>修改密码
                </div>
            </div>
            <div class="portlet-body">
                <form class="form-horizontal" role="form">
        			<div class="form-group">
        				<label class="col-md-2 control-label">输入原密码</label>
        				<div class="col-md-10">
        					<input type="text" class="form-control" name="pwd">
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="col-md-2 control-label">输入新密码</label>
        				<div class="col-md-10">
        					<input type="text" class="form-control" name="pwd1">
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="col-md-2 control-label">密码确认</label>
        				<div class="col-md-10">
        					<input type="text" class="form-control" name="pwd2">
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
	var formData = {
			pwd:$('[name="pwd"]').val(),
			pwd1:$('[name="pwd1"]').val(),
			pwd2:$('[name="pwd2"]').val()
		}
	$.post('/self/update_password',formData,function(d){
	    if(d.code){
	  		return alert(d.msg);
	    }
	    window.location = '/'
	},'json')	
})
</script>
<?php gvar('js',ob_get_clean());?>