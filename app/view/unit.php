<?php ob_start();?>

<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-3">
        <div class="portlet yellow-lemon box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Contextual Menu with Drag & Drop 
                </div>
            </div>
            <div class="portlet-body">
                <div id="tree_3" class="tree-demo"> </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>分析单元 </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable">
                    <thead>
                        <tr>
                            <th>单元名称 </th>
                            <th>单元编码 </th>
                            <th>单元类型 </th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arr as $v){?>
                        <tr id="unit-<?= $v['unit_code']?>" data-level="<?= $v['unit_level']?>" data-end="<?= $v['end_flag']?>">
                            <td><?= $v['unit_name']?></td>
                            <td><?= $v['unit_code']?></td>
                            <td><?= $v['unit_type']?></td>
                            <td></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<?php ob_start();?>
<script type="text/javascript">
$('#datatable>tbody>tr').each(function(){
	var str = '';
	var id = $(this).prop('id');
	var level = $(this).data('level');
	for(i=1;i<level;i++){ str += '&nbsp;&nbsp;&nbsp;';}
	if($(this).data('end')=='N'){
		str += '<i class="fa fa-caret-right">&nbsp;</i>';
		$('td:first',this).mouseover(function(){
			$(this).css('cursor','pointer');
		});
		$('td:first',this).click(function(){
			var isRight = $('i.fa',this).hasClass('fa-caret-right');
			if(isRight){
				$('i.fa',this).removeClass('fa-caret-right').addClass('fa-caret-down')
			}else{
				$('i.fa',this).removeClass('fa-caret-down').addClass('fa-caret-right')
			}
			$(this).parent().siblings('[id^="'+id+'"]').toggle('fast');
		});
	}
	$(this).find('td:first').prepend(str);
});

$("#tree_3").jstree({
    "core" : {
        "themes" : {
            "responsive": false
        }, 
        // so that create works
        "check_callback" : true,
        'data': [{
                "text": "Parent Node",
                "children": [{
                    "text": "Initially selected",
                    "state": {
                        "selected": true
                    }
                }, {
                    "text": "Custom Icon","icon" : "fa fa-file icon-state-warning"
                }, {
                    "text": "Initially open",
                    "state": {
                        "opened": true
                    },
                    "children": [
                        {"text": "Another node", "icon" : "fa fa-file icon-state-warning"}
                    ]
                }, {
                    "text": "Another Custom Icon",
                }, {
                    "text": "Disabled Node",
                    "state": {
                        "disabled": true
                    }
                }, {
                    "text": "Sub Nodes",
                    "children": [
                        {"text": "Item 1", "icon" : "fa fa-file icon-state-warning"},
                        {"text": "Item 2", "icon" : "fa fa-file icon-state-success"},
                        {"text": "Item 3", "icon" : "fa fa-file icon-state-default"},
                        {"text": "Item 4", "icon" : "fa fa-file icon-state-danger"},
                        {"text": "Item 5", "icon" : "fa fa-file icon-state-info"}
                    ]
                }]
            },
            "Another Node"
        ]
    },
    "types" : {
        "default" : {
            "icon" : "fa fa-folder icon-state-warning icon-lg"
        },
        "file" : {
            "icon" : "fa fa-file icon-state-warning icon-lg"
        }
    },
    "state" : { "key" : "demo2" },
    "plugins" : [ "contextmenu", "dnd", "state", "types" ]
});
</script>
<?php gvar('js',ob_get_clean());?>