<div class="row">
    <div class="col-md-4">
        <div class="portlet box <?=config('portletClass')?>">
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
        <div class="portlet box <?=config('portletClass')?>">
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
    scrollY:        300,
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
</script>
<?php gvar('js',ob_get_clean());?>