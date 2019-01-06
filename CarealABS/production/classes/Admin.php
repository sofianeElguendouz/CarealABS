<?php

class Administrateur
{

protected $bdd;

public function __construct() 
{
	$this->bdd = new PDO('mysql:dbname=project;localhost=localhost','root','');


}


public function ajouter_employe ($post)
{
  $settings=[];
   $params=[];
  $inters=[];
   $inter;
   $setting;
   foreach($post as $k=>$v)
    {
   	$settings[]=$k;
   	$inters[]='?';
   	$params[]=$v;
    }
    $setting=implode(',',$settings);
    $inter=implode(',',$inters);
    $query=$this->bdd->prepare('INSERT INTO  salary('.$setting.') VALUES('.$inter.')');

    $query->execute($params);

}

public function supprimer_employer($id)
{
$req =$this->bdd->prepare('DELETE from salary WHERE ID = :id' );
$req->execute( array('id'=>$id));
}

public function modifier ($post,$id)
{
   $settings=[];
   $params=[];
   $setting;
   foreach($post as $k=>$v)
   {
   	$settings[]=$k.'=?';
   	$params[]=$v;
   }
   $params[]=$id;
   $setting=implode(',',$settings);
   $query=$this->bdd->prepare('UPDATE salary SET '.$setting.'WHERE ID=?');
   $query->execute($params);

}
//services
public function addDepartement ($nomdep)
{
	$req= $this->bdd->prepare('INSERT INTO department (Departement) VALUES (:dep) ');
	$req->execute  (array(':dep' => $nomdep )) ;

}
public function removeDepartement($id)
{
	$reqq = $this->bdd->prepare('DELETE from salary WHERE Departement =:dep');
	$reqq -> execute(array('dep'=>$id));
	$req1 = $this->bdd->prepare('DELETE from department where Id =:dep');
	$req1->execute(array('dep'=>$id));
}
public function addService ($nomserv,$depas)
{
	$req= $this->bdd->prepare('INSERT INTO services (Service,Departement) VALUES (:ser,:dep) ');
	$req->execute  (array('ser' => $nomserv,'dep'=>$depas )) ;

}
public function removeService($id)
{
	$reqq = $this->bdd->prepare('DELETE from salary WHERE Service =:dep');
	$reqq -> execute(array('dep'=>$id));
	$req1 = $this->bdd->prepare('DELETE from services where Num =:dep');
	$req1->execute(array('dep'=>$id));
}
}
