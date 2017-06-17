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
        		</form>
            </div>
        </div>
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
</script>
<?php gvar('js',ob_get_clean());?>