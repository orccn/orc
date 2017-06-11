var option = {
	paging:false,
	ordering:false,
// buttons:[{
// text: '添加',
// className: 'add btn red',
// action: function ( e, dt, node, config ) {
// $(".select2").select2({width: null})
// $("#edit-modal").modal();
// }
// }]
}
var dt = $('.datatable').initDT(option)
$('.datatable>tbody>tr').each(function(){
	var str = '';
	var id = $(this).prop('id');
	var level = $(this).data('level');
	for(i=1;i<level;i++){ str += '&nbsp;&nbsp;&nbsp;';}
	if(!$(this).data('end')){
		str += '<i class="fa fa-caret-down">&nbsp;</i>';
	}
	$(this).find('td:first').css('cursor','pointer').prepend(str);
});

var fieldtpl = $('.tpl-wrapper tr');
function showField(code)
{
  $.getJSON('/field/ls',{door_code:code},function(d){
	  if(d.code){
		  return editError(d.msg)
	  }
	  var data = d.data;
	  $(".dragsort").html('');
	  if(Object.keys(data).length>0){
		  for(var i in data){
			  var newtr = fieldtpl.clone().attr('data-id',data[i].field_code) 
			  newtr.find('.en').html(data[i].field_enname)
			  newtr.find('.zh').html(data[i].field_zhname)
			  newtr.find('.td-save').addClass('td-edit').html('编辑').removeClass('td-save')
			  $(".dragsort").append(newtr)
		  }
	  }
	  addFieldTd()
	  $(".dragsort").dragsort({ dragSelector: ".en,.zh"})
	  if($("#field-modal:visible").length==0){
		  $("#field-modal").data('door_code',code).modal();
	  }
  })
}

function addFieldTd()
{
	if($(".dragsort tr").length==$(".dragsort tr[data-id]").length){
		$(".dragsort").append(fieldtpl.clone());
	}
}

$(".dragsort").on('click',".td-add",function(e){
	if($(".dragsort tr").length==$(".dragsort tr[data-id]").length){
		$(e.target).parents('tr').after(fieldtpl.clone());
	}
})

$(".dragsort").on('click',".td-del",function(){
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
		if($(".dragsort tr").length>1){
			t.parents('tr').remove();
		}else{
			showField($('#field-modal').data('door_code'))
		}
	})
})

$(".dragsort").on('click',".td-edit",function(){
	var tden = $(this).parent().siblings('.en');
	var tdzh = $(this).parent().siblings('.zh');
	tden.html(fieldtpl.find('.en input').clone().val(tden.html()))
	tdzh.html(fieldtpl.find('.zh input').clone().val(tdzh.html()))
	$(this).addClass('td-save').html('保存').removeClass('td-edit')
})

$(".dragsort").on('click',".td-save",function(){
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

var app = new Vue({
  el: '#portlet-wrapper',
  data: {
    row:{},
  },
  methods : {
	  clickCaret:function(pid,e){
		  if($('i.fa',e.target).hasClass('fa-caret-right')){
			  $('i.fa',e.target).removeClass('fa-caret-right').addClass('fa-caret-down');
		  }else{
			  $('i.fa',e.target).removeClass('fa-caret-down').addClass('fa-caret-right');
		  }
          $(e.target).parent().siblings('.pid-'+pid).toggle();
	  },
	  showDetail:function(code){
		  var t = this;
		  $.getJSON('/menu/detail',{door_code:code},function(d){
			  if(d.code){
				  return editError(d.msg);
			  }
			  t.row = d.data
			  $("#edit-modal").modal().find('.alert').hide();
		  })
	  },
	  showAdd:function(pid)
	  {
		  this.row = {door_parent:pid,need_auth:'true'}
		  $("#edit-modal").modal().find('.alert').hide();
	  },
	  edit:function(){
		  var t = this;
		  $.post('/menu/edit',t.row,function(d){
			  if(d.code){
				  return editError(d.msg);
			  }
			  $("#edit-modal").modal('hide');
			  window.location.href = location.href;
		  },'json')
	  },
	  del:function(code){
		  if(!confirm('确定删除？')){
			  return 
	      }
		  $.getJSON('/menu/del',{door_code:code},function(d){
			  window.location.href = location.href;
		  })
	  },
	  showField:function(code)
	  {
		  showField(code)
	  }
  }
})