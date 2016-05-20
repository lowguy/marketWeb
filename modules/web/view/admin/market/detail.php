<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/market/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li><a href="/admin/market/index">市场管理</a></li>
        <li class="active">
            <span class="active">
                详情
            </span>
        </li>
    </ol>
    <form class="form-horizontal market-form" method="post">
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2"><strong>地区:</strong></label>
            <div class="col-lg-2">
                <p class="form-control-static"><?php echo $market['city'];?></p>
                <input name="city" value="<?php echo $market['city'];?>"  readonly="readonly" class="form-control hidden"  type="text">
            </div>
            <div class="col-lg-2">
                <p class="form-control-static"><?php echo $market['district'];?></p>
                <input name="district" value="<?php echo $market['district'];?>"  readonly="readonly" class="form-control hidden"  type="text">
            </div>
        </div>
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2 for="select_area"><strong>配送区域:</strong></label>
            <div class="col-lg-4">
                <div>
                    <small style="color:red;">1. 红色虚线为行政区域边界.</small>
                    <br />
                    <small style="color:green;">2. 绿色区域为免费配送区..</small>
                </div>
                <div id="map"></div>
                <input value="<?php echo $market['free_area'];?>" readonly="readonly" class="form-control hidden"  type="text" name="area"/>
            </div>
        </div>
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2"><strong>联系方式:</strong></label>
            <div class="col-lg-2">
                <p class="form-control-static"><?php echo empty($market['phone'])?"未授权":$market['phone'];?></p>
            </div>
        </div>
    </form>
</div>
<?php $this->piece('common/foot.php');?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6H65OLKMDUXx7M8TYf4txfrG"></script>
