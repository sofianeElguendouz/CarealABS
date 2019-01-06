  var width=$(window).width();
   var width_block=$('.block').width();
      var height=$(window).height();
      $('.black-opacity').css({'width':width,'height':height*3});
      $('.black-opacity').hide();
      $('.block').mouseenter(function(){
        
      $('.black-opacity').show();
      $('.black-opacity').css({'top':$(window).scrollTop()-height});
      $('.black-opacity').stop().animate({'opacity':0.6},500);
       var clone=$(this).clone();
       clone.attr('class','clone');
      clone.css({'position':'absolute','top':$(this).offset().top,'left':$(this).offset().left,'width':width_block,'z-index':2});
      $('body').append(clone);
       $('.btnok').click(function(){
        var Matricule=$(this).attr('id');
        $('.listEmployes').empty();
        $('.listChoix').show();
        var i;
        for(i=0;i<$('.listChoix button').length;i++)
        {
          $('.listChoix button:eq('+i+')').attr('id',$('.listChoix button:eq('+i+')').attr('id')+'_'+Matricule);
        }
        $('.black-opacity').css({'opacity':0});
      $('.black-opacity').hide();
      $('.clone').remove();
      });
      $('.clone').mouseleave(function(){
      $('.black-opacity').css({'opacity':0});
      $('.black-opacity').hide();
      $('.clone').remove();
      });
      });
      