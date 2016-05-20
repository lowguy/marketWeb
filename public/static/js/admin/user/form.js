
function add(phone, password, role){
    $(document).ajaxStart(function(){
        ajax_doing();
    });

    $(document).ajaxStop(function(){
        ajax_finished();
    });

    $.ajax({
        url:'/admin/user/add',
        type:'POST',
        dataType:'JSON',
        data:{
            phone:phone,
            password:password,
            role:role
        },

        success:function(data,err){
            if(data.code == 0){
                form_success('添加成功');
            }
            else{
               form_error(data.data)
            }

        },
        error:function(err){
            form_error()
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
            element.parent('div').append(error);
        },
        rules:{
            phone:{
                required:true,
                mobile:true,
                remote:{
                    url:'/admin/user/phoneused',
                    type:'POST'
                }
            },
            password:{
                required:true
            },
            password_confirm:{
                required:true,
                equalTo:'input[name="password"]'
            }
        },

        messages:{

            phone:{
                required:'请输入手机号码',
                mobile:'请输入手机号码',
                remote:'该手机号码已被注册'
            },
            password:{
                required:'请输入密码'
            },
            password_confirm:{
                required:'请输入密码',
                equalTo:'两次输入的密码不一致'
            }
        },

        submitHandler:function(form){
            var phone = $('input[name="phone"]').val();
            var password = $('input[name="password"]').val();
            var role = $('select[name="role"]').val();

            add(phone, password, role);
        }
    });
});