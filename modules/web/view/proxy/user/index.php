<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/proxy/user/index','market'=>$market,'currentMarket'=>$currentMarket));?>
    <div class="col-lg-10">
        <ol class="breadcrumb">
            <li class="active"><span class="active">商户管理</span></li>
        </ol>
        <div>
            <div class="pull-right">
                <form class="form-inline list-header" style="height: 40px;">
                    <div class="form-group">
                        <label for="role">角色:</label>
                        <select class="form-control" id="role" name="role">
                            <?php foreach($roles as $k=>$v):?>
                                <option
                                    <?php if($v['role_id'] == intval($_GET['role'])):?>selected="selected"<?php endif;?>
                                    value="<?php echo $v['role_id'];?>"><?php echo $v['title'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">状态:</label>
                        <select class="form-control" id="role" name="status">
                            <?php foreach($status_array as $k=>$v):?>
                                <option
                                    <?php if($v['status'] == intval($_GET['status'])):?>selected="selected"<?php endif;?>
                                    value="<?php echo $v['status'];?>"><?php echo $v['title'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">手机:</label>
                        <input value="<?php echo $_GET['phone'];?>" class="form-control" type="text" name="phone" id="phone" />
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
                    手机
                </th>
                <th>
                    角色
                </th>
                <th>
                    状态
                </th>
                <th>
                    操作
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user):?>
                <tr>
                    <td><?php echo $user['phone'] ;?></td>
                    <td><?php echo $user['role_names'] ;?></td>
                    <td><?php echo $status_array[++$user['status']]['title'];?></td>
                    <td>
                        <button class="btn-operate change-status">
                            <a href="<?php echo "auth?uid=".$user['user_id']."&status=".$user['status'] ;?>">审核</a>
                        </button>
<?php
if($user['status'] == 2 && $user['role_id'] == 100){
echo <<<EOT
                        <button class="btn-operate change-status">
                            <a href="/proxy/product/userProducts?uid={$user['user_id']}">商品</a>
                        </button>
EOT;
}
?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $pagination->render();?>
    </div>
<?php $this->piece('common/foot.php');?>