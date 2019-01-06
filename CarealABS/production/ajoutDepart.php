<?php 
$nom=$_POST['nom'];
echo $nom;
require 'classes/admin.php';
$admin= new Administrateur();
$admin->addDepartement($nom);
?>