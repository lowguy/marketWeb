function form_error(data){
    data = data || '系统忙，请稍候再试';
    var message = '<p class="text-center form-error">' + data +'</p>';
    $('form').prepend(message);
    $('form .form-error').fadeIn();
    setTimeout(function(){
        $('form .form-error').fadeOut('normal', function(){
            $('form .form-error').remove();
        });
    }, 1500);
}

function form_success(data){
    data = data || '成功';
    var message = '<p class="text-center form-success">' + data +'</p>';
    $('form').prepend(message);
    $('form .form-success').fadeIn();
    setTimeout(function(){
        location.reload();
    }, 1500);
}