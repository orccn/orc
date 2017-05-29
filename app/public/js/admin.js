var admin = {
	'dtLang' : {
        "aria": {
            "sortAscending": ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        },
        "emptyTable": "没有符合条件的记录",
        "info": "显示 _START_ 到 _END_ 共 _TOTAL_ 条",
        "infoEmpty": "没有数据",
        "infoFiltered": "(从1到_MAX_条记录中筛选)",
        "lengthMenu": "_MENU_ 条",
        "search": "搜索:",
        "zeroRecords": "没有匹配的记录"
    }	
}
if($('#datatable').length){
	$('#datatable').dataTable({
	    "language": admin.dtLang,
	    "buttons":[],
	    "ordering" : false,
	    "paging" : false,
//	    "order": [
//	        [0, 'asc']
//	    ],
//	    "lengthMenu": [
//	        [5, 10, 15, 20, -1],
//	        [5, 10, 15, 20, "All"]
//	    ],
//	    "pageLength": 10,
//	    "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
	});
}
function JQPost(url,data,fn){$.post(url,data,fn,'json');}

