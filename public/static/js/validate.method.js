/**
 * Created by Monk on 2016/1/25.
 */
$(function(){
    $.validator.addMethod("mobile", function(value, element) {
        return this.optional(element) || /^((\(\d{3}\))|(\d{3}\-))?13[0-9]\d{8}|15[012356789]|18[01236789]\d{8}$/.test(value);
    }, "请输入手机号码");
});