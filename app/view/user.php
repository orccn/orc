<div class="row">
    <div class="col-md-4">
        <div id="user-left" class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>分析单元 
                </div>
            </div>
            <div class="portlet-body">
                <div id="unit-tree"> </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div id="user-right" class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>人员列表  </div>
                <div class="tools"> </div>
                <div class="actions">
                    <div class="tools"> </div>
                </div>
            </div>
            <div class="portlet-body">
                <table id="user-list" class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>人员ID </th>
                            <th>人员代码 </th>
                            <th>人员姓名 </th>
                            <th>人员登录号</th>
                            <th>性别</th>
                            <th>所属分析单元</th>
                            <th>身份证号</th>
                            <th>职称</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<div id="edit-modal" class="modal" data-width="760" data-keyboard="true" data-attention-animation="false">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">添加/修改人员</h4>
	</div>
	<div class="modal-body">
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-3 control-label">姓名</label>
				<div class="col-md-9">
					<input type="hidden" name="user_id">
					<input type="text" class="form-control" name="name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">角色</label>
				<div class="col-md-9">
					<select class="form-control" name="role">
						<option value="3">临床用户</option>
						<option value="2">管理者</option>
                    </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">登录号</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="user_code">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">性别</label>
				<div class="col-md-9">
				    <select class="form-control" name="sex">
						<option value="1">男</option>
						<option value="2">女</option>
                    </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">分析单元</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="unit_code">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">身份证号</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="idno">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">执业证书编码</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="certificate_no">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">职称</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="title">
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-outline dark">取消</button>
		<button type="button" class="submit btn green">提交</button>
	</div>
</div>
<div id="unit-modal" class="modal modal-scroll" data-height="760" data-keyboard="true" data-attention-animation="false">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">可查看的分析单元</h4>
	</div>
	<div class="modal-body">
	   <div class="unit-tree"></div>
	</div>
</div>

<?php ob_start();?>
<script type="text/javascript">
var tdBtns = '<a href="javascript:;" class="<?=config('tdDelClass')?>">删除</a>';
tdBtns += '<a href="javascript:;" class="<?=config('tdDetailClass')?>">详情</a>';
tdBtns += '<a href="javascript:;" class="td-unit btn btn-xs purple btn-outline">单元</a>';
tdBtns += '<a href="javascript:;" class="td-reset btn btn-xs dark btn-outline">重置密码</a>';
</script>
<script src="/js/user.js" type="text/javascript" ></script>
<?php gvar('js',ob_get_clean());?>