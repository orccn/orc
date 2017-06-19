var option = {
    ajax: {"url" : "/user/index",dataSrc:function(d){
    	if(d.code){
    		alert(d.msg);
    		return [];
    	}
    	return d.data;
    }},
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
    	$(row).attr({'data-userid':data.user_id,'data-name':data.name});
    }
}
var dt = $('#user-list').initDT(option)
var leftUnitTree = $('#unit-tree').initJSTree({},'/unit/tree?flag=1')
leftUnitTree.bind("loaded.jstree", function (event, data) {
	leftUnitTree.jstree('deselect_all')
}).bind('click.jstree',function(e){
//	if(e.target.nodeName!='A'||dblclick.check()){
	if(e.target.nodeName!='A'){
		return
	}
//	dblclick.callback(function() { 
	dt.api().ajax.url('/user/index?ajax=1&unit_code='+$(e.target).parents('li').attr('id')).load();
//    })
}).bind('dblclick.jstree',function(e){
	
});

$("#user-list").on('click',".td-unit",function(){
	var userid = $(this).parents('tr').data('userid');
	var username = $(this).parents('tr').data('name');
	$('#unit-modal .unit-tree').jstree('destroy')
	$("#unit-modal").modal().find('.modal-title').html('<b>'+username+'</b>可查看的分析单元')
	var modalUnitTree = $('#unit-modal .unit-tree').initJSTree({"plugins" : ["types", "checkbox"]},'/unit/tree')
	modalUnitTree.bind("loaded.jstree", function (event, data) {
		$.getJSON('/user/detail',{userid:userid},function(d){
			if(d.code){
				return alert(d.msg);
		    }
			modalUnitTree.jstree('select_node','#'+d.data.unit_code);
		    modalUnitTree.jstree('disable_node','#'+d.data.unit_code);
		    if(d.data.units){
		    	var units = d.data.units.split(',');
			    for(var i in units){
			    	modalUnitTree.jstree('select_node','#'+units[i])
			    }
		    }
		})
	}).bind('click.jstree',function(e){
		var units = modalUnitTree.jstree('get_selected').join(',')
		$.getJSON('/user/setunit',{userid:userid,units:units},function(d){
			if(d.code){
				return alert(d.msg);
		    }
		    var data = d.data;
		})
	})
})


function showUserAdd(userid)
{
	 $('#edit-modal').find('input').val('')
	 $('#edit-modal').find('[type="checkbox"]').prop('checked',false)
	 $('#edit-modal').find('[name="need_auth"]').prop('checked',true)
	 $("#edit-modal").modal().find('.alert').hide();
}

$("#menu-list").on('click',".td-add",function(){
	showUserAdd($(this).parents('tr').data('code'))
})

$(".title-add").on('click',function(){
	showUserAdd(0)
})

$("#user-list").on('click',".td-detail",function(){
	var userid = $(this).parents('tr').data('userid')
	$("#edit-modal").modal().find('.alert').hide();
	$.getJSON('/user/detail',{userid:userid},function(d){
	  if(d.code){
		  return alert(d.msg);
	  }
	  var data = d.data;
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
$("#user-list").on('click',".td-reset",function(){
	if(!confirm('确定重置此用户密码？')){
		return;
	}
	var userid = $(this).parents('tr').data('userid')
	$.getJSON('/user/reset_password',{userid:userid},function(d){
	  if(d.code){
		  return alert(d.msg);
	  }else{
		  alert('重置成功！');
	  }
	})
})