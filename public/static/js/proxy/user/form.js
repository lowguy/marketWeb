/**
 * Created by Administrator on 2016/4/22 0022.
 */
$(function(){
    //$(document).on('click','button.js-submit',function(){
    //    apply();
    //})
    $('.user-form').validate({
        debug:true,
        errorElement:'div',
        errorClass:'help-block',
        focusInvalid:false,
        ignore:'.validate-ignore',
        highlight : function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        success : function(label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },
        errorPlacement : function(error, element) {
            element.parents('div.form-group').append(error);
        },

        rules:{
            rate:{
                digits:true,
                range:[0,60]
            }
        },

        messages:{
            rate:{
                digits:"请输入正确的数字",
                range:"请输入0至60区间的数字"
            }
        },
        submitHandler:function(form){
            console.log(1);
            apply();
        }
    });
});

function apply(){
    loading();
    var status  = $('input[name="status"]:checked').val();
    var user_id = $('input[name="user_id"]').val();
    var address = $('input[name="address"]').val();
    var lng = $('input[name="lng"]').val();
    var lat = $('input[name="lat"]').val();
    console.log(rate);
    $.ajax({
        url:'/proxy/user/approval',
        type:'POST',
        dataType:'JSON',
        data:{status:status,id:user_id,address:address,lng:lng,lat:lat},
        success:function(data,err){
            loading();
        },
        error:function(err){
            form_error()
        }
    });
}

function loading(){
    $('button.btn-primary').toggleClass('js-submit');
}
