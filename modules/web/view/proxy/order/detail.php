<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/proxy/order/index','market'=>$market,'currentMarket'=>$currentMarket));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li class="active"><span class="active">订单管理</span></li>
        <li class="active">
            <span class="active">
                订单详情
            </span>
        </li>
    </ol>
    <div>
        <?php
        var_dump($order);
        ?>
    </div>
</div>

<?php $this->piece('common/foot.php');?>