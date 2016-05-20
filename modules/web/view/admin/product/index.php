<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/product/index'));?>
<style>
    tr>td:first-child{
        position: relative;
    }
    tr>td:first-child>img{
        transition: .8s transform;
        transform: translateZ(0);
    }
    tr>td:hover{
        z-index: 9999;
    }
    tr>td:hover> img{
        transform: scale(12, 15) translateX(25px);
        -webkit-transform:scale(12, 15) translateX(25px);
        -moz-transform:scale(12, 15) translateX(25px);
        -ms-transform: scale(12, 15) translateX(25px);
        -o-transform: scale(12, 15) translateX(25px);
        transition: .8s transform;
    }
</style>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">商品管理</span></li>
    </ol>
    <div>
        <div class="pull-left">
            <div class="form-group">
                <a class="function-button" href="/admin/product/add">
                    <i class="icon-plus-sign"></i>
                    <span>添加</span>
                </a>
            </div>
        </div>
        <div class="pull-right">
            <form class="form-inline list-header">
                <div class="form-group">
                    <label for="parent">一级类别:</label>
                    <select class="form-control" id="parent" name="parent" data-id="<?php echo $_GET['parent']?>">
                        <option>无</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">二级类别:</label>
                    <select class="form-control" id="category" name="category" data-id="<?php echo $_GET['category']?>">
                        <option>无</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">名称:</label>
                    <input type="text" class="form-control"  placeholder="请输入商品名称" name="title">
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
                    <th>封面</th>
                    <th>名称</th>
                    <th>类别</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody
            <?php foreach($products as $v):?>
                <tr data-id="<?php echo $v['product_id'] ;?>">
                    <td><?php echo '<img src="/static/upload/product/'.$v["path"].'" width=25px height=25px>' ;?></td>
                    <td><?php echo $v['title'] ;?></td>
                    <td><?php echo $v['category_name'];?></td>
                    <td>
                        <button class="btn-operate change-status btn-edit">
                            <a href="/admin/product/edit?id=<?php echo $v['product_id'];?>" >修改</a>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $pagination->render();?>
    </div>
</div>
<?php $this->piece('common/foot.php');?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6H65OLKMDUXx7M8TYf4txfrG"></script>