$('.back').css({'top':$('#loginbox').offset().top-5,'left':$('#loginbox').offset().left-5,'width':$('#loginbox').width()+10,'height':$('#loginbox').height()+10});
$('.fond').css({'height':$('.back').height(),'width':$('.back').width()*3,'left':-$('.back').width()*2});
$('.fond').animate({'left':0},10000,function(){
     $('.fond').animate({'left':-$('.back').width()*2},10000);
	});
setInterval(function(){
	$('.fond').animate({'left':0},10000,function(){
     $('.fond').animate({'left':-$('.back').width()*2},10000);
	});

	},20000);