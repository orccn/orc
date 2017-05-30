<div class="col-md-4">
    <div class="portlet green box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>分析单元 
            </div>
        </div>
        <div class="portlet-body">
            <div id="unit-tree"> </div>
        </div>
    </div>
</div>

<?php ob_start();?>
<script type="text/javascript">

</script>
<?php gvar('js',ob_get_clean());?>