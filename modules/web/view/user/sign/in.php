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
		<link rel="stylesheet" type="text/css" href="<?php echo $css;?>">
	<?php endforeach;?>
	<link>
</head>
<body>
	<div class="container-fluid">
		<div class="page-header">
			<img class="logo" src="<?php echo $this->cdn('/static/images/logo.png');?>" />
		</div>
		<h3 class="text-center">
			小小家信息管理平台
		</h3>

		<form class="form-horizontal login-form" method="post">

			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="icon-user"></i></span>
					<input name="phone" type="text" class="form-control" placeholder="手机号码" aria-describedby="basic-addon1">
				</div>
			</div>
			<div  class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="icon-lock"></i></span>
					<input name="password" type="password" class="form-control" placeholder="密码" aria-describedby="basic-addon1">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="icon-shield"></i></span>
					<input id="code" name="code" type="text" class="pull-left form-control" placeholder="验证码" aria-describedby="basic-addon1">
					<img class="change-captcha pull-right"
						 src="<?php $captcha = new \web\common\Captcha();echo $captcha->inline();?>"
						/>
				</div>
			</div>
			<div class="form-group">
				<div class="text-center">
					<button type="button" class="btn btn-primary submit">登录</button>
				</div>
			</div>

		</form>
	</div>

	<?php foreach($this->js as $js): ?>
		<script  src="<?php echo $js;?>"></script>
	<?php endforeach;?>
	<script type="text/javascript">
		function login(phone, password, code){
			$(document).ajaxStart(function(){
				ajax_doing();
			});

			$(document).ajaxStop(function(){
				ajax_finished();
			});

			$.ajax({
				url:'/user/sign/in',
				type:'POST',
				dataType:'JSON',
				data:{
					phone:phone,
					password:password,
					code:code
				},

				success:function(data,err){
					if(data.code == 0){
						location.href="/";
					}
					else{
						form_error(data.data)
					}

				},
				error:function(err){
					form_error();
				}
			});
		}


		function ajax_doing(){
			$('button').attr('disabled', true);

		}

		function ajax_finished(){
			$('button').attr('disabled', false);

		}

		$(function(){

			$(document).keydown(function(event){
				switch(event.keyCode){
					case 13:
						$('form').submit();
						break;
					default:
						break;
				}
			});

			$(".change-captcha").click(function(){
				$(this).attr('src', '/site/index/captcha?seed=' + Math.random());
			});

			$('button').click(function(){
				$('form').submit();
			});

			$('form').validate({
				errorElement:'div',
				errorClass:'help-block',
				focusInvalid:false,
				highlight : function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				success : function(label) {
					label.closest('.form-group').removeClass('has-error');
					label.remove();
				},
				errorPlacement : function(error, element) {
					element.parents('.form-group').append(error);
				},
				rules:{
					phone:{
						required:true,
						mobile:true
					},
					password:{
						required:true
					},
					code:{
						required:true,
						minlength:4,
						remote:{
							url:'/user/sign/checkCode',
							type:'POST'
						}
					}
				},

				messages:{
					phone:{
						required:'请输入手机号码'
					},
					password:{
						required:'请输入密码'
					},
					code:{
						required:'验证码错误',
						minlength:'验证码错误',
						remote:'验证码错误'
					}
				},

				submitHandler:function(form){
					var phone = $('input[name="phone"]').val();
					var password = $('input[name="password"]').val();
					var code = $('input[name="code"]').val();

					login(phone, password, code);
				}
			});
		});
	</script>
</body>
</html>
