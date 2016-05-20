/**
 * Created by Administrator on 2016/5/19 0019.
 */
$(function(){
    $(document).on('change','#month',function(){
        var year = $('#year').val();
        var month = $('#month').val();
        var  day = new Date(year,month,0);
        var count = day.getDate();
        var html = '<option value="-1">全部</option>';
        for(var i = 1; i <= count; i++){
            html += "<option value='" + i + "'>" + i + "</option>";
        }
        $('#day').html(html);
    });

    $(document).on('change','#day',function(){
        var month = $('#month').val();
        if(-1 == month){
            $('#month').focus();
            $('#day').empty().html('<option value="-1">全部</option>');
        }
    });

});