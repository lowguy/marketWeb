/**
 * Created by Administrator on 2016/2/26 0026.
 */
var global_is_edit=false;

$(function(){
    if(location.pathname == '/admin/product/edit'){
        global_is_edit = true;
    }

    get_parent_category();
    $('.product-form').validate({
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
            element.parent('div').append(error);
        },

        rules:{
            uri:{
                required:true
            },
            title:{
                required:true
            },
            category:{
                required:true
            },
            slogan:{
                required:true
            }
        },

        messages:{
            uri:{
                required:"请添加商品图片"
            },
            title:{
                required:"请添加商品名称"
            },
            category:{
                required:"请选择分类"
            },
            slogan:{
                required:"请添加广告语"
            }
        },
        submitHandler:function(form){
            var uri = $('input[name="uri"]').val();
            var title = $('input[name="title"]').val();
            var slogan = $('textarea[name="slogan"]').val();
            var category = $('select[name="category"]').val();
            if(!global_is_edit){
                addProduct(title,slogan,category,uri);
            }else{
                var id = $('input[name="product_id"]').val();
                editProduct(id,title,slogan,category,uri);
            }
        }
    });

    $('select[name="parent"]').on('change',function(){
        get_child_category($(this).val());
    });
    $('#icon_uri').fileupload({
        dataType: 'json',
        add: function (e, data) {
            var numItems = $('.files .images_zone').length;
            if(numItems>1){
                alert('不能多于一张');
                return false;
            }
            data.submit();
        },
        done: function (e, data) {
            var d = data.result;
            if(d.status==0){
                alert("上传失败");
            }else{
                $('input[name="uri"]').val(d.msg);
                var imgshow = '<div class="images_zone_max"><img src="'+d.msg+'" /><a href="javascript:;" ><i class="icon-trash icon-4"></i></a></div>';
                jQuery('.files').append(imgshow);
            }
        }
    });

    //图片删除
    $('.files').on({
        mouseenter:function(){
            $(this).find('a').show();
        },
        mouseleave:function(){
            $(this).find('a').hide();
        },
    },'.images_zone_max');

    $('.files').on('click','.images_zone_max a',function(){
        $('input[name="uri"]').val('');
        $(this).parent().remove();
    });
});
/**
 * 获取一级类目
 */
function get_parent_category(){
    $.ajax({
        url:'/admin/category/getTopCategory',
        type:'POST',
        dataType:'JSON',
        data:{},
        success:function(data,err){
            if(data.code == 0){
                init_select(data.data);
            }
        },
        error:function(err){
            form_error()
        }
    });
}
/**
 * 获取二级类目
 * @param parent
 */
function get_child_category(pid){
    $.ajax({
        url:'/admin/category/getCategoryByPid',
        type:'POST',
        dataType:'JSON',
        data:{
            pid:pid
        },
        success:function(data,err){
            if(data.code == 0){
                var category = $('select[name="category"]').attr('data-id');
                $('select[name="category"]').empty();
                var html = '<option value="">无</option>';
                if(0 != data.data.length){
                    $.each(data.data,function(i,e){
                        if(category == e["category_id"]){
                            html += '<option value="' + e["category_id"] + '" selected>' + e["category_name"] + '</option>';
                        }else{
                            html += '<option value="' + e["category_id"] + '">' + e["category_name"] + '</option>';
                        }
                    });
                }
                $('select[name="category"]').append(html);
            }else{
                console.log(12);
            }
        },
        error:function(err){
            form_error()
        }
    });
}
/**
 * 填充下拉框
 * @param data
 */
function init_select(data){
    var html = '';
    var parent = $('select[name="parent"]').attr('data-id');
    $.each(data,function(i,e){
        if(parent == e["category_id"]){
            get_child_category(parent);
            html += '<option value="' + e["category_id"] + '" selected>' + e["category_name"] + '</option>';
        }else{
            html += '<option value="' + e["category_id"] + '">' + e["category_name"] + '</option>';
        }
    });
    $('select[name="parent"]').append(html);
}

function addProduct(title,slogan,category,uri){
    $(document).ajaxStart(function(){
        ajax_doing();
    });
    $(document).ajaxStop(function(){
        ajax_finished();
    });
    $.ajax({
        url:location.href,
        type:'POST',
        dataType:'JSON',
        data:{
            title:title,
            slogan:slogan,
            category:category,
            uri:uri
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
function editProduct(id,title,slogan,category,uri){
    $(document).ajaxStart(function(){
        ajax_doing();
    });
    $(document).ajaxStop(function(){
        ajax_finished();
    });
    $.ajax({
        url:location.href,
        type:'POST',
        dataType:'JSON',
        data:{
            id:id,
            title:title,
            slogan:slogan,
            category:category,
            uri:uri
        },
        success:function(data,err){
            if(data.code == 0){
                form_success('修改成功');
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
    $('#js_submit').attr('disabled', true);

}

function ajax_finished(){
    $('#js_submit').attr('disabled', false);
}