<?php ob_start();?>
<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-3">
        <div class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption" data-toggle="collapse" data-target=".todo-project-list-content-tags">
                    <span class="caption-subject font-red bold uppercase">角色权限分配 </span>
                </div>
            </div>
            <div class="portlet-body todo-project-list-content todo-project-list-content-tags" style="height: auto;">
                <div class="todo-project-list">
                    <ul id="roles" class="nav nav-pills nav-stacked">
                        <li><a href="javascript:;" data-role="2">管理者</a></li>
                        <li><a href="javascript:;" data-role="3">临床用户</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>权限分配 
                </div>
            </div>
            <div class="portlet-body">
                <div id="menu-tree"> </div>
            </div>
        </div>
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
var tree = $('#menu-tree').initJSTree({"plugins" : ["types", "checkbox"]},'/menu/tree')
tree.bind("loaded.jstree", function (event, data) {
	tree.jstree('deselect_all')
	$("#roles a[data-role='3']").trigger('click');
}).bind('click.jstree',function(e){
	if(e.target.nodeName!='A'){
		return
	}
	var active_role = $('#roles li.active');
	if(active_role.length==0){
		return alert('请选择一个角色')
	}	
	var door_codes = tree.jstree('get_selected').join(',')
	var roleid = active_role.find('a').data('role')
	$.getJSON('/role/setauth',{roleid:roleid,door_codes:door_codes},function(d){
		if(d.code){
			return alert(d.msg);
	    }
	    var data = d.data;
	})
})

$("#roles a").on('click',function(){
	$(this).parent().addClass('active').siblings().removeClass('active');
	var roleid = $(this).data('role')
	$.getJSON('/role/detail',{roleid:roleid},function(d){
		if(d.code){
			return alert(d.msg);
	    }
		tree.jstree('deselect_all')
	    if(!d.data.menus){
		    return 
	    }
	    var roleids = d.data.menus.split(',');
	    for(var i in roleids){
	    	tree.jstree('select_node','#'+roleids[i])
	    }
	    if(roleid==2&&user_role!=1||roleid==3&&(user_role!=1&&user_role!=2)){
	    	$("#menu-tree li").each(function(){
		    	tree.jstree('disable_node',$(this))
			})
	    }else{
	    	$("#menu-tree li").each(function(){
		    	tree.jstree('enable_node',$(this))
			})
	    }
	})
})
</script>
<?php gvar('js',ob_get_clean());?>