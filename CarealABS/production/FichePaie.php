<?php

$matricule=$_POST['Matricule'];
$month = $_POST['Mois'];//$_GET['Mois'];   //format yyyy-mm         à rectifier


$bdd = new PDO('mysql:dbname=project;localhost=localhost','root','');
//get user's data
$req = $bdd->prepare('SELECT * FROM salary WHERE Matricule =?');
$req->execute(array($matricule));//change 2 by matricule
$element = $req->fetch();
$req1 = $bdd->prepare('SELECT Service FROM services WHERE Num =?');
$req1->execute(array($element['Service']));
$service = $req1->fetch();
$servicename = $service['Service'];//nom du service 
$req2 = $bdd->prepare('SELECT * From department WHERE Id =?');
$req2->execute(array($element['Departement']));
$depart = $req2->fetch();
$departname = $depart['Departement'];//nom du departement

require("classes/Directeur.php");
$ec = new Directeur($element['ID'],$element['Matricule'],$element['Login'],$element['Password']);
$ec->initiate();
$nom = $element['Nom'];
$rang = $element['Rang'];

$monthOnly = substr($month,5,2);
$yearOnly = substr($month,0,4);
$format = substr($month,0,7);
$i = 0 ;
while (checkdate($monthOnly, ($i+1), $yearOnly))  {$i++;}
$nbHTotal = 8*($i-$ec->nbWeekendMois($month));//nbheure total
$nbHAbs = $ec->simpleHeuresAbsMois($matricule,$month);
$nbHJustif = $ec->nbJustMonth($matricule,$month);
$chnbHTotal = strval($nbHTotal);
$chnbHAbs = strval($nbHAbs);
$chnbHJustif = strval($nbHJustif);
/*-------------------------------------------------------------------------------------------------------------------------*/
require ("fpdf.php");
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image("images/logo.png",NULL,NULL,37);
$pdf->SetFont("Arial","","12");
$pdf->Text(47,20,"Ecole Nationale");
$pdf->Text(47,30,"Superieure");
$pdf->text(47,40,"D'informatique");
$pdf->Text(130,20,"BP 68M OUED SEMAR");
$pdf->Text(130,30,"16309, EL HARRACH");
$datetime = date(" d - m - Y");
$pdf->Text(130,40,"Le ".$datetime);
$pdf->SetFont("Arial","B","16");
$pdf->SetXY(15,55);
$pdf->Cell(180,10,"Fiche de paie",1,1,"C");
//corps_______________________________________________________________________________________________
$pdf->SetFont("Arial","B","14");
$pdf->SetLineWidth(0.2);
$pdf->Rect(15,140,180,125,"D");
$pdf->Text(88,147,"Bulletin de Paie");
$pdf->SetTextColor(142,162,198);
$pdf->Text(62,153,"Periode du ".$month."-01 au ".$month."-30");//période
$pdf->Line(15,156,195,156);
$pdf->SetTextColor(18,16,102);
$pdf->Text(88,162,"Salaire Brut");
$pdf->Line(15,164,195,164);
$pdf->Line(15,179,195,179);
$pdf->SetFont("Arial","",14);
$pdf->SetTextColor(0,0,0);
$pdf->Text(16,170,"Total heures");
$pdf->Line(60,164,60,179);
$pdf->Line(76,164,76,179);
$pdf->Line(99,164,99,179);
$pdf->Line(119,164,119,179);
$pdf->Line(152,164,152,179);
$pdf->Text(79,173,"Taux");
$pdf->Text(123,173,'Montant');
$pdf->SetFont("Arial","B",14);
$pdf->SetTextColor(18,16,102);
$pdf->Text(70,185,"Conges et absences justifiees");
$pdf->Line(15,188,195,188);
$pdf->Line(15,203,195,203);
$pdf->Line(60,188,60,203);
$pdf->Line(76,188,76,203);
$pdf->Line(99,188,99,203);
$pdf->Line(119,188,119,203);
$pdf->Line(152,188,152,203);
$pdf->SetFont("Arial","",14);
$pdf->SetTextColor(0,0,0);
$pdf->Text(16,194,"Total heures");
$pdf->Text(16,200,"absences justifiees");
$pdf->Text(79,194,"Duree ");
$pdf->Text(79,200,"conge");
$pdf->Text(123,198,'Montant');
$pdf->SetFont("Arial","B",14);
$pdf->SetTextColor(18,16,102);
$pdf->Text(74,209,"Absences non justifiees");
$pdf->Line(15,212,195,212);
$pdf->Line(15,222,195,222);
$pdf->Line(15,232,195,232);
$pdf->Line(15,242,195,242);
$pdf->Line(120,212,120,242);
$pdf->SetFont("Arial","",14);
$pdf->SetTextColor(0,0,0);
$pdf->Text(19,219,"Total heures absences");
$pdf->Text(19,229,"1 heure d'absence vaut");
$pdf->Text(19,239,"Total sanctions");
$pdf->SetFont("Arial","B",14);
$pdf->SetTextColor(18,16,102);
$pdf->Text(88,248,"Salaire Net");
$pdf->Line(15,250,195,250);
$pdf->SetFont('Arial',"",14);
$pdf->SetTextColor(0,0,0);
$pdf->Text(130,229,"-0,1% du salaire brut");
$pdf->SetFont("Arial","B","12");
$pdf->SetTextColor(0,0,0);
$pdf->Rect(15,70,180,64,"D");
$pdf->Text(17,78,"Nom :");
$pdf->Text(17,86,"Prenom :");
$pdf->Text(17,94,"Matricule :");
$pdf->Text(17,102,"Service :");
$pdf->Text(17,110,"Rang :");
$pdf->Text(17,118,"Departement : ");
$pdf->Text(17,126,"Nom de l'etablissement : ");
$pdf->SetFont("Arial","","12");
$pdf->Text(35,78,$element['Nom']);
$pdf->Text(40,86,$element['Prenom']);
$pdf->Text(45,94,$element['Matricule']);
$pdf->Text(40,101,$servicename);
$pdf->Text(35,110,$element['Rang']);
$pdf->Text(53,118,$departname);
$pdf->Text(75,126,"Ecole nationale superieure de l'informatique");
$pdf->SetFont("Arial",'','14');
$pdf->Text(61,173,$nbHTotal.' H');
$pdf->Text(63,198,$nbHJustif.' H');
$pdf->Text(145,219,$nbHAbs-$nbHJustif.' H');
$heureNonJustif = $nbHAbs-$nbHJustif;
$pdf->Text(130,239,'- '.(($heureNonJustif*0.1)).' % du salaire brut');
$pdf->Text(102,173,"100%");
$pdf->Text(157,173,$element['Montant'].' DA');
$pdf->Text(88,260,$element['Montant']-($element['Montant']*0.1*$heureNonJustif/100).' DA');
echo $pdf->Output();
?>