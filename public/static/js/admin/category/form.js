/**
 * Created by LegendFox on 2016/2/24.
 */
var global_is_edit=false;

$(function () {
    if(location.pathname == '/admin/category/edit') {global_is_edit = true;}

    get_parent_category();//获取父级分类

    category_validate();//分类表单验证

    fileupload();//异步上传图片


    //图片删除
    $('.files').on({
        mouseenter:function(){
            $(this).find('a').show();
        },
        mouseleave:function(){
            $(this).find('a').hide();
        },
    },'.images_zone');

    $('.files').on('click','.images_zone a',function(){
        $('input[name="uri"]').val('');
        $(this).parent().remove();
    });
});

function fileupload(){
    $('#icon_uri').fileupload({
        dataType: 'json',
        add: function (e, data) {
            var numItems = $('.files .images_zone').length;
            if(numItems>1){
                form_error('不能多于一张');
                return false;
            }
            data.submit();
        },
        done: function (e, data) {
            var d = data.result;
            if(d.status==0){
                form_error("上传失败");
            }else{
                $('input[name="uri"]').val(d.msg);
                var imgshow = '<div class="images_zone"><img src="'+d.msg+'" /><a href="javascript:;"><i class="icon-trash icon-2"></i></a></div>';
                jQuery('.files').append(imgshow);
            }
        }
    });
}

function category_validate(){
    $(".category-form").validate({
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
            element.parent('div').append(error);
        },
        rules:{
            category_name:{
                required:true
            },
            uri:{
                required:true
            }
        },
        messages:{
            category_name:{
                required:"请添加分类名称"
            },
            uri:{
                required: "请上传分类图标"
            }
        },
        submitHandler:function(form){
            var data = $(form).serializeArray(),
                url  = !global_is_edit ? 'add' : 'edit';
            doCategory(data,url);//分类操作
        }
    });
}

function doCategory(data,url){
    $(document).ajaxStart(function(){
        ajax_doing();
    });
    $(document).ajaxStop(function(){
        ajax_finished();
    });
    $.ajax({
        url:url,
        type:'POST',
        dataType:'JSON',
        data:data,
        success:function(data,err){
            if(data.code == 0){
                location.reload();
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

function get_parent_category(){
    $.ajax({
        url:'getTopCategory',
        type:'POST',
        dataType:'JSON',
        data:{},
        success:function(data,err){
            if(data.code == 0){
                console.log(data.data);
                init_select(data.data);//初始化下拉框
            }
        },
        error:function(err){
            form_error()
        }
    });
}

function init_select(data){
    var html = '';
    var pid = $('select[name="parent"]').attr('data-id');
    $.each(data,function(i,e){
        if(pid == e["category_id"]){
            html += '<option value="' + e["category_id"] + '" selected>' + e["category_name"] + '</option>';
        }else{
            html += '<option value="' + e["category_id"] + '">' + e["category_name"] + '</option>';
        }
    });
    $('select[name="parent"]').append(html);
}


function ajax_doing(){
    $('#js_submit').attr('disabled', true);

}

function ajax_finished(){
    $('#js_submit').attr('disabled', false);
}