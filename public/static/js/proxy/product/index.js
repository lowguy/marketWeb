/**
 * Created by LegendFox on 2016/3/4 0004.
 */
$(function(){
    //初始化菜单
    category_init($('select[name="market_parent"] option:selected').val());
    $(document).on('change','select[name="market_parent"]',function(){
        category_init($(this).val());
    });

    //取消代理产品
    $(document).on('click','button.btn-cancel',function(){
        cancel($(this).attr('data-id'));
    });

    //授权代理产品给商户
    $(document).on('click','button.btn-apr-user',function(){
        if(!$(this).hasClass('loading')){
            productToUser($(this).attr('data-id'),$(this).attr('data-user'),$(this));
        }
    });

    //取消授权
    $(document).on('click','button.btn-apr-cancel',function(){
        productCancelToUser($(this).attr('data-id'));
    });

});


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

function cancel(product_id){
    $.ajax({
        url:'/proxy/product/cancel',
        type:'POST',
        dataType:'JSON',
        async: true,
        data:{product_id:product_id},
        success:function(data,err){
            if(data.code == 0){
                location.reload();
            }else{
                form_error(data.data);
            }
        },
        error:function(err){
            form_error()
        }
    });
}

function productToUser(p,u,e){
    $(e).addClass('loading');
    $.ajax({
        url:'/proxy/product/productToUser',
        type:'POST',
        dataType:'JSON',
        async: true,
        data:{pid:p,uid:u},
        success:function(data,err){
            if(data.code == 0){
                $(e).closest('tr').remove();
            }else{
                form_error(data.data);
            }
        },
        error:function(err){
            form_error()
        }
    });
}

function productCancelToUser(p){
    $.ajax({
        url:'/proxy/product/productCancelToUser',
        type:'POST',
        dataType:'JSON',
        async: true,
        data:{pid:p},
        success:function(data,err){
            if(data.code == 0){
                location.reload();
            }else{
                form_error(data.data);
            }
        },
        error:function(err){
            form_error()
        }
    });
}