<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/proxy/category/index','market'=>$market,'currentMarket'=>$currentMarket));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">分类管理</span></li>
    </ol>
    <div>
        <div>
            <div class="pull-left">
                <div class="form-group">
                    <a class="function-button" href="javascript:void(0);">
                        <i class="icon-plus-sign"></i>
                        <span>保存</span>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <table id="categories" class="table">
                <caption class="bg-warning text-primary" style="text-indent: 1em;"><i class="icon-lightbulb"></i> 请为首页选择一级分类<i class="icon-lightbulb"></i> 拖动分类可自由排序</caption>
                <tbody>
                    <tr> <td></td> <td></td> </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->piece('common/foot.php');?>