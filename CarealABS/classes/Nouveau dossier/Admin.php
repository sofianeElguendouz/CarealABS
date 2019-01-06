<?php

class Administrateur
{
$bdd;


try
 {
 $bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
 }
 catch (exception $e)
 {
	 die ('Erreur'.$e->getMessage());
 }



public void ajouter_employe ($matricule, $nom, $grad, $departement)
{
$req = $bdd->prepare ('INSERT INTO list (matricule,nom,grad,departement) VALUES(:matricule,:nom,:grad,:departement)');
$req -> execute (array('matricule'=>$matricul, 'nom'=>$nom, 'grad'=>$grad, 'departement'=>$departement));

}

public void suprimmer_employer($id)
{
$req=$bdd->prepare('DELETE from list WHERE id = :id' );
$req->execute( array('id'=>$id));
}

public void modifier ($id,$table,$champ,$changes)
{
$req = $bdd->prepare("UPDATE :table SET .$champ. = :champ  WHERE ID = :id");
$req -> execute (array('table'=>$table, 'champ'=>$changes, 'id'=>$id));

}

}
