<div class="row">
    <div class="col-md-4">
        <div id="user-left" class="portlet box <?=config('portletClass')?>">
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
        <div id="user-right" class="portlet box <?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>人员列表  </div>
                <div class="tools"> </div>
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
				<label class="col-md-3 control-label">功能名称</label>
				<div class="col-md-9">
					<input type="hidden" name="door_code">
					<input type="text" class="form-control" name="door_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">功能路径</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="door_url">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">上级功能</label>
				<div class="col-md-9">
					<select id="single" class="form-control select2" name="door_parent">
						<option value="0">顶级</option>
                        <?php foreach ($menuList as $v){ if ($v['door_parent']) continue;?>
                        <option value="<?=$v['door_code']?>"><?=$v['door_name']?></option>
                        <?php }?>
                    </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<label class="mt-checkbox mt-checkbox-outline"> 
					   <input type="checkbox" name="is_menu"> 设为菜单 <span></span>
					</label> 
					<label class="mt-checkbox mt-checkbox-outline"> 
					   <input type="checkbox" name="need_auth"> 验证权限 <span></span>
					</label>
					<label class="mt-checkbox mt-checkbox-outline"> 
					   <input type="checkbox" name="has_field"> 字段控制 <span></span>
					</label>
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
var option = {
    ajax: {"url" : "/user/ls"},
    columns: [
        { data: "user_id" },
        { data: "his_code" },
        { data: "name" },
        { data: "user_code" },
        { data: "sex" },
        { data: "unit_code" },
        { data: "idno" },
        { data: "title" },
        { data: null, defaultContent: tdBtns}
    ],
    scrollY:        600,
    deferRender:    true,
    scroller:       true,
    stateSave:      true,
}
var dt = $('#user-list').initDT(option)
$('#unit-tree').initUnitTree({},'/unit/tree?flag=1').bind('click.jstree',function(e){
	if(e.target.nodeName!='A'||dblclick.check()){
		return
	}
	dblclick.callback(function() { 
		dt.api().ajax.url('/user/ls?unit_code='+$(e.target).parents('li').attr('id')).load();
    })
}).bind('dblclick.jstree',function(e){
	
});
$("#user-list").on('click',".td-unit",function(){
	$("#unit-modal").modal();
	$('#unit-modal .unit-tree').initUnitTree({"plugins" : ["state", "types", "checkbox"]})
})
$("#menu-list .td-detail").on('click',function(){
	var door_code = $(this).parents('tr').data('code')
	$.getJSON('/menu/detail',{door_code:door_code},function(d){
	  if(d.code){
		  return editError(d.msg);
	  }
	  data = d.data;
	  $('#edit-modal [name="door_code"]').val(door_code);
	  $('#edit-modal [name="door_name"]').val(data.door_name);
	  $('#edit-modal [name="door_url"]').val(data.door_url);
	  $('#edit-modal [name="door_parent"]').val(data.door_parent);
	  $('#edit-modal [name="is_menu"]').prop('checked',data.is_menu);
	  $('#edit-modal [name="need_auth"]').prop('checked',data.need_auth);
	  $('#edit-modal [name="has_field"]').prop('checked',data.has_field);
	  $("#edit-modal").modal().find('.alert').hide();
	})
})

</script>
<?php gvar('js',ob_get_clean());?>