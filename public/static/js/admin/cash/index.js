/**
 * Created by Administrator on 2016/5/17 0017.
 */
$(function(){
    $(document).on('click','button.apply-status',function(){
        var apply_id = $(this).closest('tr').attr('data-id');
        $('#myModal input[name="apply_id"]').val(apply_id);
        $('#myModal').modal('toggle');
    });
    $('#myModal').on('shown.bs.modal', function () {
        $('button.apply-primary').on('click',function(){
            var apply_id = $('#myModal input[name="apply_id"]').val();
            var comment = $('#myModal textarea[name="comment"]').val();
            var status = $('#myModal input[name="status"]:checked ').val();
            if(!comment){
                $('#myModal textarea[name="comment"]').closest('div.form-group').addClass('has-error');
                $('<div class="help-block">请输入备注信息</div>').insertAfter('#myModal textarea[name="comment"]');
                return;
            }
            $.ajax({
                url:'/admin/cash/examine',
                type:'POST',
                dataType:'JSON',
                data:{id:apply_id,comment:comment,status:status},
                success:function(data,err){
                    if(data.code == 0){
                        $('#myModal').modal('hide');
                    }
                },
                error:function(err){
                    form_error()
                }
            });
        });
        $(document).on('keyup','#myModal textarea[name="comment"]',function(){
            if($(this).val()){
                if($(this).closest('div.form-group').hasClass('has-error')){
                    $(this).closest('div.form-group').removeClass('has-error');
                    $(this).closest('div.form-group').find('div.help-block').remove();
                }
            }
        });
    });
    $('#myModal').on('hidden.bs.modal', function () {
        location.reload();
    });
});