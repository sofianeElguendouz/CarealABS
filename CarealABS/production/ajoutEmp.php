<?php 
require 'classes/Admin.php';
$admin=new Administrateur();
$admin->ajouter_employe($_POST);
?>