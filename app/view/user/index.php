<?php ob_start();?>
<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-4">
        <div class="portlet green box">
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
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>人员列表  </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="datatable table table-striped table-bordered table-hover table-condensed">
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
<?php ob_start();?>
<script type="text/javascript">
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
        { data: null, defaultContent: ""}
    ],
    scrollY:        300,
    deferRender:    true,
    scroller:       true,
    stateSave:      true,
}
var dt = $('.datatable').initDT(option)
$('#unit-tree').initUnitTree().bind('click.jstree',function(e){
	if(e.target.nodeName!='A'||dblclick.check()){
		return
	}
	dblclick.callback(function() { 
		dt.fnClearTable();
		dt.fnDestroy();
		option.ajax.url = '/user/ls?unit_code='+$(e.target).parents('li').attr('id');
		dt = $('.datatable').initDT(option);
    })
}).bind('dblclick.jstree',function(e){
	
});
</script>
<?php gvar('js',ob_get_clean());?>