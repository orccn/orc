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