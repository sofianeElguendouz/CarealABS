$('.InfoEmploye').hide();
$('.d').hide();
$('.more').click(function(){

 var info=$(this).parent().parent().parent().parent();
    var nom=info.find('.nom').text();
    var matricule=info.find('.matricule').text();
    $('.hidden').val(matricule);
    var departement=info.find('.departement').text();
    var niveau=info.find('.niveau').text();
    var service=info.find('.service').text();
    $('.InfoEmploye').empty();
    $('.InfoEmploye').html("   <div class='col-md-5 col-md-offset-3'><div class='block'><div class='thumbnailok'><div class='captionok text-center'> <h1>Mr "+nom+"</h1> <h4>"+matricule+"</h4> <h4>Niveau : "+niveau+"</h4> <h4>Service: "+service+"</h4> <h4>Département:"+departement+"</h4></div></div></div></div>");
                           
    	           
    	       
  var top= info.offset().top - $('.lol').offset().top;
 var left=-$('.lol').offset().left + info.offset().left;
 $('.infos').hide();
 info.show();
 info.css({'position':'absolute','top':top,'left':left});
 info.animate({'top':110,'left':($('.lol').width()-$('.infos').width())/2,'opacity':0 },650,function(){
     $('.InfoEmploye').css({'top':110,'left':($('.lol').width()-$('.InfoEmploye').width())/2});
 	 $('.InfoEmploye').show(500);
 	 $('.d').show();
 	});
 


 return false;
});