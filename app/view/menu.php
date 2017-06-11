<?php ob_start();?>
<?php gvar('css',ob_get_clean());?>
<div id="portlet-wrapper" class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box <?=config('portletClass')?>">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-globe"></i>功能模块
				</div>
				<div class="actions">
                    <a href="javascript:;" class="<?=config('titleAddClass')?>" @click="showAdd(0)">
                        <i class="fa fa-plus"></i> 添加
                    </a>
                </div>
			</div>
			<div class="portlet-body">
				<table
					class="datatable table table-striped table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th>功能名称</th>
							<th>功能编码</th>
							<th>上级功能</th>
							<th>功能路径</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
                        <?php foreach ($sortMenu as $v){?>
                        <tr class="pid-<?= $v['door_parent']?>" data-level="<?= $v['door_level']?>" data-end="<?= $v['is_leaf']?>">
							<td @click="clickCaret(<?= $v['door_code']?>,$event)"><?= $v['door_name']?></td>
							<td><?= $v['door_code']?></td>
							<td><?= $v['door_parent'] ? $menuList[$v['door_parent']]['door_name'] : '顶级'?></td>
							<td><?= $v['door_url']?></td>
							<td>
							     <a href="javascript:;" class="<?=config('tdDelClass')?>" @click="del(<?= $v['door_code']?>)">删除</a>
							     <a href="javascript:;" class="<?=config('tdDetailClass')?>" @click="showDetail(<?= $v['door_code']?>)">详情</a>
							     <a href="javascript:;" v-if="!<?=intval($v['door_parent'])?>" class="<?=config('tdAddClass')?>" @click="showAdd(<?= $v['door_code']?>)">添加</a>
							     <a href="javascript:;" v-if="<?=intval($v['has_field'])?>" class="td-field btn btn-xs purple btn-outline" @click="showField(<?= $v['door_code']?>)">字段</a>
							</td>
						</tr>
                        <?php }?>
                    </tbody>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
	<div id="edit-modal" class="modal" data-width="760" data-keyboard="true" data-attention-animation="false">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    		<h4 class="modal-title">添加/修改功能</h4>
    	</div>
    	<div class="modal-body">
    		<form class="form-horizontal" role="form">
    			<div class="form-group">
    				<label class="col-md-3 control-label">功能名称</label>
    				<div class="col-md-9">
    					<input type="text" class="form-control" v-model="row.door_name">
    				</div>
    			</div>
    			<div class="form-group">
    				<label class="col-md-3 control-label">功能路径</label>
    				<div class="col-md-9">
    					<input type="text" class="form-control" v-model="row.door_url">
    				</div>
    			</div>
    			<div class="form-group">
    				<label class="col-md-3 control-label">上级功能</label>
    				<div class="col-md-9">
    					<select id="single" class="form-control select2" v-model="row.door_parent">
    						<option value="0">顶级</option>
                            <?php foreach ($menuList as $v){ if ($v['door_parent']) continue;?>
                            <option value="<?=$v['door_code']?>"><?=$v['door_name']?></option>
                            <?php }?>
                        </select>
    				</div>
    			</div>
    			<div class="form-group">
    				<label class="col-md-3 control-label"></label>
    				<div class="col-md-9">
    					<label class="mt-checkbox mt-checkbox-outline"> 
    					   <input type="checkbox" v-model="row.is_menu"> 设为菜单 <span></span>
    					</label> 
    					<label class="mt-checkbox mt-checkbox-outline"> 
    					   <input type="checkbox" v-model="row.need_auth"> 验证权限 <span></span>
    					</label>
    					<label class="mt-checkbox mt-checkbox-outline"> 
    					   <input type="checkbox" v-model="row.has_field"> 字段控制 <span></span>
    					</label>
    				</div>
    			</div>
    		</form>
    	</div>
    	<div class="modal-footer">
    		<button type="button" data-dismiss="modal"
    			class="btn btn-outline dark">取消</button>
    		<button type="button" class="btn green" @click="edit">提交</button>
    	</div>
    </div>
</div>

<div id="field-modal" class="modal" data-width="760" data-keyboard="true" data-attention-animation="false">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">添加/修改字段</h4>
	</div>
	<div class="modal-body">
	   <table class="tpl-wrapper display-hide">
            <tr>
            	<td width="35%" class="en"><input type="text" placeholder="字段英文名"></td>
            	<td width="35%" class="zh"><input type="text" placeholder="字段中文名"></td>
            	<td>
            	     <a href="javascript:;" class="<?=config('tdDelClass')?>">删除</a>
            	     <a href="javascript:;" class="<?=config('tdSaveClass')?>">保存</a>
            	</td>
            </tr>
        </table>
		<table class="dragsort table table-striped table-bordered table-hover table-condensed"></table>
	</div>
</div>


<?php ob_start();?>
<script src="/js/jquery.dragsort-0.5.2.min.js" type="text/javascript" ></script>
<script src="/js/menu.js" type="text/javascript" ></script>
<?php gvar('js',ob_get_clean());?>