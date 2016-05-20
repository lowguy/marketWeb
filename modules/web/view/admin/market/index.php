<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/market/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">市场管理</span></li>
    </ol>
    <div>
        <div class="pull-left">
            <div class="form-group">
                <a class="function-button" href="/admin/market/add">
                    <i class="icon-plus-sign"></i>
                    <span>添加</span>
                </a>
            </div>
        </div>
        <div class="pull-right">
            <form class="form-inline list-header">
                <div class="form-group">
                    <label for="role">城市:</label>
                    <select class="form-control" id="city" name="city">
                        <option value="0">请选择</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <table  class="table table-hover">
        <thead>
        <tr>
            <th>
                城市
            </th>
            <th>
                区/县
            </th>
            <th>
                操作
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($markets as $market):?>
            <tr data-id="<?php echo $market['market_id'] ;?>">
                <td class="js-city"><?php echo $market['city'] ;?></td>
                <td class="js-district"><?php echo $market['district'];?></td>
                <td>
                    <a href="/admin/market/detail?id=<?php echo $market['market_id'];?>">详情</a>
                    <a href="/admin/market/edit?id=<?php echo $market['market_id'];?>">编辑</a>
                    <a class="js-auth cursor-pointer" data-id="<?php echo $market['market_id'] ;?>">授权</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php $pagination->render();?>
</div>
<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">
                    市场管理人员设置
                </h4>
            </div>
            <div class="modal-body center">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="first-disabled" class="col-sm-2 control-label">市场管理人员</label>
                        <div class="col-sm-10">
                            <input type="text" class="hidden" value="" name="market">
                            <select class="selectpicker" >
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button id="js_market_user_confirm" type="button" class="btn btn-primary">完成</button>
            </div>
        </div>
    </div>
</div>
<?php $this->piece('common/foot.php');?>