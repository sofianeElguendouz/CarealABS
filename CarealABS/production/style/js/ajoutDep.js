$('.form').submit(function(){
   var nom=$('.nom').val();
   $.post('ajoutDep.php',{nom:nom},function(data){
   	alert(data);
   }
   	);
	return false;
});