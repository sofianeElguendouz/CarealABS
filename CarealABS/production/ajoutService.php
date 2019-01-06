<?php 
    $nom=$_POST['n'];
    $dep=$_POST['d'];
     require 'classes/Admin.php';
     $admin = new administrateur();
     //$admin->addService($nom,$dep);
     $admin->addService($nom,$dep);
     