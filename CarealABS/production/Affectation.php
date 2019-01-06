<?php
	session_start();
	try
	{
		$datab = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
	}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
	
		$login=$_SESSION['inputLogin'];
		$password=$_SESSION['inputPassword'];
		$reponse=$datab->prepare('SELECT * FROM salary WHERE Login=? AND Password =?');
		$reponse->execute([$login,$password]);
		if($reponse->rowCount()>0)
		{
			$personne=$reponse->fetch();
			$_SESSION['Rang']=$personne['Rang'];
			$_SESSION['Departement']=$personne['Departement'];
			$_SESSION['Nom']=$personne['Nom'];
			$_SESSION['Service']=$personne['Service'];
			$_SESSION['Matricule']=$password;
			$_SESSION['Password']=$password;
			$_SESSION['Login']= $personne['Login'];
			$_SESSION['Id']=$personne['ID'];
			switch ($_SESSION['Rang']) {
				case 0:
					require 'Administrateur.php';
					break;
				case 1: 
					require 'UserNiv1.php';
					break;
				case 2: 
					require 'UserNiv2.php';
					break;
				case 3: 
					require 'UserNiv3.php';
					break;
				case 4:
					require 'UserNiv4.php';
					break;
				default:
					require 'Login.php';
					break;
			}
		}
		else 
		{
			require 'Login.php';
		}

?>
