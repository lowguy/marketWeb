/**
 * Created by LegendFox on 2016/3/11 0011.
 */
$(function() {
    //初始化slider
    var values = [$( "input[name='start']").val(),$( "input[name='end']").val()];
    $( "#slider-range" ).slider({
        range: true,
        animate: "fast",
        disabled: true,
        min: 0,
        max: 86400,
        step:300,
        values: values,
        slide: function( event, ui ) {
            refreshSwatch(ui.values[ 0 ],ui.values[ 1 ]);
            $( "input[name='start']").val(ui.values[ 0 ]);
            $( "input[name='end']").val(ui.values[ 1 ]);
            $( "start" ).html(transform_time(ui.values[ 0 ]));
            $( "end" ).html(transform_time(ui.values[ 1 ]));
        },
    });

    //slider默认值
    $( "start" ).html(transform_time($( "#slider-range" ).slider( "values", 0 )));
    $( "end" ).html(transform_time($( "#slider-range" ).slider( "values", 1 )));

    //开启或禁用
    $('input[name="isset"]').on('click',function(){
        if($('input[name="isset"]').is(':checked')){
            $( "input[name='start']").val(($( "#slider-range" ).slider( "values", 0 )));
            $( "input[name='end']").val(($( "#slider-range" ).slider( "values", 1 )));
            $( "#slider-range" ).slider('enable');
        }else{
            $( "input[name='start']").val(0);
            $( "input[name='end']").val(0);
            $( "#slider-range" ).slider({disabled: true,});
        }
    });

    activity();
    //开启或禁用活动
    $('input[name="activity"]').on('click',function(){
        activity();
    });

    $('input[name="tag"]').on('click',function(){
        activity_time();
    });

    //表单验证
    $(".edit-form").validate({
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
            price:{
                required:true
            },
            discount:{
                required:true
            }

        },
        messages:{
            price:{
                required:"请设置产品单价"
            },
            discount:{
                required:"请设置产品活动单价"
            }
        },
        submitHandler:function(form){
            edit($(form).serializeArray());
        }
    });
});
function activity(){
    if($('input[name="activity"]').is(':checked')){
        //todo
        $('input[name="activity"]').val(1);
        $('.activity:not(.activity_time)').removeClass('hidden');
        $('.activity:not(.activity_time)').find('input').removeClass('validate-ignore');
    }else{
        $('input[name="activity"]').val(0);
        $('.activity:not(.activity_time)').addClass('hidden');
        $('.activity:not(.activity_time)').find('input').addClass('validate-ignore');
    }
    activity_time();
}
function activity_time(){
    if($("input[name='tag']:checked").val() == 2 && $('input[name="activity"]').is(':checked')){
        $('.activity_time').removeClass('hidden');
        $('.activity_time').find('input').removeClass('validate-ignore');
    }else{
        $('.activity_time').addClass('hidden');
        $('.activity_time').find('input').addClass('validate-ignore');
    }
}
function transform_time(n){
    var h = parseInt(n/3600), m = parseFloat(n%3600/60);
    h = h < 10 ? '0' + h : h;
    m = m < 10 ? '0' + m : m;
    return h+':'+m;
}

function hexFromRGB(r, g, b) {
    var hex = [
        r.toString( 16 ),
        g.toString( 16 ),
        b.toString( 16 )
    ];
    $.each( hex, function( nr, val ) {
        if ( val.length === 1 ) {
            hex[ nr ] = "0" + val;
        }
    });
    return hex.join( "" ).toUpperCase();
}
function refreshSwatch(red,green) {
        var blue = Math.random(0,225),
        hex = hexFromRGB( red, green, blue );
    $( "#slider-range .ui-slider-range" ).css( "background-color", "#" + hex );
}
function edit(data){
    $.ajax({
        url:location.href,
        type:'POST',
        dataType:'JSON',
        data:data,
        success:function(data,err){
            location.reload();
        },
        error:function(err){
            form_error()
        }
    });
}