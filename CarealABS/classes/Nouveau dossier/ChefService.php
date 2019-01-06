<?php

	require("Employe.php");

	/******************************************
	* La classe concernant les chefs de service
	*******************************************/

	class ChefService extends Employe // Validé.
	{
		protected $numService; // L'attribut indiquant le N° de service.
		protected $degree; // L'attribut indiquant le degree ou bien le rang.
		protected $tabEmployee; // Un tableau qui contient les employés appartenants à ce service et qui sont de degree inférieur.

		/** La fonction à appeler avant tous traitements */

		public function initiate(){

			// La récupération du rang.
			$selecting = $this->datab->prepare("SELECT * FROM salary WHERE ID = :id");
			$selecting->execute(array(':id' => $this->id));
			$userDetail1 = $selecting->fetch();
			$rang = $userDetail1['Rang'];

			// La récupération du service.
			$select = $this->datab->prepare("SELECT * FROM salary WHERE Matricule = :matricule");
			$select->execute(array(':matricule' => $this->matricule));
			$userDetail2 = $select->fetch();
			$service = $userDetail2['Departement'];

			// Récupération du tableau des employés dans le mm service et de degré inférieur.
			$gettingTab = $this->datab->prepare("SELECT * FROM salary, list WHERE ( list.Deppartement = ".$service." AND salary.Rang > ".$rang." AND salary.Matricule = list.Matricule)");
			$gettingTab->execute();

			// Maintenant le résultat $gettingTab contient le tableau qu'on veut récupérer.
			$this->numService = $service;
			$this->degree = $rang;
			$this->tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
		}

		/************************************************************/
		/** Cette classe utilise les méthodes de la classe employé **/
		/************************************************************/

		/****************************************************/
		/** Les méthodes concernants le Service en général **/
		/****************************************************/

		/** Les Getters */

		public function getService(){
			return $this->numService;
		}

		public function getDegree(){
			return $this->degree;
		}

		/** La fonction qui renvoie un tableau de boolean avec comme indices les heures de travail dans une journée pour le service en général */

		public function serviceArrayByDay($service, $day){ // Day est de format YYYY-MM-DD
			$gettingTab = $this->datab->prepare("SELECT salary.Matricule, list.Deppartement FROM salary, list WHERE (list.Deppartement = ".$service." AND salary.Rang > ".$this->degree." AND salary.Matricule = list.Matricule)");
			$gettingTab->execute();
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			$checkDay = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkDay[$i] = false;
			}
			foreach ($tabEmployee as $object) {
				$tab = $this->simpleArrayByDay($object->Matricule, $day);
				for ($i = 0; $i < 12; $i++) { 
					$checkDay[$i] = $checkDay[$i] or $tab[$i];
				}
			}
			return $checkDay;
		}

		/** La fonction qui renvoie le nombre des heures du travail de tout le service pendant une journée donnée */

		public function getHourService($service, $day){
			$sumHours = 0;
			$gettingTab = $this->datab->prepare("SELECT salary.Matricule, list.Deppartement FROM salary, list WHERE (list.Deppartement = ".$service." AND salary.Rang > ".$this->degree." AND salary.Matricule = list.Matricule)");
			$gettingTab->execute();
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			foreach ($tabEmployee as $object) {
				$sumHours += $this->getHour($this->simpleArrayByDay($object->Matricule, $day));
			}
			return $sumHours;
		}

		/** La fonction qui renvoie un tableau des heures de travail avec comme indices les jours du mois pour le service en général */

		public function serviceArrayByMonth($service, $month){ // $month est de format YYYY-MM
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			if ($monthOnly == 1 or $monthOnly == 3 or $monthOnly == 5 or $monthOnly == 7 or $monthOnly == 8 or $monthOnly == 10 or $monthOnly == 12) {
				$taille = 31;
			}
			elseif ($monthOnly == 4 or $monthOnly == 6 or $monthOnly == 9 or $monthOnly == 11) {
				$taille = 30;
			}
			else{
				$taille = 28;
			}
			$checkMonth = [];
			for ($i = 0; $i < $taille; $i++) { 
				$checkMonth[$i] = $this->getHourService($service, $format.'-'.($i + 1));
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie le nombre des employés dans le service donné */

		public function numberEmployee($service){
			$gettingTab = $this->datab->prepare("SELECT ".$this->tablogin.".Matricule, ".$this->tablist.".Deppartement FROM ".$this->tablogin.", ".$this->tablist." WHERE (".$this->tablist.".Deppartement = ".$service." AND ".$this->tablogin.".Rang > ".$this->degree." AND ".$this->tablogin.".Matricule = ".$this->tablist.".Matricule)");
			$gettingTab->execute();
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			/*$nbEmployee = 0;
			foreach ($tabEmployee as $object) {
				$nbEmployee++;
			}
			return $nbEmployee;*/
			return (count($tabEmployee));
		}

		/** La fonction qui renvoie le taux de travaille pour un service en général pendant un mois */

		public function getRateService($service, $month){
			$sumHours = 0;
			$tabEmployee = $this->serviceArrayByMonth($service, $month);
			foreach ($tabEmployee as $Hours) {
				$sumHours += $Hours;
			}
			return (($sumHours / (8 * count($tabEmployee) * $this->numberEmployee($service))) * 100);
		}

		/** La fonction qui renvoie un tableau des taux de travail avec comme indices les mois d'une année pour le service en général */

		public function serviceArrayByYear($service, $year){ //$year a le format YYYY
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRateService($service, $yearOnly.'-'.($i + 1));
			}
			return $checkYear;
		}

		/** La fonction qui renvoie le taux d'absences d'un service pendant un jour */

		public function dayRateService($service, $day){
			$presenceRate = ($this->getHourService($service, $day) / (8 * $this->numberEmployee($service)) * 100);
			return (100 - $presenceRate);
		}

		/** La fonction qui renvoie le taux d'absences d'un service pendant un mois */

		public function monthRateService($service, $month){
			return (100 - $this->getRateService($service, $month));
		}

		/** La fonction qui renvoie le taux d'absences d'un service pendant une année */

		public function yearRateService($service, $year){
			$sumMonthRate = 0;
			foreach ($this->serviceArrayByYear($service, $year) as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}

		/*************************************************************************************************/
		/** Les méthodes concernants les détails d'un employé qlq dans le service de ce Chef de service **/
		/*************************************************************************************************/

		// Pour ceci il suffit d'appeler les méthodes héritées de la classe Employee en passant les paramètres qu'il faut.
	}
?>