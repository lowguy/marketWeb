/**
 * Created by Administrator on 2016/3/11 0011.
 */
$(function(){
    //添加代理产品
    $(document).on('click','button.btn-operate',function(){
        if(!$(this).hasClass('loading')){
            product_add($(this).attr('data-id'),$(this));
        }
    });
    //初始化菜单
    category_init($('select[name="parent"] option:selected').val());
    $('select[name="parent"]').on('change',function(){
        category_init($(this).val());
    });
});

function product_add(product_id,obj){
    $(obj).addClass('loading');
    $.ajax({
        url:'/proxy/product/commodity',
        type:'POST',
        dataType:'JSON',
        data:{product_id:product_id},
        success:function(data,err){
            if(data.code == 0){
                $(obj).closest('tr').remove();
            }else{
                form_error(data.data);
            }
        },
        error:function(err){
            form_error()
        }
    });
}

function category_init(pid){
    $.ajax({
        url:'/admin/category/getCategoryByPid',
        type:'POST',
        dataType:'JSON',
        data:{
            pid:pid
        },
        success:function(data,err){
            if(data.code == 0){
                $('select[name="category"]').empty();
                var category = $('select[name="category"]').attr('data-id');
                var html = '<option value="0">全部</option>';
                $.each(data.data,function(i,e){
                    if(category == e.category_id){
                        html += '<option value="' + e["category_id"] + '" selected>' + e["category_name"] + '</option>';
                    }else{
                        html += '<option value="' + e["category_id"] + '">' + e["category_name"] + '</option>';
                    }
                });
                $('select[name="category"]').append(html);
            }
        }
    });
}