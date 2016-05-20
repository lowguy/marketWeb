<?php  $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/proxy/user/index','market'=>$market,'currentMarket'=>$currentMarket,'isShow'=>1));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li><a href="/proxy/user/index">商户管理</a></li>
        <li class="active"><span class="active">审核</span></li>
    </ol>
    <div class="col-lg-4">
        <div  class="form-group">
            <label class="control-label col-lg-4" for="select_area"><strong>配送区域:</strong></label>
            <div class="col-lg-8">
                <div>
                    <small style="color:red;">1. 红色虚线为行政区域边界.</small><small style="color:green;">2. 绿色区域为免费配送区..</small>
                </div>
            </div>
        </div>
        <div  class="form-group">
            <div class="col-lg-12">
                <div id="map"></div>
                <input name="city" value="<?php echo $market['city'];?>"  readonly="readonly" class="form-control hidden"  type="text">
                <input name="district" value="<?php echo $market['district'];?>"  readonly="readonly" class="form-control hidden"  type="text">
                <input value="<?php echo $market['free_area'];?>" readonly="readonly" class="form-control hidden"  type="text" name="area"/>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <form class="form-horizontal user-form" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user['0']['user_id']?>" >
            <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>市场:</strong></label>
                <div class="col-lg-10">
                    <p class="form-control-static"><?php echo $market['city'].$market['district'];?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>手机:</strong></label>
                <div class="col-lg-10">
                    <p class="form-control-static"><?php echo $user['0']['phone']?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>余额:</strong></label>
                <div class="col-lg-10">
                    <p class="form-control-static"><?php echo $user['0']['money']?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>积分:</strong></label>
                <div class="col-lg-10">
                    <p class="form-control-static"><?php echo $user['0']['score']?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>角色:</strong></label>
                <div class="col-lg-10">
                    <p class="form-control-static"><?php echo $user['0']['role_names']?></p>
                </div>
            </div>
            <?php
            if($user['0']['role_id'] == 100){
echo <<<EOT
                <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>地址:</strong></label>
                <div class="col-lg-6">
                    <input value="{$user['0']['address']}" class="form-control validate-ignore" type="text" name="address" />
                    <input value="{$user['0']['lng']}" class="form-control validate-ignore" type="hidden" name="lng" />
                    <input value="{$user['0']['lat']}" class="form-control validate-ignore" type="hidden" name="lat" />
                </div>
            </div>
EOT;
            }
            ?>
            <div class="form-group">
                <label class="control-label col-lg-2" for="phone"><strong>审核:</strong></label>
                <div class="col-lg-10">
                    <label class="checkbox-inline" style="padding-left: 0px;">
                        <input type="radio" name="status" value="1" class="validate-ignore" <?php  echo $user['0']['status'] == 1 ? 'checked': '';?>> 启用
                    </label>
                    <label class="checkbox-inline">
                        <input type="radio" name="status" value="2" class="validate-ignore" <?php  echo $user['0']['status'] == 2 ? 'checked': '';?>> 禁止
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="form-control btn-primary js-submit">保存</button>
                </div>
            </div>
        </form>
    </div>

</div>
<?php $this->piece('common/foot.php');?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6H65OLKMDUXx7M8TYf4txfrG"></script>
