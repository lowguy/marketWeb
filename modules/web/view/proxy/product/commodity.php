<?php $this->piece('common/head.php',array('menu'=>$menu, 'selected_menu'=>'/proxy/product/index','market'=>$market,'currentMarket'=>$currentMarket));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active"><a href="/proxy/product/index">产品管理</a></span></li>
        <li class="active">
            <span class="active">
                可代理产品
            </span>
        </li>
    </ol>
    <div class="searchbox">
        <div class="pull-right">
            <form class="form-inline list-header pull-right" method="GET" >
                <div class="form-group">
                    <label for="parent">一级类别:</label>
                    <select class="form-control" id="parent" name="parent">
                        <?php
                        echo " <option value='0'>全部</option>";
                        foreach($categories as $key => $item){
                            if($item['category_id'] == $_GET['parent']){
                                echo "<option value='{$item['category_id']}' SELECTED>{$item['category_name']}</option>";
                            }else{
                                echo "<option value='{$item['category_id']}'>{$item['category_name']}</option>";
                            }
                        }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">二级类别:</label>
                    <select class="form-control" id="category" name="category" data-id="<?php echo $_GET['category']; ?>">
                        <option value='0'>全部</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">名称:</label>
                    <input type="text" class="form-control"  placeholder="请输入商品名称" name="title" value="<?php echo $_GET['title'];?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
        </div>
    </div>
    <div>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="col-lg-8">名称</th>
                <th class="col-lg-2">分类</th>
                <th class="col-lg-2">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($products)){?>
                <caption>暂无可代理产品</caption>
            <?php }else{ foreach($products as $v):?>
                <tr data-id="<?php echo $v['product_id'] ;?>">
                    <td><?php echo $v['title'] ;?></td>
                    <td><?php echo $v['category_name'];?></td>
                    <td>
                        <button class="btn-operate change-status" data-id="<?php echo $v['product_id'];?>">
                            <a href="javascript:void(0);" ><i class="icon-plus"></i>添加</a>
                        </button>
                    </td>
                </tr>
            <?php endforeach; }?>
            </tbody>
        </table>
        <?php $pagination->render();?>
    </div>
</div>
<?php $this->piece('common/foot.php');?>

