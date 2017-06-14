var admin = {
	dtLang : {
		"aria" : {
			"sortAscending" : ": activate to sort column ascending",
			"sortDescending" : ": activate to sort column descending"
		},
		"emptyTable" : "没有符合条件的记录",
		"info" : "显示 _START_ 到 _END_ 共 _TOTAL_ 条",
		"infoEmpty" : "没有数据",
		"infoFiltered" : "(从1到_MAX_条记录中筛选)",
		"lengthMenu" : "_MENU_ 条",
		"search" : "搜索:",
		"zeroRecords" : "没有匹配的记录"
	}
}
var dblclick = {
	timer : null,
	lastTime : 0,
	interval : 300,
	check : function() {
		var isdbl = new Date().getTime() - this.lastTime < this.interval
		if (isdbl) {
			clearTimeout(this.timer)
		}
		return isdbl
	},
	callback : function(fn){
		dblclick.lastTime = new Date().getTime();
		dblclick.timer = setTimeout(fn, dblclick.interval)
	}
}
$.fn.initDT = function(option) 
{
	var d = {
		"language" : admin.dtLang,
	}
	option = option || {}
	option.buttons = option.buttons || []
	// 分页
	if (option.need_page === false) {
		d.paging = false
	} else {
		paging = typeof (option.need_page) === 'number' ? option.need_page : 10
		d.lengthMenu = [ [ 5, 10, 15, 20, -1 ], [ 5, 10, 15, 20, "All" ] ]
		d.pageLength = 10
		d.dom = "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>" // horizobtal
	}
	// 排序
	if (option.need_order === false) {
		d.ordering = false
	} else {
		d.order = [ [ 0, 'asc' ] ]
	}
	for (i in option){
		d[i] = option[i]
	}
	return this.dataTable(d)
}
$.fn.initJSTree = function(option,url) 
{
	var d = {
		"core" : {
			"themes" : {
				"responsive" : false
			},
			// so that create works
			"check_callback" : true,
			'data' : {
				'url' : url,
			}
		},
		"types" : {
			"default" : {
				"icon" : "fa fa-folder icon-state-info icon-lg"
			},
			"file" : {
				"icon" : "fa fa-file icon-state-info icon-lg"
			}
		},
		"state" : {
			"key" : "demo2"
		},
		"plugins" : ["state", "types"]
	}
	option = option || {}
	for (i in option){
		d[i] = option[i]
	}
	return this.jstree(d)
}
function editError(msg)
{
	var str = '<div class="alert alert-danger display-hide"><button class="close" data-close="alert"></button><span></span></div>';
	if($('.alert','.modal-body').length==0){
		$('.modal-body').prepend(str);
	}
	$('.alert','.modal-body').show().find('span').html(msg);
}
$('li.open','.page-sidebar-menu').parents('li').addClass('active open').find('a').append('<span class="selected"></span>');
