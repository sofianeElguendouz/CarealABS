var i=0;

$('.valider').hide();
$('.form div').each(function(){
 if(i%2==0)
 {
  $(this).css({'left':-300});
 }
 else 
 {
  $(this).css({'left':350});
 }
 i++;
});

$('.ajouter').click(function(){
$('.h1,.recherche,.table').animate({'opacity':0},1000);
$('.form div').animate({'left':0},1000);
$('.valider').show(1000);

 

});




$('.form').submit(function(){

var nom=$('.nom').val();
var prenom=$('.prenom').val();
var montant=$('.montant').val();
var rang=$('.rang').val();
var departement=$('.department').val();
var service=$('.service').val();
var matricule=$('.matricule').val();
var login=$('.login').val();
var password=$('.password').val();
var adresse=$('.adress').val();
var telephone=$('.telephone').val();
var question=$('.question').val();
var reponse=$('.answer').val();

	$.post('ajoutEmp.php',{Nom:nom,Prenom:prenom,Montant:montant,Rang:rang,Departement:departement,Service:service,Matricule:matricule,Login:login,password:password,Adresse:adresse,Telephone:telephone,question:question,answer:reponse});
return false;
});