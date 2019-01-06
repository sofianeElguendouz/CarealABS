$(".modal_bg").hide();
$("#myBtn").click(function(){
	$(".modal_bg").show();
  $("html,body").css('overflow-y','hidden');
});

$('.supprimer').click(function(){
	swal({
  title: "Etes vous sûr?",
  text: "Vous serez incapable de le recupérer !",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Oui, supprimer !",
  closeOnConfirm: false
},
function(){
  swal("Supprimé !", "Le Département a été supprimé !", "success");
});
	$('.confirm').attr('title',$(this).attr('title'));
	$('.confirm').click(function(){
     var button=$(this);
     var id=button.attr('title');
     //$('.lll').load('../../supp_dep.php','id='+id);
	$.post("supp_dep.php",{id:button.attr('title')},function(data){
	                                                    
	                                                  });
setTimeout(function(){
	$(".supprimer[title="+id+"]").parent().parent().hide(500);
},300,id);
$('html').css('overflow-y','scroll');
});
});
$(".valider").click(function(){
	swal("Validation ", "Ajout accompli !", "success");
  $(".modal_bg").hide();


});
$('.form').submit(function(){


var nom=$('.Nom').val();
  $.post("ajoutDepart.php",{nom:nom},function(data){
                                           
                                                    });
  return false;
});

$(".closer").click(function(){
  $(".modal_bg").hide();
  $("html","body").css('overflow-y','visible');

});
 

