/**
 * Created by Monk on 2016/2/22.
 */

function init_cities(){
    var cities = get_cities();

    var html = '';

    for(var i = 0; i < cities.length; i++){
        html += '<option value="' + cities[i] + '">' + cities[i] + '</option>'
    }

    $('#city').append(html);

    $("#city option").each(function(index, item){
        var city = $(item).val();
        var uri = decodeURI(location.search);

        if(uri.search(city) > -1){
            $(item).attr('selected', true);
        }
    });
}
function marketAuth(id){
    $.ajax({
        url:'/admin/market/marketAuth',
        type:'POST',
        dataType:'JSON',
        data:{
            id:id
        },
        success:function(data,err){
            if(data.code == 0){
                $('.selectpicker').empty();
                var user = data.data.user;
                var user_id = 0;
                if(0 != user.length){
                    user_id = user[0]['user_id'];
                    $('#js_market_user_confirm').empty().html('更新');
                }
                $('input[name="market"]').val(id);
                $.each(data.data.users,function(i,e){
                    if(user_id == e['user_id']){
                        $('.selectpicker').append("<option value='"+ e['user_id'] +"' selected>"+ e['phone'] +"</option>");
                    }else{
                        $('.selectpicker').append("<option value='"+ e['user_id'] +"'>"+ e['phone'] +"</option>");
                    }
                });
                $('.selectpicker').selectpicker('refresh');
                $('.modal').modal('toggle');
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

function marketAuth2User(){
    var user_id = $('.selectpicker').val();
    var market_id = $('input[name="market"]').val();
    console.log(user_id);
    $.ajax({
        url:'/admin/market/marketAuth2User',
        type:'POST',
        dataType:'JSON',
        data:{
            market_id:market_id,
            user_id:user_id
        },
        success:function(data,err){
            if(data.code == 0){
                form_success(data.data);
                $('.modal').modal('toggle');
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

$(function(){
    init_cities();

    $('#city').change(function(){
       $(this).parents('form').submit();
    });

    $(".js-auth").click(function(){
        marketAuth($(this).attr('data-id'));
    });

    $("#js_market_user_confirm").click(function(){
        marketAuth2User();
    });

    $('.selectpicker').selectpicker({
        style: 'btn-success',
        liveSearch: true,
        title:'设置市场管理员',
        header:'请选择下列名单',
        liveSearchPlaceholder:'请输入',
        showTick:true,
        size: 4
    });


});