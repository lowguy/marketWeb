<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/proxy/product/index','market'=>$market,'currentMarket'=>$currentMarket));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">产品管理</span></li>
        <li class="active">
            <span class="active">
                市场产品
            </span>
        </li>
    </ol>
    <div class="searchbox">
        <div class="pull-left">
            <div class="form-group">
            </div>
        </div>
        <div class="pull-right">
            <form class="form-inline list-header">
                <div class="form-group">
                    <label for="market_parent">一级类别:</label>
                    <select class="form-control" id="market_parent" name="market_parent">
                        <?php
                        echo "<option value='0'>全部</option>";
                        foreach($categories as $key => $item){
                            if($item['category_id'] == $_GET['market_parent']){
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
                    <?php if($_GET['uid']){?><input type="hidden"  value="<?php echo $_GET['uid'];?>"  name="uid"><?php } ?>
                    <input type="text" class="form-control" value="<?php echo $_GET['title'];?>" placeholder="请输入商品名称" name="title">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
        </div>
    </div>
    <div>
        <table id="categories" class="table table-hover">
            <thead>
                <tr>
                    <th>名称</th>
                    <th>类别</th>
                    <th>价格/￥</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $user_id = $_GET['uid'];
            foreach($products as $key => $item){
echo <<<EOT
                <tr>
                    <td>{$item['title']}</td>
                    <td>{$item['category_name']}</td>
                    <td>{$item['price']}</td>
                    <td>
                        <button class="btn-operate change-status btn-apr-user" data-id="{$item['product_id']}" data-user="{$user_id}">
                            <a href="javascript:void(0);" ><i class="icon-plus"></i>添加</a>
                        </button>
                    </td>
                </tr>
EOT;
            }
            ?>
            </tbody>
        </table>
        <?php $pagination->render();?>
    </div>
</div>

<?php $this->piece('common/foot.php');?>