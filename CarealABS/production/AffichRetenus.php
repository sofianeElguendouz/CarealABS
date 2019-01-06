<?php
	 require 'classes/Employe.php';
	$lesMois = array(' ','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
    $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
    $emp= $bdd->prepare("SELECT * FROM salary WHERE Matricule = :mat");
    $emp->execute(array( 'mat' => $_GET['Matricule'] ));  
    $pers = $emp->fetch();
    $date=explode('-',$_GET['Mois']);
    $m=$date[0].'-'.$date[1];

    $ec=new Employe(0,0,' ',' ');
    $nom  = $pers['Nom'];
    $serv=$pers['Service'];
    $dep=$pers['Departement'];
    $rang=$pers['Rang'];
    $month=$m;          //format : yyyy-mm
    $mat  = $_GET['Matricule'];
/*---------------------------------------Renvoie nb heures total-------------------------------------------------*/
			$monthOnly = substr($month, 5, 2); 
			$yearOnly = substr($month, 0, 4); 
			$format = substr($month, 0, 7);
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) { 
				$i++;
			}
			$nbHTotal=8*($i-$ec->nbWeekendMois($month));
/*---------------------------------------Renvoie nb heures absences mois-----------------------------------------*/
			$nbHAbs=$ec->simpleHeuresAbsMois($mat,$month);
/*---------------------------------------Renvoie nombre jours justifiés------------------------------------------*/
			$nbHAbsJustif=$ec->nbJustMonth($mat,$month);
/*---------------------------------------------------------------------------------------------------------------*/
			$nbHJustif = $ec->nbJustMonth($mat,$month);
			$heureNonJustif = $nbHAbs-$nbHJustif;
			$pSanc=$heureNonJustif*0.1;
			$mSanc=$pSanc/100*$pers['Montant'];
			$salNet=$pers['Montant']-$mSanc;




    echo "
    	<div class='text-center' style='background-color: #ddd;'>
    		<h1>Employé : ".$pers['Nom']." ".$pers['Prenom']."</h1>
    		<h1>Retenus En ".$lesMois[intval($date[1])]." ".$date[0]."</h1>
    	</div>";
    echo "
          				<div class='row tile_count'>
    					  <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Normales</h2></span>
			              	<div class='count green'><h2>".$nbHTotal." H</h2></div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Absences Justifiées</h2></span>
			              	<div class='count green'><h2>".$nbHAbsJustif." H</h2></div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Absences</h2></span>
			              	<div class='count green'><h2>".$nbHAbs." H</h2></div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Durée Congé</h2></span>
			              	<div class='count green'><h2>".(0)." Jour</h2></div>
			              </div>
			            </div>";

	echo "
          				<div class='row tile_count'>
    					  <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Salaire Brut</h2></span>
			              	<div class='count green'><h2>".$pers['Montant']." DA</h2></div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total sanctions (%)</h2></span>
			              	<div class='count green'><h2>".$pSanc." % Du Salaire Brut</h2></div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total sanctions (DA)</h2></span>
			              	<div class='count green'><h2>".$mSanc." DA</h2></div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Salaire Net</h2></span>
			              	<div class='count green'><h2>".$salNet." DA</h2></div>
			              </div>
			            </div>";
?>
