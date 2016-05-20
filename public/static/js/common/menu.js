$(function(){
   var path = location.pathname;

   $('.side-nav .top').each(function(){
      if($(this).attr('href') == path){
         $(this).addClass('active');
      }
   });
});