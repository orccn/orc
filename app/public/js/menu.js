var option = {
	paging:false,
	ordering:false,
}
var dt = $('#menu-list').initDT(option)
$('#menu-list>tbody>tr').each(function(){
	var str = '';
	var id = $(this).prop('id');
	var level = $(this).data('level');
	if(!$(this).data('end')){
		str += '<i class="fa fa-caret-down">&nbsp;</i>';
	}
	if(level>1){
		$(this).find('td:first').css('padding-left',(level-1)*30+'px')
	}
	$(this).find('td:first').css('cursor','pointer').prepend(str);
}).find('td:gt(0)').addClass('dragsort-handler');

$(".door-name").click(function(){
	var pid = $(this).parents('tr').data('code')
	if($('i.fa',this).hasClass('fa-caret-right')){
		$('i.fa',this).removeClass('fa-caret-right').addClass('fa-caret-down');
	}else{
		$('i.fa',this).removeClass('fa-caret-down').addClass('fa-caret-right');
	}
	$(this).parent().siblings('[data-pid='+pid+']').toggle();
});

$("#menu-list>tbody").dragsort({
	dragSelector: ".dragsort-handler",
	dragStart:function(e){
		var $thistr = $(e.target).parents('tr')
		$thistr.siblings().each(function(){
			$(this).attr('data-visible',$(this).is(':visible')?1:0)
		})
	},
	dragEnd:function(){
		$('#menu-list tr[data-level=1]').each(function(){
			var child = $(this).siblings('[data-pid='+$(this).data('code')+']')
			$(this).after(child.clone())
			child.remove();
		})
		var codes = new Array();
		$('#menu-list tr').each(function(){
			codes.push($(this).data('code'))
			if($(this).data('visible')==1){
				$(this).show();
			}
		})
		$.post('/menu/sort',{codes:codes.join(',')},function(d){
			
		},'json')
		
	},
})



var fieldtpl = $('.tpl-wrapper tr');
function showField(code)
{
  $.getJSON('/field/ls',{door_code:code},function(d){
	  if(d.code){
		  return editError(d.msg)
	  }
	  var data = d.data;
	  $("#field-list").html('');
	  if(Object.keys(data).length>0){
		  for(var i in data){
			  var newtr = fieldtpl.clone().attr('data-id',data[i].field_code) 
			  newtr.find('.en').html(data[i].field_enname)
			  newtr.find('.zh').html(data[i].field_zhname)
			  newtr.find('.td-save').addClass('td-edit').html('编辑').removeClass('td-save')
			  $("#field-list").append(newtr)
		  }
	  }
	  addFieldTd()
	  $("#field-list").dragsort({ dragSelector: ".en,.zh"})
	  if($("#field-modal:visible").length==0){
		  $("#field-modal").data('door_code',code).modal();
	  }
  })
}

function addFieldTd()
{
	if($("#field-list tr").length==$("#field-list tr[data-id]").length){
		var newtr = fieldtpl.clone();
		newtr.find('.td-del').remove();
		$("#field-list").append(newtr);
	}
}

$("#field-list").on('click',".td-add",function(e){
	if($("#field-list tr").length==$("#field-list tr[data-id]").length){
		$(e.target).parents('tr').after(fieldtpl.clone());
	}
})

$("#field-list").on('click',".td-del",function(){
	if($(this).parents('tr').data('id')==undefined){
		return
	}
	if(!confirm('确定删除？')){
		return
	}
	var t = $(this);
	$.getJSON('/field/del',{field_code:$(this).parents('tr').data('id')},function(d){
		if(d.code){
		    return alert(d.msg)
		}
		if($("#field-list tr").length>1){
			t.parents('tr').remove();
		}else{
			showField($('#field-modal').data('door_code'))
		}
	})
})

$("#field-list").on('click',".td-edit",function(){
	var tden = $(this).parent().siblings('.en');
	var tdzh = $(this).parent().siblings('.zh');
	tden.html(fieldtpl.find('.en input').clone().val(tden.html()))
	tdzh.html(fieldtpl.find('.zh input').clone().val(tdzh.html()))
	$(this).addClass('td-save').html('保存').removeClass('td-edit')
})

$("#field-list").on('click',".td-save",function(){
    var formData = {
		field_code:$(this).parents('tr').data('id'),
		door_code:$('#field-modal').data('door_code'),
		field_enname:$(this).parent().siblings('.en').find('input').val(),
		field_zhname:$(this).parent().siblings('.zh').find('input').val(),
    }
    var t = $(this)
    $.post('/field/edit',formData,function(d){
        if(d.code){
      		return editError(d.msg);
        }
        showField(formData.door_code)
    },'json')
})

$("#menu-list").on('click',".td-detail",function(){
	var door_code = $(this).parents('tr').data('code')
	$.getJSON('/menu/detail',{door_code:door_code},function(d){
	  if(d.code){
		  return editError(d.msg);
	  }
	  var data = d.data;
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
function showMenuAdd(code)
{
	 $('#edit-modal').find('input').val('')
	 $('#edit-modal').find('[type="checkbox"]').prop('checked',false)
	 $('#edit-modal').find('[name="need_auth"]').prop('checked',true)
	 $('#edit-modal [name="door_parent"]').val(code);
	 $("#edit-modal").modal().find('.alert').hide();
}
$("#menu-list").on('click',".td-add",function(){
	 showMenuAdd($(this).parents('tr').data('code'))
})
$(".title-add").on('click',function(){
	showMenuAdd(0)
})
$("#menu-list").on('click',".td-del",function(){
	if(!confirm('确定删除？')){
		return 
    }
	$.getJSON('/menu/del',{door_code:$(this).parents('tr').data('code')},function(d){
		if(d.code){
			return alert(d.msg);
		}
		window.location.href = location.href;
	})
})
$("#menu-list").on('click',".td-field",function(){
	showField($(this).parents('tr').data('code'))
})
$("#edit-modal").on('click',".submit",function(){
	  var data = {
		  door_code:$('#edit-modal [name="door_code"]').val(),
		  door_name:$('#edit-modal [name="door_name"]').val(),
		  door_url:$('#edit-modal [name="door_url"]').val(),
		  door_parent:$('#edit-modal [name="door_parent"]').val(),
		  is_menu:$('#edit-modal [name="is_menu"]').prop('checked'),
		  need_auth:$('#edit-modal [name="need_auth"]').prop('checked'),
		  has_field:$('#edit-modal [name="has_field"]').prop('checked')
	  }
	  $.post('/menu/edit',data,function(d){
		  if(d.code){
			  return editError(d.msg);
		  }
		  $("#edit-modal").modal('hide');
		  window.location.href = location.href;
	  },'json')
})