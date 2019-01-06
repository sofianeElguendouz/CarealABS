<?php
    require 'classes/Directeur.php';
    $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');

    function totalNumberEmployes(){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->query("SELECT * FROM salary");
      $result=$results->fetchAll();
      return count($result);
    }
    function totalNumberServices(){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->query("SELECT * FROM services");
      $result=$results->fetchAll();
      return count($result);
    }
    function totalNumberDepartement(){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->query("SELECT * FROM department");
      $result=$results->fetchAll();
      return count($result);
    }
    function hommesfemmes($h,$f)
    {
        $dataPoints = array();
        array_push($dataPoints,array("y" =>$h*100/($h+$f),"name"=>"Hommes", "exploded"=>"true"));
        array_push($dataPoints,array("y" =>$f*100/($h+$f),"name"=>"Femmes"));
        return $dataPoints;
    }
    function Dep(){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->query("SELECT * FROM department");
      $result=$results->fetchAll();
      $dataPoints=array();
      for($i=0;$i<count($result);$i++){
          $r = $bdd->prepare("SELECT ID FROM salary WHERE Departement=:d");
          $r->execute(array('d' =>$result[$i]['Id']));
          $rr=$r->fetchAll();$nb=count($rr);
          array_push($dataPoints,array("name"=>$result[$i]['Departement'],"y"=>$nb,"drilldown"=>$result[$i]['Departement'],"id"=>$result[$i]['Id']));
      }
      return $dataPoints;
    }
    function Serv($dep){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $dataPoints=array();
      for ($i=0; $i < count($dep); $i++) { 
          $results = $bdd->prepare("SELECT * FROM services WHERE Departement=:d");
          $results->execute(array('d' =>$dep[$i]['id']));
          $result=$results->fetchAll();
          $dp=array();
          for($j=0;$j<count($result);$j++){
              $r = $bdd->prepare("SELECT ID FROM salary WHERE Service=:s");
              $r->execute(array('s' =>$result[$j]['Num']));
              $rr=$r->fetchAll();$nb=count($rr);
              array_push($dp,array($result[$j]['Service'],$nb));
          }
          array_push($dataPoints,array("name"=>$dep[$i]['name'],"id"=>$dep[$i]['name'],"data"=>$dp));
    }
    return $dataPoints;
  }
/*---------------------------------------------------------------------------------------------------------------*/
    function totalNumberEmployesDepart($dep){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->prepare("SELECT * FROM salary WHERE Departement=:dd");
      $results->execute(array('dd' =>$dep));
      $result=$results->fetchAll();
      return count($result);
    }
    function totalNumberServicesDepart($dep){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->prepare("SELECT * FROM services WHERE Departement=:dd");
      $results->execute(array('dd' =>$dep));
      $result=$results->fetchAll();
      return count($result);
    }
/*---------------------------------------------------------------------------------------------------------------*/
    function totalNumberEmployesServ($srv){
      $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
      $results = $bdd->prepare("SELECT * FROM salary WHERE Service=:s");
      $results->execute(array('s' =>$srv));
      $result=$results->fetchAll();
      return count($result);
    }
