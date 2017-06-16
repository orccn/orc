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
        				<label class="col-md-1 control-label">输入密码</label>
        				<div class="col-md-11">
        					<input type="text" class="form-control" name="pwd1">
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="col-md-1 control-label">密码确认</label>
        				<div class="col-md-11">
        					<input type="text" class="form-control" name="pwd2">
        				</div>
        			</div>
        		</form>
            </div>
        </div>
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
</script>
<?php gvar('js',ob_get_clean());?>