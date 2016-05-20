/**
 * Created by Monk on 2016/1/27.
 */

$(function(){
   $('#role, #status').change(function(){
       $('form').submit();
   });

    $(".change-status").click(function(){
        var button = $(this);
        var id = button.closest('tr').attr('data-id');
        button.attr('disabled', true);
        $.ajax({
            url:'/admin/user/status',
            type:'POST',
            dataType:'JSON',
            data:{
                id:id
            },
            success:function(data,err){
                button.attr('disabled', false);
                if(data.code == 0){
                    location.reload();
                }
            },
            error:function(err){
                button.attr('disabled', false);
            }
        });
    });
});