<?php ob_start();?>
<?php gvar('css',ob_get_clean());?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>用户详情</div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>选项</th>
                            <th>内容</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> 当前用户</td>
                            <td> <?=$_SESSION['user']['name'] ?></td>
                        </tr>
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