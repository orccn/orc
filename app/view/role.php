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
$('#menu-tree').initJSTree({"plugins" : ["state", "types", "checkbox"]},'/menu/tree')
$("#roles a").on('click',function(){
	console.log($(this).data('role'))
})
</script>
<?php gvar('js',ob_get_clean());?>