<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>小小家后台管理系统</title>
    <link rel="shortcut icon" href="<?php $this->favicon(); ?>">
    <?php foreach($this->css as $css): ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->cdn($css);?>">
    <?php endforeach;?>
</head>
<body>
<div class="container-fluid">
    <div class="page-header <?php if(!empty($header)){ echo "hide";}?>">
        <img class="logo" src="<?php echo $this->cdn('/static/images/logo.png');?>" />
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-user"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="/user/sign/out">退出</a></li>
            </ul>
        </div>
        <?php if(!empty($market)){ ?>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-shopping-cart"></i> <?php echo $currentMarket?><span class="caret"></span>
            </button>
            <?php if(empty($isShow)){ ?>
            <ul class="dropdown-menu">
                <?php foreach($market as $key=>$item):?>
                <li><a href="<?php echo $item['url'];?>"><?php echo $item['district'];?></a></li>
                <?php endforeach;?>
            </ul>
            <?php }?>
        </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <ul class="nav nav-list side-nav">
                <?php foreach($menu as $key=>$item):?>
                <li>
                    <a class="top" href="<?php echo $item['url'];?>"><?php echo $item['name'];?>
                        <?php if($item['url'] == $selected_menu):?>
                        <i class="icon-chevron-right pull-right"></i>
                        <?php endif;?>
                    </a>
                </li>
                <?php endforeach;?>
            </ul>
        </div>

