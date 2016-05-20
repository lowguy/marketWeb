<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/order/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">订单管理</span></li>
    </ol>
    <div>
        <div class="pull-left">
            <div class="form-group">
                <a class="function-button" href="javascript:void(0);">
                    <i class="icon-print"></i>
                    <span>报表</span>
                </a>
            </div>
        </div>
        <div class="pull-right" >
            <form class="form-inline list-header">
                <div class="form-group">
                    <label for="market">市场:</label>
                    <select class="form-control"  name="market" id="market">
                        <?php foreach($markets as $k => $v){
                            if($v['market_id'] == $selectedMarket){
                        ?>
                            <option value='<?php echo $v['market_id']; ?>' selected><?php echo $v['district'];?></option>
                        <?php }else{?>
                            <option value='<?php echo $v['market_id']; ?>'><?php echo $v['district'];?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment">年:</label>
                    <select class="form-control" id="year" name="year">
                        <?php for($year = 2016; $year <= 2025; $year ++){
                         if($year == $selectedYear){
                         ?>
                         <option value='<?php echo $year?>' SELECTED><?php echo $year?></option>
                        <?php } else { ?>
                         <option value='<?php echo $year?>'><?php echo $year?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment">月:</label>
                    <select class="form-control" id="month" name="month">
                        <option value='-1'>全部</option>
                        <?php for($month = 1; $month < 13; $month ++){
                        if($month == $selectedMonth){
                            ?>
                            <option value='<?php echo $month?>' SELECTED><?php echo $month?></option>
                        <?php } else { ?>
                            <option value='<?php echo $month?>'><?php echo $month?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment">日:</label>
                    <select class="form-control" id="day" name="day">
                        <option value='-1'>全部</option>
                        <?php for($day = 1; $day <= $count; $day ++){
                        if($day == $selectedDay){
                            ?>
                            <option value='<?php echo $day?>' SELECTED><?php echo $day?></option>
                        <?php } else { ?>
                            <option value='<?php echo $day?>'><?php echo $day?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment">支付方式:</label>
                    <select class="form-control" id="payment" name="payment">
                        <?php foreach($payments as $k => $v){
                        if($v['id'] == $selectedPayment){
                            ?>
                            <option value='<?php echo $v['id']; ?>' selected><?php echo $v['title'];?></option>
                        <?php }else{?>
                            <option value='<?php echo $v['id']; ?>'><?php echo $v['title'];?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">订单状态:</label>
                    <select class="form-control" id="status" name="status">
                        <?php
                        foreach($order_status as $key => $item){
                            if($item['id'] == $selectedStatus){
                                echo "<option value='{$item['id']}' SELECTED>{$item['title']}</option>";
                            }else{
                                echo "<option value='{$item['id']}'>{$item['title']}</option>";
                            }
                        }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="order_no">订单号:</label>
                    <input type="text" class="form-control" value="<?php echo $_GET['order'];?>" placeholder="请输入订单号" name="order">
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
                    订单号
                </th>
                <th>
                    金额
                </th>
                <th>
                    利润
                </th>
                <th>
                    支付方式
                </th>
                <th>
                    下单时间
                </th>
                <th>
                    缺货
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
            <?php foreach($order as $item):?>
                <tr data-id="<?php echo $item['order_id'] ;?>">
                    <td><?php echo $item['order_no'] ;?></td>
                    <td> &#165 <?php echo $item['amount'] ;?></td>
                    <td style="color: red;"> &#165 <?php echo $item['amount'] - $item['inamount'] ;?></td>
                    <td><?php echo ($item['payment'] == 1) ? "微信" : (($item['payment'] == 2) ? "支付宝" : ($item['payment'] == 3) ? "积分" : "货到付款") ;?></td>
                    <td><?php echo date('Y-m-d',$item['created_at']);?></td>
                    <td><?php echo ($item['goods_less'] == 0) ? "是" : "否";?></td>
                    <td><?php echo ($item['status'] == 0) ? "已取消" : (($item['status'] == 1) ? "未支付" : ($item['status'] == 2) ? "已支付" : ($item['status'] == 3) ? "派送中" : "已完成") ;;?></td>
                    <td>
                        <button class="btn-operate">
                            <a>详细</a>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <?php $pagination->render();?>
</div>
<?php $this->piece('common/foot.php');?>
