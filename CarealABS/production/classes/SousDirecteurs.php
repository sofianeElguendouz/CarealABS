<?php

	require("classes/ChefService.php");

	/***************************************
	* La classe concernant le sous directeur
	****************************************/

	class SousDirecteurs extends ChefService // Validé.
	{
		protected $depart; // Le déppartement de ce sous-directeur.
		protected $tabService; // Le tableau qui contient tous les service.
		/** La fonction à appeler avant tous traitements */
		public function initiate(){

			// L'appel de la fonction du parent.
			parent::initiate();			

			// Récupération du déppartement de ce sous-directeur.
			$gettingDepart = $this->datab->prepare("SELECT * FROM salary WHERE salary.Matricule = :matricule)");
			$gettingDepart->execute(array(":matricule" => $this->matricule));
			$detail = $gettingDepart->fetch();
			$depart = $detail['Departement'];

			// Récupération du tableau des services.
			$gettingServices = $this->datab->prepare("SELECT services.Num FROM services WHERE Departement = ".$depart);
			$gettingServices->execute();

			// Maintenant le résultat $gettingServices contient le tableau qu'on veut récupérer.
			$this->depart = $depart;
			$this->tabService = $gettingServices->fetchAll(PDO::FETCH_OBJ);
		}

		/********************************************************************/
		/** Cette classe utilise les méthodes de la classe Chef de Service **/
		/********************************************************************/

		/*********************************************************/
		/** Les méthodes concernants le Déppartement en général **/
		/*********************************************************/

		/** Les Getters */

		public function getDepart(){
			return $this->depart;
		}

		/** La fonction qui renvoie un tableau de boolean avec comme indices les heures de travail dans une journée pour le déppartement en général */

		public function departArrayByDay($depart, $day) // $day a le format YYYY-MM-DD
		{
			$checkDay = [];
			if ($this->isWeekend($day)) {
				for ($i = 0; $i < 12; $i++) { 
					$checkDay[$i] = false;
				}
			}
			else {
				$results = $this->datab->prepare("SELECT MIN(HeureDebut) AS Min FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
				$results->execute(array(':depart' => $depart, ':day' => $day, ':rang' => $this->degree));
				$result = $results->fetch();
				$val = $result['Min'];
				if ($val == NULL) {
					$hour1 = 0;
				}
				else{
					$hour1 = intval(substr($val, 0, 2));
				}
				$results = $this->datab->prepare("SELECT MAX(HeureFin) AS Max FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
				$results->execute(array(':depart' => $depart, ':day' => $day, ':rang' => $this->degree));
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

		/** La fonction qui renvoie le nombre des heures du travail de tous le déppartement pendant une journée donnée */

		public function getHourDepart($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$results = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
			$results->execute(array(':depart' => $depart, ':day' => $day, ':rang' => $this->degree));
			$result = $results->fetch();
			$sumHours = $result['Sum'];
			if ($sumHours == NULL) {
				$sumHours = 0;
			}
			return $sumHours;
		}
		/** La fonction qui renvoie le nombre d'employés absents pendant la journée donnée pour le déppartement */

		public function departAbsent($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {return 0;}
			else return ($this->departNumberEmployee($depart) - $this->departPresent($depart, $day));
		}

		/** La fonction qui renvoie le nombre d'employés présents pendant la journée donnée pour le déppartement */

		public function departPresent($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {return 0;}
			else{
			$gettingTab = $this->datab->prepare("SELECT schedule.Matricule, salary.Service FROM schedule, salary WHERE (salary.Departement = :depart AND schedule.Date = :day AND schedule.Matricule = salary.Matricule AND salary.Rang > :rang)");
			$gettingTab->execute(array(':depart' => $depart, ':day' => $day, ':rang' => $this->degree));
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));}
		}

		/** La fonction qui renvoie un tableau des heures de travail avec comme indices les jours du mois pour le déppartement en général */

		public function departArrayByMonth($depart, $month) // $month a le format YYYY-MM
		{
			$checkMonth = [];
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) { 
				$checkMonth[0][$i] = $this->getHourDepart($depart, $format.'-'.($i + 1));
				$checkMonth[1][$i] = $month."-".($i+1);
				$i++;
			}
			return $checkMonth;
		}
		/** La fonction qui renvoie un tableau des heures d'absences avec comme indices les jours du mois pour le déppartement en général */

		public function departArrayByMonthAbs($depart, $month) // $month a le format YYYY-MM
		{
			$checkMonth = [];
			$tab = $this->departArrayByMonth($depart, $month);
			$numberEmp = $this->departNumberEmployee($depart);
			$format = substr($month, 0, 7);
			$day = $format.'-01';
			for ($i = 0; $i < count($tab[0]); $i++) {
				if ($this->isWeekend($day)) {
					$checkMonth[0][$i] = 0;
				}
				else{
					$checkMonth[0][$i] = $this->departHeuresAbsJour($depart, $day);
				}
				$checkMonth[1][$i] = $day;
				$day = $this->nextDay($day);
			}
			return $checkMonth;
		}
		/** La fonction qui renvoie un tableau des Taux de travail avec comme indices les jours du mois pour le déppartement en général */

		public function departArrayByMonthTauxP($depart, $month) // $month a le format YYYY-MM
		{
			$checkMonth = [];
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) { 
				if(!$this->isWeekend($month."-".($i+1))) $checkMonth[0][$i] = 100-$this->dayRateDepart($depart, $format.'-'.($i + 1));else $checkMonth[0][$i] =0;
				$checkMonth[1][$i] = $month."-".($i+1);
				$i++;
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie le nombre des employés dans le déppartement donné */

		public function departNumberEmployee($depart)
		{
			$gettingTab = $this->datab->prepare("SELECT ".$this->tablogin.".Matricule, ".$this->tablogin.".Service FROM ".$this->tablogin." WHERE (".$this->tablogin.".Departement = ".$depart." AND ".$this->tablogin.".Rang > ".$this->degree.")");
			$gettingTab->execute();
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));
		}

		/** La fonction qui renvoie le taux de travaille pour le déppartement en général pendant un mois */

		public function getRateDepart($depart, $month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':depart' => $depart, ':day1' => ($format.'-01'), ':day2' => ($format.'-'.$taille), ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			$nb=$this->departNumberEmployee($depart);
			if($nb!=0) return (($sumHour / (8 * ($taille - $this->nbWeekendMois($month)) *$nb)) * 100);
			else return 0;
		}

		/** La fonction qui renvoie un tableau des taux de travail avec comme indices les mois d'une année pour le déppartement en général */

		public function departArrayByYear($depart, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRateDepart($depart,$yearOnly.'-'.($i+1));
			}
			return $checkYear;
		}
		/** La fonction qui renvoie un tableau des taux d'absences avec comme indices les mois d'une année pour le déppartement en général */

		public function departArrayByYearAbs($depart, $year) // $year a le format YYYY
		{
			$checkYear = [];
			$tab = $this->departArrayByYear($depart, $year);
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = 100 - $tab[$i];
			}
			return $checkYear;
		}

		public function departArrayByYearHAbs($depart,$year)
		{
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->departHeuresAbsMois($depart, $year.'-'.($i+1));
			}
			return $checkYear;
		}


		/** La fonction qui renvoie le taux d'absences du déppartement pendant un jour */

		public function dayRateDepart($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$presenceRate = ($this->getHourDepart($depart, $day) / (8 * $this->departNumberEmployee($depart)) * 100);
			return (100 - $presenceRate);
		}

		/** La fonction qui renvoie le taux d'absences du déppartement pendant un mois */

		public function monthRateDepart($depart, $month) // $month a le format YYYY-MM
		{
			return (100 - $this->getRateDepart($depart, $month));
		}

		/** La fonction qui renvoie le taux d'absences du déppartement pendant une année */

		public function yearRateDepart($depart, $year) // $year a le format YYYY
		{
			$sumMonthRate = 0;
			$table = $this->departArrayByYear($depart, $year);
			foreach ($table as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}

		/****************************** Pour la présence *******************************/

		/** La fonction qui renvoie le taux de présences du déppartement pendant un jour */

		public function dayRateDepartPre($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}else{
			$presenceRate = ($this->getHourDepart($depart, $day) / (8 * $this->departNumberEmployee($depart)) * 100);
			return ($presenceRate);}
		}

		/** La fonction qui renvoie le taux de présences du déppartement pendant un mois */

		public function monthRateDepartPre($depart, $month) // $month a le format YYYY-MM
		{
			return ($this->getRateDepart($depart, $month));
		}

		/** La fonction qui renvoie le taux de présences du déppartement pendant une année */

		public function yearRateDepartPre($depart, $year) // $year a le format YYYY
		{
			$sumMonthRate = 0;
			$table = $this->departArrayByYear($depart, $year);
			foreach ($table as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return ($sumMonthRate / 12);
		}

		/*--------------------------------------------------------------------------------------------------------------------*/
		/**************************************** Traitement pour une plage donnée ********************************************/
		/*--------------------------------------------------------------------------------------------------------------------*/


		/** La fonction qui renvoie le nombre des heures d'absences du déppartement dans la plage donnée */

		public function bandDepart($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :beg AND :en) AND salary.Rang > :rang)");
			$request->execute(array(':depart' => $depart, ':beg' => $begin, ':en' => $end, ':rang' => $this->degree));
			$data = $request->fetch();
			$sum = $data['Sum'];
			if ($sum == NULL) {
				$sum = 0;
			}
			return (8 * ($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->departNumberEmployee($depart) - $sum);
		}

		/** La fonction qui renvoie le taux d'absence du déppartement dans la plage donnée */

		public function bandRateDepart($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (($this->bandDepart($depart, $begin, $end)) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->departNumberEmployee($depart) * 8) * 100);
		}

		/** La fonction qui renvoie le taux de présence du déppartement dans la plage donnée */

		public function bandRatePresDepart($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (100 - $this->bandRateDepart($depart, $begin, $end));
		}

		/** La fonction qui renvoie un tableau de taux de présences avec comme indices les jours dans la plage donnée pour le déppartement */

		public function departArrayBand($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$taille = $this->nbDays($begin, $end);
			$checkBand = [];
			$day = $begin;
			$numberEmp = $this->departNumberEmployee($depart);
			for ($i = 0; $i < $taille; $i++) { 
				$checkBand[$i] = $this->getHourDepart($depart, $day) / (8 * $numberEmp) * 100;
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/** La fonction qui renvoie un tableau de taux d'absences avec comme indices les jours dans la plage donnée pour le déppartement */

		public function departArrayBandAbs($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$tab = $this->departArrayBand($depart, $begin, $end);
			$checkBand = [];
			$day = $begin;
			for ($i = 0; $i < count($tab); $i++) {
				if ($this->isWeekend($day)) {
					$checkBand[$i] = 0;
				}
				else{
					$checkBand[$i] = 100 - $tab[$i];
				}
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/*la fct qui renvoie la somme des heures absentées dans un jour*/
		/*public function getHourAbsDepJour($depart,$day){
			$sumHours = 0;
			$gettingTab = $this->datab->prepare("SELECT * FROM salary WHERE Departement = :depart AND Rang >:deg");
			$gettingTab->execute(array('depart' => $depart,'deg'=> $this->degree));
			$tabEmployee = $gettingTab->fetchAll();
			for ($i=0;$i<count($tabEmployee);$i++) {
				$sumHours += 8-$this->getHour($tabEmployee[$i]['Matricule'],$day);
			}
			return $sumHours;
		}*/

		/*la fct qui renvoie un tableau des heures absentées dans la plage donnée*/
		public function departArrayBandNbHeuresAbs($depart, $begin, $end){ // $begin et $end ont le fomat YYYY-MM-DD
			$tab = $this->departArrayBand($depart, $begin, $end);
			$checkBand = [];
			$day = $begin;
			for ($i = 0; $i < count($tab); $i++) {
				if ($this->isWeekend($day)) {
					$checkBand[$i] = 0;
				}
				else{
					$checkBand[$i] = $this->departHeuresAbsJour($depart,$day);
				}
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/**************************************************************************************************/
		/** Les méthodes concernants les traitemets faits avec prise en considération les justifications **/
		/**************************************************************************************************/

		/** La fonction qui renvoie le nombre des justifications dans un déppartement pendant une journée donnée */
		
		public function nbJustDayDepart($depart, $day) // $day a le format YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE justification.Date = :day AND salary.Departement = :depart AND salary.Matricule = justification.Matricule");
			$query->execute(array(':day' => $day, ':depart' => $depart));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le nombre des justifications dans un déppartement pendant un mois donné */

		public function nbJustMonthDepart($depart, $month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$firstDay = $format.'-01';
			$lastDay = $format.'-'.cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Departement = :depart) AND (salary.Matricule = justification.Matricule)");
			$query->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':depart' => $depart));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le nombre des justifications dans un déppartement pendant une année donnée */

		public function nbJustYearDepart($depart, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Departement = :depart) AND (salary.Matricule = justification.Matricule)");
			$query->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':depart' => $depart));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le taux des justifications dans un déppartement dans une journée donnée */

		public function rateJustDayDepart($depart, $day) // $day a le format YYYY-MM-DD
		{
			return ($this->nbJustDayDepart($depart, $day) / ($this->departNumberEmployee($depart)) * 100);
		}

		/** La fonction qui renvoie le taux des justifications dans un déppartement dans un mois donné */

		public function rateJustMonthDepart($depart, $month) // $month a le format YYYY-MM
		{
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$nbDays = cal_days_in_month(CAL_GREGORIAN, $monthOnly, $yearOnly) - $this->nbWeekendMois($month); // On récupère les jours de travail.
			return ($this->nbJustMonthDepart($depart, $month) / ($this->departNumberEmployee($depart) * $nbDays) * 100);
		}

		/** La fonction qui renvoie le taux des justifications dans un déppartement dans une année donnée */

		public function rateJustYearDepart($depart, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumRates = 0;
			for ($i = 1; $i <= 12 ; $i++) { 
				$sumRates += $this->rateJustMonthDepart($depart, $yearOnly.'-'.$i);
			}
			return ($sumRates / 12);
		}

		/** La fonction qui renvoie le nombre des justifications dans un déppartement durant une plage donnée */

		public function nbJustBandDepart($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Departement = :depart) AND (salary.Matricule = justification.Matricule)");
			$query->execute(array(':day1' => $begin, ':day2' => $end, ':depart' => $depart));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le taux des justifications dans un déppartement durant une plage donnée */

		public function rateJustBandDepart($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->nbJustBandDepart($depart, $begin, $end) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->departNumberEmployee($depart)) * 100);
		}
		/*************************************************************************/
		/** Les méthodes concernants le nombre d'heures d'absence et du travail **/
		/*************************************************************************/

		/** La fonction qui renvoie le nombre des heures d'absence d'un département pendant un jour */

		public function departHeuresAbsJour($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {return 0;}
			else return (8 * $this->departNumberEmployee($depart) - $this->getHourDepart($depart, $day));
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un département pendant un jour */

		public function departHeuresTravJour($depart, $day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) return 0;
			else return ($this->getHourDepart($depart, $day));
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un département pendant un mois */

		public function departHeuresAbsMois($depart, $month) // $month a le format YYYY-MM
		{
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$sumHours = $this->departHeuresTravMois($depart, $month);
			return (8 * ($taille - $this->nbWeekendMois($month)) * $this->departNumberEmployee($depart) - $sumHours);
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un département pendant un mois */

		public function departHeuresTravMois($depart, $month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':depart' => $depart, ':day1' => ($format.'-01'), ':day2' => ($format.'-'.$taille), ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return $sumHour;
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un département pendant une année */

		public function departHeuresAbsAnnee($depart, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumHours = $this->departHeuresTravAnnee($depart, $year); // Les heures de travail.
			$nbDays = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$nbDays += (cal_days_in_month(CAL_GREGORIAN, $i, $yearOnly) - $this->nbWeekendMois($yearOnly.'-'.$i));
			}
			return (8 * $nbDays * $this->departNumberEmployee($depart) - $sumHours);
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un département pendant une année */

		public function departHeuresTravAnnee($depart, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':depart' => $depart, ':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return $sumHour;
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un département pendant une période */

		public function departHeuresAbsPeriode($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->bandDepart($depart, $begin, $end));
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un département pendant une période */

		public function departHeuresTravPeriode($depart, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Departement = :depart AND salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :beg AND :en) AND salary.Rang > :rang)");
			$request->execute(array(':depart' => $depart, ':beg' => $begin, ':en' => $end, ':rang' => $this->degree));
			$data = $request->fetch();
			$sum = $data['Sum'];
			if ($sum == NULL) {
				$sum = 0;
			}
			return $sum;
		}
	}
?>