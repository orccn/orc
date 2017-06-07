<?php ob_start();?>

<?php gvar('css',ob_get_clean());?>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box <?=config('portletClass')?>">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-globe"></i>功能模块
				</div>
				<div class="actions">
                    <a href="javascript:;" class="<?=config('titleAddClass')?>" @click="showAdd(0)">
                        <i class="fa fa-plus"></i> 添加
                    </a>
                </div>
			</div>
			<div class="portlet-body">
				<table
					class="datatable table table-striped table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th>功能名称</th>
							<th>功能编码</th>
							<th>上级功能</th>
							<th>功能路径</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
                        <?php foreach ($sortMenu as $v){?>
                        <tr class="pid-<?= $v['door_parent']?>"
                            data-level="<?= $v['door_level']?>"
							data-end="<?= $v['is_leaf']?>">
							<td @mouseover="mouseoverTd" @click="clickCaret(<?= $v['door_code']?>,$event)"><?= $v['door_name']?></td>
							<td><?= $v['door_code']?></td>
							<td><?= $v['door_parent'] ? $menuList[$v['door_parent']]['door_name'] : '顶级'?></td>
							<td><?= $v['door_url']?></td>
							<td>
							     <a href="javascript:;" class="<?=config('tdEditClass')?>" @click="showEdit(<?= $v['door_code']?>)">编辑</a>
							     <a href="javascript:;" class="<?=config('tdDelClass')?>" @click="del(<?= $v['door_code']?>)">删除</a>
							     <a href="javascript:;" v-if="!<?=intval($v['door_parent'])?>" class="<?=config('tdAddClass')?>" @click="showAdd(<?= $v['door_code']?>)">添加</a>
							</td>
						</tr>
                        <?php }?>
                    </tbody>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<div id="edit" class="modal" data-keyboard="true"
	data-attention-animation="false">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h4 class="modal-title">添加/修改功能</h4>
	</div>
	<div class="modal-body">
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-3 control-label">功能名称</label>
				<div class="col-md-9">
					<input type="text" class="form-control" v-model="row.door_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">功能路径</label>
				<div class="col-md-9">
					<input type="text" class="form-control" v-model="row.door_url">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">上级功能</label>
				<div class="col-md-9">
					<select id="single" class="form-control select2"
						v-model="row.door_parent">
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
					<label class="mt-checkbox mt-checkbox-outline"> <input
						type="checkbox" v-model="row.is_menu"> 设为菜单 <span></span>
					</label> <label class="mt-checkbox mt-checkbox-outline"> <input
						type="checkbox" v-model="row.need_auth"> 验证权限 <span></span>
					</label>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal"
			class="btn btn-outline dark">取消</button>
		<button type="button" class="btn green" @click="edit">提交</button>
	</div>
</div>
<?php ob_start();?>
<script type="text/javascript">
var option = {
	paging:false,
	ordering:false,
// 	buttons:[{
//         text: '添加',
//         className: 'add btn red',
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
	if(!$(this).data('end')){
		str += '<i class="fa" :class={"fa-caret-right":caret2Right,"fa-caret-down":!caret2Right}>&nbsp;</i>';
	}
	$(this).find('td:first').prepend(str);
});
var app = new Vue({
  el: '#page-content',
  data: {
    row:{},
    caret2Right : true,
  },
  methods : {
	  mouseoverTd:function(e){
		  $(e.target).css('cursor','pointer');
	  },
	  clickCaret:function(pid,e){
		  this.caret2Right = !$('i.fa',e.target).hasClass('fa-caret-right');
          $(e.target).parent().siblings('.pid-'+pid).toggle('fast');
	  },
	  showEdit:function(code){
		  var t = this;
		  $.getJSON('/menu/detail',{code:code},function(d){
			  if(d.code){
				  return editError(d.msg);
			  }
			  t.row = d.data
			  $("#edit").modal().find('.alert').hide();
		  })
	  },
	  showAdd:function(pid)
	  {
		  this.row = {door_parent:pid,need_auth:'true'}
		  $("#edit").modal().find('.alert').hide();
	  },
	  edit:function(){
		  var t = this;
		  $.post('/menu/edit',t.row,function(d){
			  if(d.code){
				  return editError(d.msg);
			  }
			  $("#edit").modal('hide');
			  window.location.href = location.href;
		  },'json')
	  },
	  del:function(code){
		  if(!confirm('确定删除？')){
			  return 
	      }
		  $.getJSON('/menu/del',{code:code},function(d){
			  if(d.code){
				  return alert(d.msg);
			  }
			  window.location.href = location.href;
		  })
	  }
  }
})
</script>
<?php gvar('js',ob_get_clean());?>