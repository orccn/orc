<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div id="user-right" class="<?=config('portletClass')?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>运营核算分析  </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table id="user-list" class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <?=$tableHeader?>
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
var option = {
	language : admin.dtLang,
	serverSide: true,
	ordering: true,
    searching: false,
    ajax: function ( p, callback, settings ) {
		$.getJSON('/opaccount/index',p,function(d){
			if(d.code){
	    		alert(d.msg);
	    		return [];
	    	}
			callback( {
                draw: p.draw,
                data: d.data,
                recordsTotal: d.all_count,
                recordsFiltered: d.filter_count
            } );
		})
    },
    scroller: {
        loadingIndicator: true
    },
    scrollY: 600,
    "columns": <?=$tableColumns?>
}
var dt = $('#user-list').dataTable(option)

</script>
<?php gvar('js',ob_get_clean());?>