/*---------------------------------------------------------------------------------------------------------------*/
    function tableHistoMoisEcole($mois)
    {
    	$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
		$gettinginputs->execute(array('mat' => 1000000));
		$gettinginput = $gettinginputs->fetch();
    $dep = new Directeur($gettinginput['ID'],$gettinginput['Matricule'],$gettinginput['Login'], $gettinginput['Password']);
		$dep->initiate();
        $absMonth=$dep->majorArrayByMonthTauxP($mois);
        $nbJours=count($absMonth[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$absMonth[0][$i],"label"=>$absMonth[1][$i]));
            }

        return $dataPoints;
    }
    /*----------------------------------------------*/
    function tableHeuresAbsEcole($mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
   $emp = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absMois=$emp->majorArrayByMonthAbs($mois);
        $nbJours=count($absMois[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$absMois[0][$i],"label"=>$absMois[1][$i]));
            }
        return $dataPoints;
    }
    /*----------------------------------------------*/
    function HeuresAbsTousServ($mois){
      $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $bdd->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $s = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $s->initiate();

      $dataPoints=array();
      $results = $bdd->query("SELECT * FROM services");
      $result=$results->fetchAll();
      $dataPoints=array();
      for($i=0;$i<count($result);$i++){
            $taux=$s->monthRateService($result[$i]['Num'],$mois);
            array_push($dataPoints,array("name"=>$result[$i]['Service'],"label"=>$result[$i]['Service'],"y"=>$taux,"z"=>$taux));
          }
    return $dataPoints;
    }
    /*----------------------------------------------*/
    function HeuresAbsTousDep($mois){
      $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $bdd->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $d = new SousDirecteurs($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $d->initiate();

      $dataPoints=array();
      $results = $bdd->query("SELECT * FROM department");
      $result=$results->fetchAll();
      $dataPoints=array();
      for($i=0;$i<count($result);$i++){
        $taux=$d->monthRateDepart($result[$i]['Id'],$mois);
        array_push($dataPoints,array("name"=>$result[$i]['Departement'],"label"=>$result[$i]['Departement'],"y"=>$taux));
          }
    return $dataPoints;
  }
  /*-----------------------------------------------*/
  function tableHistoMoisDepart($dd,$mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $dep = new SousDirecteurs($gettinginput['ID'],$gettinginput['Matricule'],$gettinginput['Login'], $gettinginput['Password']);
        $dep->initiate();
        $absMonth=$dep->departArrayByMonthTauxP($dd,$mois);
        $nbJours=count($absMonth[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$absMonth[1][$i],"y" =>$absMonth[0][$i]) );
            }

        return $dataPoints;
    }
  function tableHistoMoisTauxDepart($dd,$mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $dep = new SousDirecteurs($gettinginput['ID'],$gettinginput['Matricule'],$gettinginput['Login'], $gettinginput['Password']);
        $dep->initiate();
        $absMonth=$dep->departArrayByMonthTauxP($dd,$mois);
        $nbJours=count($absMonth[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t=0;
                if (!$dep->isWeekend($absMonth[1][$i])) { $t= 100-$absMonth[0][$i]; }
                array_push($dataPoints,array("label"=>$absMonth[1][$i],"y" =>$t));
            }

        return $dataPoints;
    }
    function tableHeuresAbsDepart($dd,$mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
   $emp = new SousDirecteurs($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absMois=$emp->departArrayByMonthAbs($dd,$mois);
        $nbJours=count($absMois[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t=0;
                if(!$emp->isWeekend($absMois[1][$i])) $t=$absMois[0][$i];
                array_push($dataPoints,array("y" =>$t,"label"=>$absMois[1][$i]));
            }
        return $dataPoints;
    }
    /*----------------------------------------------*/
    function HeuresAbsTousServDepart($dd,$mois){
      $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $bdd->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $s = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $s->initiate();

      $dataPoints=array();
      $results = $bdd->prepare("SELECT * FROM services WHERE Departement=:d");
      $results->execute(array('d' => $dd));
      $result=$results->fetchAll();
      $dataPoints=array();
      for($i=0;$i<count($result);$i++){
            $taux=$s->monthRateService($result[$i]['Num'],$mois);
            array_push($dataPoints,array("name"=>$result[$i]['Service'],"label"=>$result[$i]['Service'],"y"=>$taux,"z"=>$taux));
          }
    return $dataPoints;
    }
    /*----------------------------------------------*/
    function tableHistoMoisServ($serv,$mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absMonth=$emp->serviceArrayByMonth($serv,$mois);
        $nbJours=count($absMonth[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t=0;
                if (!$emp->isWeekend($absMonth[1][$i])) { $t= $absMonth[0][$i]; }
                array_push($dataPoints,array("label"=>$absMonth[1][$i],"y" =>$t));
            }

        return $dataPoints;
    }
    function tableHistoMoisTauxAbsServ($serv,$mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absMonth=$emp->serviceArrayByMonth($serv,$mois);
        $nbJours=count($absMonth[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t=0;
                if (!$emp->isWeekend($mois.'-'.($i+1))) { $t= 100-$absMonth[0][$i]; }
                array_push($dataPoints,array("label"=>$absMonth[1][$i],"y" =>$t) );
            }

        return $dataPoints;
    }
    function tableHeuresAbsServ($serv,$mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayByMonthAbs($serv,$mois);
        $nbJours=count($absPeriode[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t= $absPeriode[0][$i];
                if ($emp->isWeekend($absPeriode[1][$i])) { $t=0; }
                array_push($dataPoints,array("label"=>$absPeriode[1][$i],"y" =>$t));
            }
        return $dataPoints;
    }
    /*--------------------------------------------------------------*/
    function tableHistoMoisEmp($mat,$mois)
    {
        $emp=new Employe(0,0,' ',' ');
        $absMonth=$emp->simpleArrayByMonthPres($mat,$mois);
        $nbJours=count($absMonth);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$mois."-".($i+1),"y" =>$absMonth[$i]));
            }
        return $dataPoints;
    }
    function tableHeuresAbsEmp($mat,$mois)
    {
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayByMonthAbs($mat,$mois);
        $nbJours=count($abs);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$mois."-".($i+1),"y" =>$abs[$i]));
            }
        return $dataPoints;
    }
    /*----------------------------------------------------------------------------------------------------------*/
    function notifTauxAbsEcole($mois)
    {
      
      $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $t=explode('-',$mois);
        if($t[1]!="01") $moisPreced=$t[0]."-".(intval($t[1])-1);
        else $moisPreced=(intval($t[0])-1)."-".(12);
        $monthRate0=$ec->monthRateMajor($moisPreced);
        $monthRate1=$ec->monthRateMajor($mois);
        return $monthRate1-$monthRate0;
    }
    function depPlus($mois){
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $departs = $datab->query("SELECT * FROM department");
        $deps=$departs->fetchAll();$t0=0;
        for ($i=0; $i < count($deps); $i++) { 
          $t1=$ec->monthRateDepart($deps[$i]['Id'],$mois);
          if ($t1>$t0) {
            $t0=$t1;$d=$deps[$i]['Departement']." Avec Un Taux : ".$t0." %";
          }
        }
        return $d;
    }
    function servPlus($mois){
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $departs = $datab->query("SELECT * FROM services");
        $deps=$departs->fetchAll();$t0=0;
        for ($i=0; $i < count($deps); $i++) { 
          $t1=$ec->monthRateService($deps[$i]['Num'],$mois);
          if ($t1>$t0) {
            $t0=$t1;$d=$deps[$i]['Service']." Avec Un Taux : ".$t0." %";
          }
        }
        return $d;
    }
    function notifTauxAbsDep($mois,$d)
    {
      
      $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $t=explode('-',$mois);
        if($t[1]!="01") $moisPreced=$t[0]."-".(intval($t[1])-1);
        else $moisPreced=(intval($t[0])-1)."-".(12);
        $monthRate0=$ec->monthRateDepart($d,$moisPreced);
        $monthRate1=$ec->monthRateDepart($d,$mois);
        return $monthRate1-$monthRate0;
    }
    function servPlusD($mois,$d){
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $departs = $datab->prepare("SELECT * FROM services WHERE Departement=:d");
        $departs->execute(array('d' =>$d));
        $deps=$departs->fetchAll();$t0=0;
        for ($i=0; $i < count($deps); $i++) { 
          $t1=$ec->monthRateService($deps[$i]['Num'],$mois);
          if ($t1>$t0) {
            $t0=$t1;$d=$deps[$i]['Service']." Avec Un Taux : ".$t0." %";
          }
        }
        return $d;
    }
    function notifTauxAbsServ($mois,$d)
    {
      
      $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $t=explode('-',$mois);
        if($t[1]!="01") $moisPreced=$t[0]."-".(intval($t[1])-1);
        else $moisPreced=(intval($t[0])-1)."-".(12);
        $monthRate0=$ec->monthRateService($d,$moisPreced);
        $monthRate1=$ec->monthRateService($d,$mois);
        return $monthRate1-$monthRate0;
    }
    function EmpPlus($mois,$d){
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $departs = $datab->prepare("SELECT * FROM salary WHERE Service=:d");
        $departs->execute(array('d' =>$d));
        $deps=$departs->fetchAll();$t0=0;
        for ($i=0; $i < count($deps); $i++) { 
          $t1=$ec->monthRate($deps[$i]['Matricule'],$mois);
          if ($t1>$t0) {
            $t0=$t1;$d=$deps[$i]['Matricule']." | ".$deps[$i]['Nom']." ".$deps[$i]['Prenom']." Avec Un Taux : ".$t0." %";
          }
        }
        return $d;
    }
    function notifTauxAbsEmp($mois,$d)
    {
      
      $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
        $ec = new Directeur($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $ec->initiate();
        $t=explode('-',$mois);
        if($t[1]!="01") $moisPreced=$t[0]."-".(intval($t[1])-1);
        else $moisPreced=(intval($t[0])-1)."-".(12);
        $monthRate0=$ec->monthRate($d,$moisPreced);
        $monthRate1=$ec->monthRate($d,$mois);
        return $monthRate1-$monthRate0;
    }