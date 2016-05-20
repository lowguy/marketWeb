<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/category/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">分类管理</span></li>
    </ol>
    <div>
        <div class="form-group">
            <a class="function-button" href="add">
                <i class="icon-plus-sign"></i>
                <span>添加</span>
            </a>
        </div>
        <div>
            <table class="table categories-table">
                <thead>
                <tr> <th></th>  <th>分类</th> <th>操作</th> </tr>
                </thead>
                <tbody>
                <tr> <td></td>  <td></td> <td></td> </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->piece('common/foot.php');?>