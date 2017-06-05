<?php ob_start();?>

<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>功能模块
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default"  @click="test" href="javascript:;">
                        <i class="icon-plus"></i>
                    </a>
                </div>
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
                        <tr id="door-<?= $v['door_code']?>" data-level="<?= $v['door_level']?>" data-end="<?= $v['is_leaf']?>">
                            <td><?= $v['door_name']?></td>
                            <td><?= $v['door_code']?></td>
                            <td><?= $v['door_code']?></td>
                            <td><?= $v['door_url']?></td>
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
<div id="edit" class="modal fade" tabindex="-1" data-keyboard="true" data-attention-animation="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">添加/修改功能</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-md-3 control-label">功能名称</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" :value="row.door_name"> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">功能路径</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" :value="row.door_url"> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">上级功能</label>
                <div class="col-md-9">
                    <select id="single" class="form-control select2">
                        <option value="AK">Alaska</option>
                        <option value="HI" disabled="disabled">Hawaii</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-9">
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" value="option1"> 设为菜单
                        <span></span>
                    </label>
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" value="option2"> 验证权限
                        <span></span>
                    </label>
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
// 	buttons:[{
//         text: '添加',
//         className: 'btn red',
//         action: function ( e, dt, node, config ) {
//             $(".select2").select2({width: null})
//             $("#edit").modal();
//         }
//     }]
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
var app = new Vue({
  el: '#page-content',
  data: {
    row:{},
  },
  methods : {
	  test:function(){
		  $("#edit").modal();
	  }
  }
})
</script>
<?php gvar('js',ob_get_clean());?>