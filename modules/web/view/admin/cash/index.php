<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/cash/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">提现申请</span></li>
    </ol>
    <div>
        <div class="pull-right" style="height: 3rem;">
            <form class="form-inline list-header">
                <div class="form-group">
                    <label for="status">状态:</label>
                    <select class="form-control" id="role" name="status">
                        <?php foreach($status as $id=>$value):?>
                            <option
                                <?php if($id == $_GET['status']):?>selected="selected"<?php endif;?>
                                value="<?php echo $id;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">用户:</label>
                    <input value="<?php echo $_GET['phone'];?>" class="form-control" placeholder="输入用户手机号" type="text" name="phone" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </form>
        </div>
    </div>
    <table  class="table table-hover">
        <thead>
            <tr>
                <th>
                    用户
                </th>
                <th>
                    金额
                </th>
                <th>
                    兑换类型
                </th>
                <th>
                    账户
                </th>
                <th>
                    姓名
                </th>
                <th>
                    手机
                </th>
                <th>
                    账户类型
                </th>
                <th>
                    状态
                </th>
                <th>
                    审核时间
                </th>
                <th>
                    备注
                </th>
                <th>
                    操作
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item):?>
            <tr data-id="<?php echo $item['id'];?>">
                <td class="col-md-1"><?php echo $item['user'];?></td>
                <td class="col-md-1"><?php echo $item['amount'];?></td>
                <td class="col-md-1"><?php echo ($item['type'] == 1) ? "积分" : "现金";?></td>
                <td class="col-md-2"><?php echo $item['account'];?></td>
                <td class="col-md-1"><?php echo $item['name'];?></td>
                <td class="col-md-1"><?php echo $item['phone'];?></td>
                <td class="col-md-1"><?php echo $item['bank'];?></td>
                <td class="col-md-1"><?php echo ($item['status'] == 1) ? "通过" : (($item['status'] == 2) ? "拒绝" : "审核中");?></td>
                <td class="col-md-1"><?php echo $item['created_at'];?></td>
                <td class="col-md-1"><?php echo $item['comment'];?></td>
                <td class="col-md-1">
                    <?php if(0 == $item['status']){?>
                    <button class="btn-operate apply-status" >
                        <a>审核</a>
                    </button>
                    <?php }else{ ?>
                    <button class="btn-operate" >
                        <a>已审核</a>
                    </button>
                    <?php } ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php $pagination->render();?>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    提现审核
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <input type="hidden" name="apply_id"  value="" >
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="comment" rows="2" placeholder="请输入备注信息" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">状态</label>
                        <div class="col-sm-8">
                            <label class="checkbox-inline">
                                <input type="radio" name="status"  value="1" checked> 通过
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="status"  value="2"> 拒绝
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary apply-primary">
                    确认
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<?php $this->piece('common/foot.php');?>
