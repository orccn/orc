<?php ob_start();?>

<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>功能模块</div>
                <div class="tools"> </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                        <label class="btn btn-transparent dark btn-outline btn-circle btn-sm active">
                            <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                        <label class="btn btn-transparent dark btn-outline btn-circle btn-sm">
                            <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="datatable table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>功能名称 </th>
                            <th>功能编码 </th>
                            <th>功能路径</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menuList as $v){?>
                        <tr id="door-<?= $v['door_code']?>" data-level="<?= $v['door_level']?>" data-end="<?= $v['end_flag']?>">
                            <td><?= $v['door_name']?></td>
                            <td><?= $v['door_code']?></td>
                            <td><?= $v['sys_windows']?></td>
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
var option = {
	paging:false,
	ordering:false,
}
var dt = $('.datatable').initDT(option)

$('.datatable>tbody>tr').each(function(){
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
</script>
<?php gvar('js',ob_get_clean());?>