<?php ob_start();?>

<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>功能模块</div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="datatable table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>功能名称 </th>
                            <th>功能编码 </th>
                            <th>上级功能 </th>
                            <th>功能路径</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menuList as $v){?>
                        <tr id="door-<?= $v['door_code']?>" data-level="<?= $v['door_level']?>" data-end="<?= $v['end_flag']?>">
                            <td><?= $v['door_name']?></td>
                            <td><?= $v['door_code']?></td>
                            <td><?= $v['door_code']?></td>
                            <td><?= $v['sys_windows']?></td>
                            <td></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<div id="edit" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="true" data-attention-animation="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">添加/修改功能</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-md-3 control-label">功能名称</label>
                <div class="col-md-9">
                    <input type="text" class="form-control"> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">功能路径</label>
                <div class="col-md-9">
                    <input type="text" class="form-control"> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">上级功能</label>
                <div class="col-md-9">
                    <input type="password" class="form-control"> 
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">取消</button>
        <button type="button" data-dismiss="modal" class="btn green">提交</button>
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
var option = {
	paging:false,
	ordering:false,
	buttons:[{
        text: '添加',
        className: 'btn red',
        action: function ( e, dt, node, config ) {
            $("#edit").modal();
        }
    }]
}
var dt = $('.datatable').initDT(option)
$('.datatable>tbody>tr').each(function(){
	var str = '';
	var id = $(this).prop('id');
	var level = $(this).data('level');
	for(i=1;i<level;i++){ str += '&nbsp;&nbsp;&nbsp;';}
	if($(this).data('end')=='N'){
		str += '<i class="fa fa-caret-right">&nbsp;</i>';
		$('td:first',this).mouseover(function(){
			$(this).css('cursor','pointer');
		});
		$('td:first',this).click(function(){
			var isRight = $('i.fa',this).hasClass('fa-caret-right');
			if(isRight){
				$('i.fa',this).removeClass('fa-caret-right').addClass('fa-caret-down')
			}else{
				$('i.fa',this).removeClass('fa-caret-down').addClass('fa-caret-right')
			}
			$(this).parent().siblings('[id^="'+id+'"]').toggle('fast');
		});
	}
	$(this).find('td:first').prepend(str);
});
</script>
<?php gvar('js',ob_get_clean());?>