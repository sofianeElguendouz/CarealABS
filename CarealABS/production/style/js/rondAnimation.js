$('.rondBlanc').hide();
$('.rond button').hide();
$('.rond p').css({'margin-top':($('.rond').height()-20)/2});
$('.rond').mouseenter(function(){
   
    var p={};
    p.top=$(this).offset().top-10;
    p.left=$(this).offset().left-10;
    $('.rondBlanc').show();
    $('.rondBlanc').offset(p);
    p.top=p.top+10;
    p.left=p.left+10;
    $(this).offset(p);

    $('.black-opacity').show();
      $('.black-opacity').css({'top':$(window).scrollTop()-height});
      $('.black-opacity').stop().animate({'opacity':0.6},500);
       var clone=$(this).clone();
       clone.attr('id','rondClone')
      clone.css({'position':'absolute','z-index':3,'top':10,'left':10,'margin':0});
      $('.fond').css({'position':'absolute','z-index':2});
      $('.fond').animate({'top':0},600,function(){
        $('.rond,.rondBlanc').each(function(){$(this).fadeOut(700);
           $('.rond button').click(function(){ 
        var id=$(this).attr('id');
        $('.listChoix').hide();
        var k = id.split('_');
        if(k[0]!='periode') {$('.date').show();}else {$('.date1').show();}
        $('.affichbtn').attr('id',$(this).attr('id'));
      });


        setTimeout(function(){$('#rondClone button').show().trigger('click');},700);
        });
         $('.black-opacity').hide();
      });
$('.rondBlanc').append(clone);
       $('#rondClone').mouseleave(function(){
         $('.black-opacity').hide();
         $('.fond').stop().css({'top':-240});
         $(this).remove();


});
});