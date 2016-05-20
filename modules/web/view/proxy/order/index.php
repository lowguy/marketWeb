<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/proxy/order/index','market'=>$market,'currentMarket'=>$currentMarket));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">订单管理</span></li>
        <li class="active">
            <span class="active">
                订单列表
            </span>
        </li>
    </ol>
    <div class="searchbox">
        <div class="pull-left">
            <div class="form-group">
<!--                <a class="function-button" href="commodity">-->
<!--                    <i class="icon-plus-sign"></i>-->
<!--                    <span>添加</span>-->
<!--                </a>-->
            </div>
        </div>
        <div class="pull-right">
            <form class="form-inline list-header">
                <div class="form-group">
                    <label for="s">订单状态:</label>
                    <select class="form-control" id="s" name="s">
                        <?php
                        $order_status = array(
                            array('id'=>'0','title'=>'已取消'),
                            array('id'=>'1','title'=>'未支付'),
                            array('id'=>'2','title'=>'已支付'),
                            array('id'=>'3','title'=>'派送中'),
                            array('id'=>'4','title'=>'已完成')
                        );
                        echo "<option value='-1'>全部</option>";
                        foreach($order_status as $key => $item){
                            if($item['id'] == $_GET['s']){
                                echo "<option value='{$item['id']}' SELECTED>{$item['title']}</option>";
                            }else{
                                echo "<option value='{$item['id']}'>{$item['title']}</option>";
                            }
                        }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">手机号:</label>
                    <input type="text" class="form-control" value="<?php echo $_GET['t'];?>" placeholder="请输入用户手机号" name="t">
                </div>
                <div class="form-group">
                    <label for="order_no">订单号:</label>
                    <input type="text" class="form-control" value="<?php echo $_GET['n'];?>" placeholder="请输入订单号" name="n">
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
                    <th>订单号</th>
                    <th>状态</th>
                    <th>金额/￥</th>
                    <th>派送员</th>
                    <th>派送地址</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($order as $key => $item){
                foreach($order_status as $v){
                    if($item['status'] == $v['id']){
                        $status = $v['title'];
                        break;
                    }
                }
echo <<<EOT
                <tr>
                    <td>{$item['order_no']}</td>
                    <td>{$status}</td>
                    <td>{$item['amount']}</td>
                    <td>{$item['phone']}</td>
                    <td>{$item['address']} <i class="icon-phone-sign"></i>{$item['user_phone']}</td>
                    <td>
                        <button class="btn-operate change-status">
                            <a href="/proxy/order/detail?id={$item['order_id']}" >详情</a>
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