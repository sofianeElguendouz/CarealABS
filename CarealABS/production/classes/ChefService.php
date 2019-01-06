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
			$service = $userDetail1['Service'];

			// Récupération du tableau des employés dans le mm service et de degré inférieur.
			$gettingTab = $this->datab->prepare("SELECT * FROM salary WHERE ( salary.Service = ".$service." AND salary.Rang > ".$rang.")");
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

		public function serviceArrayByDay($service, $day) // Day est de format YYYY-MM-DD
		{
			$checkDay = [];
			if ($this->isWeekend($day)) {
				for ($i = 0; $i < 12; $i++) { 
					$checkDay[$i] = false;
				}
			}
			else {
				$results = $this->datab->prepare("SELECT MIN(HeureDebut) AS Min FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
				$results->execute(array(':service' => $service, ':day' => $day, ':rang' => $this->degree));
				$result = $results->fetch();
				$val = $result['Min'];
				if ($val == NULL) {
					$hour1 = 0;
				}
				else{
					$hour1 = intval(substr($val, 0, 2));
				}
				$results = $this->datab->prepare("SELECT MAX(HeureFin) AS Max FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
				$results->execute(array(':service' => $service, ':day' => $day, ':rang' => $this->degree));
				$result = $results->fetch();
				$val = $result['Max'];
				if ($val == NULL) {
					$hour2 = 0;
				}
				else{
					$hour2 = intval(substr($val, 0, 2));
				}
				for ($i = 0; $i < 12; $i++) { 
					if (($i + 6 >= $hour1) and ($i + 6 < $hour2)) {
						$checkDay[$i] = true;
					}
					else
					{
						$checkDay[$i] = false;
					}
				}
			}
			return $checkDay;
		}

		/** La fonction qui renvoie le nombre des heures du travail de tout le service pendant une journée donnée */

		public function getHourService($service, $day)
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$results = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
			$results->execute(array(':service' => $service, ':day' => $day, ':rang' => $this->degree));
			$result = $results->fetch();
			$sumHours = $result['Sum'];
			if ($sumHours == NULL) {
				$sumHours = 0;
			}
			return $sumHours;
		}
		/** La fonction qui renvoie le nombre d'employés absents pendant la journée donnée */

		public function serviceAbsent($service, $day) // $day a le format YYYY-MM-DD
		{
			return ($this->numberEmployee($service) - $this->servicePresent($service, $day));
		}

		/** La fonction qui renvoie le nombre d'employés présents pendant la journée donnée pour le service */

		public function servicePresent($service, $day) // $day a le format YYYY-MM-DD
		{
			$gettingTab = $this->datab->prepare("SELECT schedule.Matricule, salary.Service FROM schedule, salary WHERE (salary.Service = :service AND schedule.Date = :day AND schedule.Matricule = salary.Matricule AND salary.Rang > :rang)");
			$gettingTab->execute(array(':service' => $service, ':day' => $day, ':rang' => $this->degree));
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));
		}

		/** La fonction qui renvoie le nombre d'employés absents pendant le mois donné pour le service */

		public function serviceAbsentMonth($service, $month) // $month a le format YYYY-MM
		{
			return ($this->numberEmployee($service) - $this->servicePresentMonth($service, $month));
		}

		/** La fonction qui renvoie le nombre d'employés présents pendant le mois donné pour le service */

		public function servicePresentMonth($service, $month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$firstDay = $format.'-01';
			$lastDay = $format.'-'.cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$gettingTab = $this->datab->prepare("SELECT schedule.Matricule, salary.Service FROM schedule, salary WHERE (salary.Service = :service AND (schedule.Date BETWEEN :day1 AND :day2) AND schedule.Matricule = salary.Matricule AND salary.Rang > :rang)");
			$gettingTab->execute(array(':service' => $service, ':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));
		}

		/** La fonction qui renvoie le nombre d'employés absents pendant l'année donnée pour le service */

		public function serviceAbsentYear($service, $year) // $year a le format YYYY
		{
			return ($this->numberEmployee($service) - $this->servicePresentYear($service, $year));
		}

		/** La fonction qui renvoie le nombre d'employés présents pendant l'année donnée pour le service */

		public function servicePresentYear($service, $year) // $year a le format YYYY
		{
			$format = substr($year, 0, 4);
			$firstDay = $format.'-01-01';
			$lastDay = $format.'-12-31';
			$gettingTab = $this->datab->prepare("SELECT schedule.Matricule, salary.Service FROM schedule, salary WHERE (salary.Service = :service AND (schedule.Date BETWEEN :day1 AND :day2) AND schedule.Matricule = salary.Matricule AND salary.Rang > :rang)");
			$gettingTab->execute(array(':service' => $service, ':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));
		}

		/** La fonction qui renvoie le nombre d'employés absents pendant la plage donnée pour le service */

		public function serviceAbsentBand($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->numberEmployee($service) - $this->servicePresentBand($service, $begin, $end));
		}

		/** La fonction qui renvoie le nombre d'employés présents pendant la plage donnée pour le service */

		public function servicePresentBand($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$gettingTab = $this->datab->prepare("SELECT schedule.Matricule, salary.Service FROM schedule, salary WHERE (salary.Service = :service AND (schedule.Date BETWEEN :day1 AND :day2) AND schedule.Matricule = salary.Matricule AND salary.Rang > :rang)");
			$gettingTab->execute(array(':service' => $service, ':day1' => $begin, ':day2' => $end, ':rang' => $this->degree));
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));
		}
		/** La fonction qui renvoie un tableau des Taux de travail avec comme indices les jours du mois pour le service en général */

		public function serviceArrayByMonth($service, $month) // $month est de format YYYY-MM
		{
			$checkMonth = [];
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) {
				$checkMonth[0][$i] = $this->getHourService($service, $format.'-'.($i + 1))/($this->numberEmployee($service)*8)*100;
				$checkMonth[1][$i] = $month."-".($i+1);
				$i++;
			}
			return $checkMonth;
		}
		public function serviceArrayByMonthNbHPres($service, $month) // $month est de format YYYY-MM
		{
			$checkMonth = [];
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) {
				$checkMonth[0][$i] = $this->getHourService($service, $format.'-'.($i + 1));
				$checkMonth[1][$i] = $month."-".($i+1);
				$i++;
			}
			return $checkMonth;
		}
		/** La fonction qui renvoie un tableau des heures d'absences avec comme indices les jours du mois pour le service en général */

		public function serviceArrayByMonthAbs($service, $month) // $month est de format YYYY-MM
		{
			$checkMonth = [];
			$tab = $this->serviceArrayByMonthNbHPres($service, $month);
			$numberEmp = $this->numberEmployee($service);
			for ($i = 0; $i < count($tab[0]); $i++) { 
				if($this->isWeekend($month.'-'.($i+1))) {$checkMonth[0][$i]=0;}
				else {$checkMonth[0][$i] = 8 * $numberEmp - $tab[0][$i];}
				$checkMonth[1][$i] = $month."-".($i+1);
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie le nombre des employés dans le service donné */

		public function numberEmployee($service)
		{
			/*$gettingTab = $this->datab->prepare("SELECT ".$this->tablogin.".Matricule, ".$this->tablogin.".Service FROM ".$this->tablogin." WHERE (".$this->tablogin.".Service = ".$service." AND ".$this->tablogin.".Rang > ".$this->degree.")");
			$gettingTab->execute();
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);*/
			$tab=$this->datab->prepare("SELECT * FROM salary WHERE Service=:serv");
			$tab->execute(array(':serv' => $service));
			$tabEmployee=$tab->fetchAll();
			return (count($tabEmployee));
		}

		/** La fonction qui renvoie le taux de travaille pour un service en général pendant un mois */

		public function getRateService($service, $month)
		{
			$format = substr($month, 0, 7);
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':service' => $service, ':day1' => ($format.'-01'), ':day2' => ($format.'-'.$taille), ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			$nb=$this->numberEmployee($service);
			if($nb!=0) return (($sumHour / (8 * ($taille - $this->nbWeekendMois($month)) *$nb)) * 100);
			else return 0;
		}

		/** La fonction qui renvoie un tableau des taux de travail avec comme indices les mois d'une année pour le service en général */

		public function serviceArrayByYear($service, $year) //$year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRateService($service, $yearOnly.'-'.($i + 1));
			}
			return $checkYear;
		}
		/** La fonction qui renvoie un tableau des taux d'absences avec comme indices les mois d'une année pour le service en général */

		public function serviceArrayByYearAbs($service, $year) //$year a le format YYYY
		{
			$checkYear = [];
			$tab = $this->serviceArrayByYear($service, $year);
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = 100 - $tab[$i];
			}
			return $checkYear;
		}

		/** La fonction qui renvoie un tableau des heures d'absences avec comme indices les mois d'une année pour le service en général */

		public function serviceArrayYearNbHeuresAbs($service, $year) //$year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->serviceHeuresAbsMois($service, $yearOnly.'-'.($i + 1));
			}
			return $checkYear;
		}

		/** La fonction qui renvoie le taux d'absences d'un service pendant un jour */

		public function dayRateService($service, $day)
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$presenceRate = ($this->getHourService($service, $day) / (8 * $this->numberEmployee($service)) * 100);
			return (100 - $presenceRate);
		}

		/** La fonction qui renvoie le taux d'absences d'un service pendant un mois */

		public function monthRateService($service, $month)
		{
			return (100 - $this->getRateService($service, $month));
		}

		/** La fonction qui renvoie le taux d'absences d'un service pendant une année */

		public function yearRateService($service, $year)
		{
			$sumMonthRate = 0;
			$tab = $this->serviceArrayByYear($service, $year);
			foreach ($tab as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}
		/*---------------------------------------------------------------------------------------------------------------*/

		/****************************** Pour la présence *******************************/

		/** La fonction qui renvoie le taux de présences d'un service pendant un jour */

		public function dayRateServicePre($service, $day){
			$presenceRate = ($this->getHourService($service, $day) / (8 * $this->numberEmployee($service)) * 100);
			return ($presenceRate);
		}

		/** La fonction qui renvoie le taux de présences d'un service pendant un mois */

		public function monthRateServicePre($service, $month){
			return ($this->getRateService($service, $month));
		}

		/** La fonction qui renvoie le taux de présences d'un service pendant une année */

		public function yearRateServicePre($service, $year){
			$sumMonthRate = 0;
			foreach ($this->serviceArrayByYear($service, $year) as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return ($sumMonthRate / 12);
		}
		/*********************************************************************************************************************/
		/************************************************ Traitement pour une plage donnée ***********************************/
		/*********************************************************************************************************************/

		/** La fonction qui renvoie le nombre des heures d'absences du service dans la plage donnée */

		public function bandService($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :beg AND :en) AND salary.Rang > :rang)");
			$request->execute(array(':service' => $service, ':beg' => $begin, ':en' => $end, ':rang' => $this->degree));
			$data = $request->fetch();
			$sum = $data['Sum'];
			if ($sum == NULL) {
				$sum = 0;
			}
			return (8 * ($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->numberEmployee($service) - $sum);
		}

		/** La fonction qui renvoie le taux d'absence du service dans la plage donnée */

		public function bandRateService($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (($this->bandService($service, $begin, $end)) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->numberEmployee($service) * 8) * 100);
		}

		/** La fonction qui renvoie le taux de présence du service dans la plage donnée */

		public function bandRatePresService($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (100 - $this->bandRateService($service, $begin, $end));
		}

		/** La fonction qui renvoie un tableau de taux de présences avec comme indices les jours dans la plage donnée pour le service */

		public function serviceArrayBand($service, $begin, $end){ // $begin et $end ont le fomat YYYY-MM-DD
			$taille = $this->nbDays($begin, $end);
			$checkBand = [];
			$day = $begin;
			$numberEmp = $this->numberEmployee($service);
			for ($i = 0; $i < $taille; $i++) { 
				$checkBand[0][$i] = $this->getHourService($service, $day) / (8 * $numberEmp) * 100;
				$checkBand[1][$i]=$day;
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/*la fct qui renvoie la somme des heures absentées dans un jour*/
		public function getHourAbsService($service,$day){
			$sumHours = 0;
			if (!$this->isWeekend($day)) {
			$gettingTab = $this->datab->prepare("SELECT salary.Matricule, salary.Service FROM salary WHERE (salary.Service = ".$service." AND salary.Rang > ".$this->degree.")");
			$gettingTab->execute();
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			foreach ($tabEmployee as $object) {
				 $sumHours += 8-$this->getHour($object->Matricule,$day);
			}}
			return $sumHours;
		}
		/*la fct qui renvoie un tableau des heures absentées dans la plage donnée*/
		public function serviceArrayBandNbHeuresAbs($service, $begin, $end){ // $begin et $end ont le fomat YYYY-MM-DD
			$taille = $this->nbDays($begin, $end);
			$checkBand = [];
			$day = $begin;
			$numberEmp = $this->numberEmployee($service);
			for ($i = 0; $i < $taille; $i++) { 
				$checkBand[0][$i] = $this->getHourAbsService($service,$day);
				$checkBand[1][$i]=$day;
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}
		/**************************************************************************************************/
		/** Les méthodes concernants les traitemets faits avec prise en considération les justifications **/
		/**************************************************************************************************/

		/** La fonction qui renvoie le nombre des justifications dans un service pendant une journée donnée */

		public function nbJustDay($service, $day) // $day a le format YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE justification.Date = :day AND salary.Service = :service AND salary.Matricule = justification.Matricule");
			$query->execute(array(':day' => $day, ':service' => $service));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le nombre des justifications dans un service pendant un mois donné */

		public function nbJustMonthService($service, $month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$firstDay = $format.'-01';
			$lastDay = $format.'-'.cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Service = :service) AND (salary.Matricule = justification.Matricule)");
			$query->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':service' => $service));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le nombre des justifications dans un service pendant une année donnée */

		public function nbJustYearService($service, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Service = :service) AND (salary.Matricule = justification.Matricule)");
			$query->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':service' => $service));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}
		/** La fonction qui renvoie le nombre des justifications dans un service durant une plage donnée */

		public function nbJustBandService($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Service = :service) AND (salary.Matricule = justification.Matricule)");
			$query->execute(array(':day1' => $begin, ':day2' => $end, ':service' => $service));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le taux des justifications dans un service dans une journée donnée */

		public function rateJustDay($service, $day) // $day a le format YYYY-MM-DD
		{
			return ($this->nbJustDay($service, $day) / ($this->numberEmployee($service)) * 100);
		}

		/** La fonction qui renvoie le taux des justifications dans un service dans un mois donné */

		public function rateJustMonthService($service, $month) // $month a le format YYYY-MM
		{
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$nbDays = cal_days_in_month(CAL_GREGORIAN, $monthOnly, $yearOnly) - $this->nbWeekendMois($month);
			return ($this->nbJustMonthService($service, $month) / ($this->numberEmployee($service) * $nbDays) * 100);
		}

		/** La fonction qui renvoie le taux des justifications dans un service dans une année donnée */

		public function rateJustYearService($service, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumRates = 0;
			for ($i = 1; $i <= 12 ; $i++) { 
				$sumRates += $this->rateJustMonthService($service, $yearOnly.'-'.$i);
			}
			return ($sumRates / 12);
		}

		/** La fonction qui renvoie le taux des justifications dans un service durant une plage donnée */

		public function rateJustBandService($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->nbJustBandService($service, $begin, $end) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->numberEmployee($service)) * 100);
		}
		/*--------------------------------------------------------------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------------------------------*/
		public function serviceHeuresAbsJour($service, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {return 0;}
			else return (8 * $this->numberEmployee($service) - $this->getHourService($service, $day));
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un service pendant un jour */

		public function serviceHeuresTravJour($service, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {return 0;}
			else return ($this->getHourService($service, $day));
		}
		/*---------------------------------------------------------------------------------------------------------------*/
		public function serviceHeuresAbsMois($service,$month)
		{
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$sumHours = $this->serviceHeuresTravMois($service, $month);
			return (8 * ($taille - $this->nbWeekendMois($month)) * $this->numberEmployee($service) - $sumHours);
		}
		public function serviceHeuresTravMois($service,$month)
		{
			$format = substr($month, 0, 7);
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':service' => $service, ':day1' => ($format.'-01'), ':day2' => ($format.'-'.$taille), ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return $sumHour;
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un service pendant une année */

		public function serviceHeuresAbsAnnee($service, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumHours = $this->serviceHeuresTravAnnee($service, $year); // Les heures de travail.
			$nbDays = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$nbDays += (cal_days_in_month(CAL_GREGORIAN, $i, $yearOnly) - $this->nbWeekendMois($yearOnly.'-'.$i));
			}
			return ( 8 * $nbDays * $this->numberEmployee($service) - $sumHours);
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un service pendant une année */

		public function serviceHeuresTravAnnee($service, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':service' => $service, ':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return $sumHour;
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un service pendant une période */

		public function serviceHeuresAbsPeriode($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->bandService($service, $begin, $end));
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un service pendant une période */

		public function serviceHeuresTravPeriode($service, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Service = :service AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :beg AND :en) AND salary.Rang > :rang)");
			$request->execute(array(':service' => $service, ':beg' => $begin, ':en' => $end, ':rang' => $this->degree));
			$data = $request->fetch();
			$sum = $data['Sum'];
			if ($sum == NULL) {
				$sum = 0;
			}
			return $sum;
		}
	}
?>