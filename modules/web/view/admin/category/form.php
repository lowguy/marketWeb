<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/category/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><a href="/admin/category/index">分类管理</a></li>
        <li class="active">
            <span class="active">
                <?php if($category):?>编辑<?php else:?>添加<?php endif;?>
            </span>
        </li>
    </ol>
    <form class="form-horizontal category-form" method="post">
        <input type="hidden" name="category_id" value="<?php if($category){ echo $category['category_id'];}?>">
        <div class="form-group">
            <label for="parent" class="col-lg-2 col-lg-offset-2 control-label">父级分类</label>
            <div class="col-sm-4">
                <select <?php if($category):?>disabled="disabled"<?php endif;?> data-id="<?php echo $category['start'];?>" class="form-control" name="parent">
                    <option value="0">
                        无
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="category_name" class="col-lg-2 col-lg-offset-2 control-label">分类名称</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="category_name" placeholder="请添加分类名称" value="<?php if($category){echo $category['category_name'];}?>" >
            </div>
        </div>
        <div class="form-group">
            <label for="category_mark" class="col-lg-2 col-lg-offset-2 control-label">备注</label>
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="mark" <?php if($category['mark'] == 1){ echo "checked";?> value="1" <?php } ?> value="1"> 此分类下的商品是否需要备注
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="icon" class="col-lg-2 col-lg-offset-2 control-label">图标</label>
            <div class="col-sm-4">
                <input type="hidden" name="uri" value="<?php if($category){ echo "/static/upload/category/".$category["category_id"].".png";}?>">
                <div class="btn-file btn-file-color files">
                    <input type="file" class="form-control img-file" name="icon_uri" id="icon_uri" placeholder="请添加图标" data-url="/admin/upload/uploadImg" multiple>
                    <span class="btn-add"><i class="icon-cloud-download icon-2"></i></span>
                    <?php if($category){echo '<div class="images_zone"><img src="/static/upload/category/'.$category["category_id"].'.png" /><a href="javascript:;"><i class="icon-trash icon-2"></i></a></div>';}?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4 col-lg-offset-4">
                <button id="js_submit" type="submit" class="form-control btn-primary submit"><?php if($category):?>更新<?php else:?>添加<?php endif;?></button>
            </div>
        </div>

    </form>
</div>
<?php $this->piece('common/foot.php');?>
