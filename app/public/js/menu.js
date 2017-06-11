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
		str += '<i class="fa fa-caret-right">&nbsp;</i>';
	}
	$(this).find('td:first').css('cursor','pointer').prepend(str);
});

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