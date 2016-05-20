<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/market/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li><a href="/admin/market/index">市场管理</a></li>
        <li class="active">
            <span class="active">
                <?php if($market):?>编辑<?php else:?>添加<?php endif;?>
            </span>
        </li>
    </ol>
    <form class="form-horizontal market-form" method="post">
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2"><strong>地区:</strong></label>
            <div class="col-lg-2">
                <select <?php if($market):?>disabled="disabled"<?php endif;?> data-value="<?php echo $market['city'];?>" class="form-control" name="city">
                    <option value="">
                        城市
                    </option>
                </select>
            </div>
            <div class="col-lg-2">
                <select <?php if($market):?>disabled="disabled"<?php endif;?> data-value="<?php echo $market['district'];?>" class="form-control" name="district">
                    <option value="">
                        区/县
                    </option>
                </select>
            </div>
        </div>
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2 for="select_area"><strong>配送区域:</strong></label>
            <div class="col-lg-2">
                <button style="width:50%;" id="select_area" type="button" class="form-control btn-primary">地图</button>
                <input value="<?php echo $market['market_id'];?>" readonly="readonly" class="form-control hidden"  type="text" name="market_id"/>
                <input value="<?php echo $market['boundaries'];?>" readonly="readonly" class="form-control hidden"  type="text" name="boundaries"/>
                <input value="<?php echo $market['free_area'];?>" readonly="readonly" class="form-control hidden"  type="text" name="area"/>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-1 col-lg-offset-5">
                <button id="js_submit" type="button" class="form-control btn-primary submit"><?php if($market):?>更新<?php else:?>添加<?php endif;?></button>
            </div>
        </div>

    </form>
</div>
<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">
                    配送区域设置
                </h4>
                <div>
                    <small style="color:red;">1. 红色虚线为行政区域边界.</small>
                    <br />
                    <small style="color:green;">2. 绿色区域为免费配送区，拖动边界上的小方块可以改变形状, 拖动红色标记物可以拖动整个区域, 区域内点击右键可以选择配送的范围.</small>
                </div>
            </div>
            <div class="modal-body no-padding">
                <div id="map"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button id="js_area_confirm" type="button" class="btn btn-primary">完成</button>
            </div>
        </div>
    </div>
</div>
<?php $this->piece('common/foot.php');?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6H65OLKMDUXx7M8TYf4txfrG"></script>
