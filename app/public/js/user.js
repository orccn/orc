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
    createdRow: function ( row, data, index ) {
    	$(row).attr('data-userid',data.user_id);
    }
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

function showUserAdd(userid)
{
	 $('#edit-modal').find('input').val('')
	 
	 $('#edit-modal').find('[type="checkbox"]').prop('checked',false)
	 $('#edit-modal').find('[name="need_auth"]').prop('checked',true)
	 
	 $("#edit-modal").modal().find('.alert').hide();
}

$("#menu-list").on('click',".td-add",function(){
	 showMenuAdd($(this).parents('tr').data('code'))
})
$(".title-add").on('click',function(){
	 showMenuAdd(0)
})
$("#user-list").on('click',".td-detail",function(){
	var userid = $(this).parents('tr').data('userid')
	$("#edit-modal").modal().find('.alert').hide();
	$.getJSON('/user/detail',{userid:userid},function(d){
	  if(d.code){
		  return alert(d.msg);
	  }
	  data = d.data;
	  $('#edit-modal [name="user_id"]').val(userid);
	  $('#edit-modal [name="name"]').val(data.name);
	  $('#edit-modal [name="role"]').val(data.role);
	  $('#edit-modal [name="user_code"]').val(data.user_code);
	  $('#edit-modal [name="sex"]').val(data.sex);
	  $('#edit-modal [name="idno"]').val(data.idno);
	  $('#edit-modal [name="certificate_no"]').val(data.certificate_no);
	  $('#edit-modal [name="title"]').val(data.title);
	  $("#edit-modal").modal().find('.alert').hide();
	})
})