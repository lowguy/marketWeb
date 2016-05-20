/**
 * Created by LegendFox on 2016/2/29 0029.
 */
$(function(){
    get_parent_category();

    $('select[name="parent"]').on('change',function(){
        get_child_category($(this).val());
    });

    $('.btn-delete>a').on('click',function(){
        del($(this).parents('tr'));
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
function get_child_category(parent){
    $.ajax({
        url:'/admin/category/getCategoryByPid',
        type:'POST',
        dataType:'JSON',
        data:{
            pid:parent
        },
        success:function(data,err){
            if(data.code == 0){
                $('select[name="category"]').empty();
                var category_id = $('select[name="category"]').attr('data-id');
                var html = '<option>无</option>';
                $.each(data.data,function(i,e){
                    if(category_id == e["category_id"]){
                        html += '<option value="' + e["category_id"] + '" selected>' + e["category_name"] + '</option>';
                    }else{
                        html += '<option value="' + e["category_id"] + '">' + e["category_name"] + '</option>';
                    }
                });
                $('select[name="category"]').append(html);
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
            html += '<option value="' + e["category_id"] + '" selected>' + e["category_name"] + '</option>';
            get_child_category(e["category_id"]);
        }else{
            html += '<option value="' + e["category_id"] + '">' + e["category_name"] + '</option>';
        }
    });
    $('select[name="parent"]').append(html);
}
function del(obj){
    var product_id = $(obj).attr('data-id');
    $.ajax({
        url:'/admin/product/delete',
        type:'POST',
        dataType:'JSON',
        data:{
            product_id:product_id
        },
        success:function(data,err){
            if(data.code == 0){
                form_success(data.data);
                $(obj).remove();
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


