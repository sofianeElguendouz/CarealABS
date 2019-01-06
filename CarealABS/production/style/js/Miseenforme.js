$(".modal_bg").hide();
/*---------------------------*/
$("#myBtn").click(function(){
    	$(".modal_bg").show();
      $("html,body").css('overflow-y','hidden');
});
/*---------------------------*/
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
            function(){ swal("Supprimé !", "Le choix a été supprimé !", "success"); }
        );
	      $('.confirm').attr('title',$(this).attr('title'));
	      $('.confirm').click(function(){
                   var button=$(this);
                   var id=button.attr('title');
              	   $.post("supp_serv.php",{id:button.attr('title')},function(data){});
                   setTimeout(function(){
                    	     $(".supprimer[title="+id+"]").parent().parent().hide(500);
                   },300,id);
                   $('html').css('overflow-y','scroll');
         });
});
/*---------------------------*/
$(".valider").click(function(){
	swal("Validation ", "Ajout accompli !", "success");
  $(".modal_bg").hide();
});
/*---------------------------*/
$('.form').submit(function(){
    var nom=$('.Nom').val();
    var dep=$('.Dep').val();
    $.post("ajoutService.php",{n:nom,d:dep},function(data){});
    return false;
});
/*---------------------------*/
$(".closer").click(function(){
    $(".modal_bg").hide();
    $("html","body").css('overflow-y','visible');
});
 



