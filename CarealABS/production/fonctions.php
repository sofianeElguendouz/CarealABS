<?php 
	function depart($departement){
		$emp_dep=[];
		$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$reponse=$datab->prepare('SELECT * FROM department WHERE Id=?');
		$reponse->execute([$departement]);
		$dep = $reponse->fetch();
		$emp_dep['NomDep']=$dep['Departement'];
		$r = $datab->prepare("SELECT * FROM salary WHERE Departement=:s");
	    $r->execute(array('s' => $departement));
	    $t = $r->fetchAll();
	    $emp_dep['NbEmp']=count($t);
	    $rr = $datab->prepare("SELECT * FROM services WHERE Departement=:s");
	    $rr->execute(array('s' => $departement));
	    $tt = $rr->fetchAll();
	    $emp_dep['NbServ']=count($tt);
		return $emp_dep;
	}
	function select_employes_departement($departement){
		$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$reponse=$datab->prepare('SELECT * FROM salary WHERE Departement=? ORDER BY Nom');
		$reponse->execute([$departement]);
		$emp_dep = $reponse->fetchall();
		return $emp_dep;
	}
	function select_services_departement($departement){
		$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$reponse=$datab->prepare('SELECT * FROM services WHERE Departement=? ORDER BY Service');
		$reponse->execute([$departement]);
		$serv_dep = $reponse->fetchall();
		for ($i=0; $i < count($serv_dep); $i++) { 
			$r = $datab->prepare("SELECT * FROM salary WHERE Service=:s");
	        $r->execute(array('s' => $serv_dep[$i]['Num']));
	        $nb = $r->fetchAll();
	        $serv_dep[$i]['nb']=count($nb);
		}
		return $serv_dep;
	}
	/*--------------------------------------------------------------------*/
	function serv($service){
		$emp_serv=[];
		$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$reponse=$datab->prepare('SELECT * FROM services WHERE Num=?');
		$reponse->execute([$service]);
		$ser = $reponse->fetch();
		$emp_serv['NomServ']=$ser['Service'];
		$r = $datab->prepare("SELECT * FROM salary WHERE Service=:s");
	    $r->execute(array('s' => $service));
	    $t = $r->fetchAll();
	    $emp_serv['NbEmp']=count($t);
		return $emp_serv;
	}
	function select_employes_serv($service){
		$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$reponse=$datab->prepare('SELECT * FROM salary WHERE Service=? ORDER BY Nom');
		$reponse->execute([$service]);
		$emp_serv = $reponse->fetchall();
		return $emp_serv;
	}
?>