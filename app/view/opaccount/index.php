<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div id="user-right" class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>人员列表  </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table id="user-list" class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>人员ID </th>
                            <th>人员代码 </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
// var option = {
// // 	language : admin.dtLang,
// 	serverSide: true,
// // 	paging:true,
//     ajax: {"url" : "/user/index",dataSrc:function(d){
//     	if(d.code){
//     		alert(d.msg);
//     		return [];
//     	}
//     	return d.data;
//     }},
//     columns: [
//         { data: "user_id" },
//         { data: "his_code" },
//     ],
//     "scrollInfinite": true,
//     "scrollCollapse": true,
//     scrollY:        200,
// //     scroller:       true,
// }
//https://datatables.net/extensions/scroller/examples/initialisation/server-side_processing.html
$("#user-list").dataTable({
	serverSide: true,
    ordering: false,
    searching: false,
    "ajax": {
        "url": "/opaccount/index",
        "type": "POST"
    },
    scroller: {
        loadingIndicator: true
    },
    scrollY: 600,
    "columns": [
        {"data": "id"}, 
        {"data": "name"}, 
    ]
});
// .on('xhr.dt', function ( e, settings, json, xhr ) {
//     var api = new $.fn.dataTable.Api(settings);
//     var info = api.page.info();
//     console.log(api.page.info());
// });
//var dt = $('#user-list').dataTable(option)
</script>
<?php gvar('js',ob_get_clean());?>