<?php ob_start();?>
<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>分析单元 </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th> 单元编码 </th>
                            <th> 单元名称 </th>
                            <th> 拼音码 </th>
                            <th> 单元类型 </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arr as $v){?>
                        <tr>
                            <td> <?= $v['unit_code']?> </td>
                            <td> <?= $v['unit_name']?> </td>
                            <td> <?= $v['spell_code']?> </td>
                            <td> <?= $v['unit_type']?> </td>
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
</script>
<?php gvar('js',ob_get_clean());?>