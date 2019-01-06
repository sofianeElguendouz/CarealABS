var width=$(window).width();
var height=$(window).height();
$('.black-opacity').css({'width':width,'height':height});
$('.black-opacity').hide();
$('.confirmation').css({'width':width*0.4,'height':height*0.2,'left':width*0.3});
$('.confirmation').hide();
$('.button-recherche').click(function(){
var texte=$('.barre-recherche').val();
$('.employee').each(function(){
 if($(this).find('.nom').text()!=texte)
 {
 	$(this).hide();
 }
 else 
 {
 	$(this).css({'position':'absolute','top':$('thead').height(),'left':0});
 }
});

});
$('.button-retour').click(function(){
$('.employee').each(function(){
	$(this).css({'position':'relative'});
	$(this).show();
});
});
$('.supprimer').click(function(){
$('.black-opacity').show();
$('.confirmation').show();
$('.black-opacity').css({'top':$(window).scrollTop()});
$('.confirmation').css({'top':0.4*height+$(window).scrollTop()});
$('html').css('overflow-y','hidden');
$('.confirmation p').empty();
$('.confirmation').prepend('<p>etes-vous sur de vouloir supprimer '+$(this).attr('href')+'<p>');
$('.confirm-supress').attr('title',$(this).attr('title'));
});
$('.confirm-supress').click(function(){
var button=$(this);
var id=button.attr('title');
$.post("supression.php",{id:button.attr('title')},function(data){
	                                                    $('.black-opacity').hide();
	                                                    $('.confirmation').hide();
	                                                  });
setTimeout(function(){
	$(".supprimer[title="+id+"]").parent().parent().hide(500);
},300,id);
$('html').css('overflow-y','scroll');
});
$('.annul-supress').click(function(){
 $('.black-opacity').hide();
 $('.confirmation').hide();
 $('html').css('overflow-y','scroll');
});