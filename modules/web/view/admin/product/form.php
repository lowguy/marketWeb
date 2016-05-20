<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/product/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><a href="/admin/product/index">商品管理</a></li>
        <li class="active">
            <span class="active">
                <?php if($product):?>编辑<?php else:?>添加<?php endif;?>
            </span>
        </li>
    </ol>
    <form class="form-horizontal product-form" method="post">
        <div class="form-group">
            <label for="cover" class="col-lg-2 col-lg-offset-2 control-label">封面</label>
            <div class="col-sm-4">
                <input type="hidden" name="uri" value="<?php if($product){ echo "/static/upload/product/".$product["path"];}?>">
                <input type="hidden" name="product_id" value="<?php if($product){ echo $product["product_id"];}?>">
                <div class="col-lg-12 btn-file-color btn-file-max-height files">
                    <input type="file" class="form-control img-file-max" name="icon_uri" id="icon_uri" placeholder="请添加图标" data-url="/admin/upload/uploadImg" multiple>
                    <span class="btn-add-max"><i class="icon-cloud-download icon-4"></i></span>
                    <?php if($product){echo '<div class="images_zone_max"><img src="/static/upload/product/'.$product["path"].'" /><a href="javascript:;"><i class="icon-trash icon-4"></i></a></div>';}?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-lg-2 col-lg-offset-2 control-label">名称</label>
            <div class="col-lg-4">
                <input type="text" class="form-control"  placeholder="请添加商品名称" name="title" value="<?php if(!empty($product)){ echo $product['title'];} ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="slogan" class="col-lg-2 col-lg-offset-2 control-label">广告语</label>
            <div class="col-lg-4">
                <textarea class="form-control" rows="3" id="slogan" name="slogan" style="resize: none" placeholder="请为产品添加广告语"><?php if(!empty($product)){ echo $product['slogan'];} ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-lg-2 col-lg-offset-2 control-label">类别</label>
            <div class="col-lg-4">
                <div class="col-lg-6" style="padding-left:0px;">
                    <select class="form-control" name="parent"  data-id="<?php if(!empty($product)){ echo $product['start'];}?>">
                        <option value="">无</option>
                    </select>
                </div>
                <div class="col-lg-6" style="padding-right:0px;">
                    <select class="form-control" name="category"  data-id="<?php if(!empty($product)){ echo $product['end'];}?>">
                        <option value="">无</option>
                    </select>
                </div>

            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4 col-lg-offset-4">
                <button id="js_submit" type="submit" class="form-control btn-primary submit"><?php if($product):?>更新<?php else:?>添加<?php endif;?></button>
            </div>
        </div>

    </form>
</div>
<?php $this->piece('common/foot.php');?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6H65OLKMDUXx7M8TYf4txfrG"></script>