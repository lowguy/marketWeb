<?php $this->piece('common/head.php',array('menu'=>$menu, 'selected_menu'=>'/proxy/product/index','market'=>$market,'currentMarket'=>$currentMarket));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active"><a href="javascript:history.back()">商户产品</a></span></li>
        <li class="active">
            <span class="active">
                修改
            </span>
        </li>
    </ol>
    <div class="searchbox">
        <form class="form-horizontal edit-form" method="post">
            <div class="row">
                <div class="pull-left col-lg-4">
                    <div class="col-lg-12">
                        <img src="/static/upload/product/<?php echo $product['path'];?>">
                    </div>
                </div>
                <div class="pull-right col-lg-8">
                    <div class="col-lg-12">
                        <h2><?php echo $product['title'];?></h2>
                    </div>
                    <div class="col-lg-12">
                        <p class="form-control-static"><?php echo $product['slogan'];?></p>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-lg-2 control-label">单价</label>
                        <div class="col-sm-4">
                            <input value="<?php echo $product['product_id'];?>" type="hidden" name="product_id" />
                            <input value="<?php echo $product['price'];?>" placeholder="请设置单价" class="form-control"  type="text" name="price" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inprice" class="col-lg-2 control-label">净价</label>
                        <div class="col-sm-4">
                            <input value="<?php echo $product['inprice'];?>" placeholder="请设置净价" class="form-control"  type="text" name="inprice" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-lg-2 control-label">时间</label>
                        <div class="col-sm-4">
                            <input value="<?php echo $product['m_p_start'];?>" class="form-control"  type="hidden" name="start" />
                            <input value="<?php echo $product['m_p_end'];?>" class="form-control"  type="hidden" name="end" />
                            <p class="form-control-static">开始:<start></start>--结束:<end></end><label class="pull-right"><input type="checkbox" name="isset"><tip>设置</tip></label></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-lg-2 control-label"></label>
                        <div class="col-sm-4">
                            <div id="slider-range" class="ui-slider-handle"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activity" class="col-lg-2 control-label">活动</label>
                        <div class="col-sm-4">
                            <p class="form-control-static"><input type="checkbox" value="<?php echo $product['activity'];?>" name="activity" <?php echo $product['activity'] != 0 ? 'checked': '';?>><tip>设置</tip></p>
                        </div>
                    </div>
                    <div class="form-group activity hidden">
                        <label for="tag" class="col-lg-2 control-label">活动类型</label>
                        <div class="col-sm-4">
                            <label class="checkbox-inline" style="padding-left: 0px;">
                                <input type="radio" name="tag" value="1" <?php echo $product['activity'] == 1||empty($product['activity']) ? 'checked': '';?>> 爆款特卖
                            </label>
                            <label class="checkbox-inline" style="padding-left: 0px;">
                                <input type="radio" name="tag" value="2" <?php echo $product['activity'] == 2 ? 'checked': '';?>> 天天低价
                            </label>
                        </div>
                    </div>
                    <div class="form-group activity hidden">
                        <label for="discount" class="col-lg-2 control-label">活动单价</label>
                        <div class="col-sm-4">
                            <input value="<?php echo $product['discount'];?>" placeholder="请设置活动单价" class="form-control validate-ignore"  type="text" name="discount" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-2">
                            <button type="submit" class="form-control btn-primary">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $this->piece('common/foot.php');?>
