<?php

	require("classes/SousDirecteurs.php");

	/**********************************
	* La classe concernant le directeur
	***********************************/

	class Directeur extends SousDirecteurs // Validé.
	{

		protected $tabDepart; // Le tableau qui contient tous les déppartement.
		protected $numberEmployee; // Le nombre totale des employés.

		/** La fonction à appeler avant tous traitements */

		public function initiate(){

			// L'appel de la fonction du parent.
			parent::initiate();			

			// Récupération du tableau des déppartements.
			$gettingDeppartements = $this->datab->prepare("SELECT * FROM department ");
			$gettingDeppartements->execute();

			// Maintenant le résultat $gettingServices contient le tableau qu'on veut récupérer.
			$this->tabDepart = $gettingDeppartements->fetchAll(PDO::FETCH_OBJ);
			$this->numberEmployee = $this->totalNumberEmployee();
		}

		/****************************************************************/
		/** Cette classe utilise les méthodes de la classe ChefService **/
		/****************************************************************/
	
		/*************************************************/
		/** Les méthodes concernants l'école en général **/
		/*************************************************/

		/** La fonction qui renvoie un tableau de boolean avec comme indices les heures de travail dans une journée pour l'école en général */

		public function majorArrayByDay($day) // $day a le format YYYY-MM-DD
		{
			$checkDay = [];
			if ($this->isWeekend($day)) {
				for ($i = 0; $i < 12; $i++) { 
					$checkDay[$i] = false;
				}
			}
			else {
				$results = $this->datab->prepare("SELECT MIN(HeureDebut) AS Min FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
				$results->execute(array(':day' => $day, ':rang' => $this->degree));
				$result = $results->fetch();
				$val = $result['Min'];
				if ($val == NULL) {
					$hour1 = 0;
				}
				else{
					$hour1 = intval(substr($val, 0, 2));
				}
				$results = $this->datab->prepare("SELECT MAX(HeureFin) AS Max FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
				$results->execute(array(':day' => $day, ':rang' => $this->degree));
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

		/** La fonction qui renvoie le nombre des heures du travail de tous les employés pendant une journée donnée */

		public function getHourMajor($day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$results = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND schedule.Date = :day AND salary.Rang > :rang)");
			$results->execute(array(':day' => $day, ':rang' => $this->degree));
			$result = $results->fetch();
			$sumHours = $result['Sum'];
			if ($sumHours == NULL) {
				$sumHours = 0;
			}
			return $sumHours;
		}

		/** La fonction qui renvoie un tableau des heures de travail avec comme indices les jours du mois pour l'école en général */
		public function majorArrayByMonth($month) // $month a le format YYYY-MM
		{
			$checkMonth = [];
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) { 
				$checkMonth[$i] = $this->getHourMajor($format.'-'.($i + 1));
				$i++;
			}
			return $checkMonth;
		}
		/*les taux de presence*/
		public function majorArrayByMonthTauxP($month) // $month a le format YYYY-MM
		{
			$checkMonth = [];
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) { 
				$checkMonth[1][$i] = $format.'-'.($i + 1);
				$checkMonth[0][$i] = 100*$this->getHourMajor($format.'-'.($i + 1))/(8*$this->numberEmployee);
				$i++;
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie un tableau des heures d'absences avec comme indices les jours du mois pour l'école en général */

		public function majorArrayByMonthAbs($month) // $month a le format YYYY-MM
		{
			$checkMonth = [];
			$tab = $this->majorArrayByMonth($month);
			$format = substr($month, 0, 7);
			$day = $format.'-01';
			for ($i = 0; $i < count($tab); $i++) {
				if ($this->isWeekend($day)) {
					$checkMonth[0][$i] = 0;
				}
				else{
					$checkMonth[0][$i] = 8 * $this->numberEmployee - $tab[$i];
				}
				$checkMonth[1][$i] = $day;
				$day = $this->nextDay($day);
			}
			return $checkMonth;
		}

		/** Récupérer le nombre total des employés */
		public function totalNumberEmployee()
		{
			$results = $this->datab->query("SELECT * FROM salary");
			$result = $results->fetchAll();
			$total=count($result);
			return $total;
		}

		/** La fonction qui renvoie le taux de travaille pour l'école en général pendant un mois */

		public function getRateMajor($month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':day1' => ($format.'-01'), ':day2' => ($format.'-'.$taille), ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return ($sumHour / (8 * $this->numberEmployee * ($taille - $this->nbWeekendMois($month))) * 100);
		}

		/** La fonction qui renvoie un tableau des taux de travail avec comme indices les mois d'une année pour l'école en général */

		public function majorArrayByYear($year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRateMajor($yearOnly.'-'.($i + 1));
			}
			return $checkYear;
		}

		/** La fonction qui renvoie un tableau des taux d'absences avec comme indices les mois d'une année pour l'école en général */

		public function majorArrayByYearAbs($year) // $year a le format YYYY
		{
			$checkYear = [];
			$tab = $this->majorArrayByYear($year);
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = 100 - $tab[$i];
			}
			return $checkYear;
		}
		public function majorArrayByYearHAbs($year) // $year a le format YYYY
		{
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->majorHeuresAbsMois($year.'-'.($i+1));
			}
			return $checkYear;
		}

		/** La fonction qui renvoie le taux d'absences de l'école pendant un jour */

		public function dayRateMajor($day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$presenceRate = ($this->getHourMajor($day) / (8 * $this->numberEmployee) * 100);
			return (100 - $presenceRate);
		}


		/** La fonction qui renvoie le taux d'absences de l'école pendant un mois */

		public function monthRateMajor($month) // $month a le format YYYY-MM
		{
			return (100 - $this->getRateMajor($month));
		}

		/** La fonction qui renvoie le taux d'absences de l'école pendant une année */

		public function yearRateMajor($year) // $year a le format YYYY
		{
			$sumMonthRate = 0;
			$table = $this->majorArrayByYear($year);
			foreach ($table as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}

		/****************************** Pour la présence *******************************/

		/** La fonction qui renvoie le taux de présences de l'école pendant un jour */

		public function dayRateMajorPre($day) // $day a le format YYYY-MM-DD
		{
			$presenceRate = ($this->getHourMajor($day) / (8 * $this->numberEmployee) * 100);
			return ($presenceRate);
		}

		/** La fonction qui renvoie le taux de présences de l'école pendant un mois */

		public function monthRateMajorPre($month) // $month a le format YYYY-MM
		{
			return ($this->getRateMajor($month));
		}

		/** La fonction qui renvoie le taux de présences de l'école pendant une année */

		public function yearRateMajorPre($year) // $year a le format YYYY
		{
			$sumMonthRate = 0;
			$table = $this->majorArrayByYear($year);
			foreach ($table as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return ($sumMonthRate / 12);
		}

		/************************ Traitement pour une plage donnée *****************************/

		/** La fonction qui renvoie le nombre des heures d'absences pour l'école en général dans la plage donnée */

		public function bandMajor($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :beg AND :en) AND salary.Rang > :rang)");
			$request->execute(array(':beg' => $begin, ':en' => $end, ':rang' => $this->degree));
			$data = $request->fetch();
			$sum = $data['Sum'];
			if ($sum == NULL) {
				$sum = 0;
			}
			return (8 * ($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->numberEmployee - $sum);
		}

		/** La fonction qui renvoie le taux d'absence pour l'école en général dans la plage donnée */

		public function bandRateMajor($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (($this->bandMajor($begin, $end)) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->numberEmployee * 8) * 100);
		}

		/** La fonction qui renvoie le taux de présence pour l'école en général dans la plage donnée */

		public function bandRatePresMajor($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (100 - $this->bandRateMajor($begin, $end));
		}
		/** La fonction qui renvoie un tableau de taux de présences avec comme indices les jours dans la plage donnée pour l'école */

		public function majorArrayBand($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$taille = $this->nbDays($begin, $end);
			$checkBand = [];
			$day = $begin;
			for ($i = 0; $i < $taille; $i++) { 
				$checkBand[0][$i] = $this->getHourMajor($day) / (8 * $this->numberEmployee) * 100;
				$checkBand[1][$i] = $day;
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/** La fonction qui renvoie un tableau de taux d'absences avec comme indices les jours dans la plage donnée pour l'école */

		public function majorArrayBandAbs($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$tab = $this->majorArrayBand($begin, $end);
			$checkBand = [];
			$day = $begin;
			for ($i = 0; $i < count($tab[0]); $i++) {
				if ($this->isWeekend($day)) {
					$checkBand[$i] = 0;
				}
				else{
					$checkBand[$i] = 100 - $tab[0][$i];
				}
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		public function majorArrayBandNbHeuresAbs($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$tab = $this->majorArrayBand($begin, $end);
			$checkBand = [];
			$day = $begin;
			for ($i = 0; $i < count($tab[0]); $i++) {
				if ($this->isWeekend($day)) {
					$checkBand[0][$i] = 0;
				}
				else{
					$checkBand[0][$i] = $this->majorHeuresAbsJour($day);
				}
				$checkBand[1][$i] = $day;
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/************************************************************************************************************/
		/** Les méthodes concernants les détails d'un déppartement, un service ou un employé qlq pour le Directeur **/
		/************************************************************************************************************/

		// Pour ceci il suffit d'appeler les méthodes héritées de la classe AssDirector en passant les paramètres qu'il faut.

		/**************************************************************************************************/
		/** Les méthodes concernants les traitemets faits avec prise en considération les justifications **/
		/**************************************************************************************************/

		/** La fonction qui renvoie le nombre des justifications dans l'école pendant une journée donnée */

		public function nbJustDayMajor($day) // $day a le format YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE justification.Date = :day AND salary.Matricule = justification.Matricule AND salary.Rang > :rang");
			$query->execute(array(':day' => $day, ':rang' => $this->degree));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le nombre des justifications dans l'école pendant un mois donné */

		public function nbJustMonthMajor($month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$firstDay = $format.'-01';
			$lastDay = $format.'-'.cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Matricule = justification.Matricule) AND (salary.Rang > :rang)");
			$query->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le nombre des justifications dans l'école pendant une année donnée */

		public function nbJustYearMajor($year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Matricule = justification.Matricule) AND (salary.Rang > :rang)");
			$query->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le taux des justifications dans l'école dans une journée donnée */

		public function rateJustDayMajor($day) // $day a le format YYYY-MM-DD
		{
			return ($this->nbJustDayMajor($day) / ($this->numberEmployee) * 100);
		}

		/** La fonction qui renvoie le taux des justifications dans l'école dans un mois donné */

		public function rateJustMonthMajor($month) // $month a le format YYYY-MM
		{
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$nbDays = cal_days_in_month(CAL_GREGORIAN, $monthOnly, $yearOnly) - $this->nbWeekendMois($month); // On récupère les jours de travail.
			return ($this->nbJustMonthMajor($month) / ($this->numberEmployee * $nbDays) * 100);
		}

		/** La fonction qui renvoie le taux des justifications dans l'école dans une année donnée */

		public function rateJustYearMajor($year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumRates = 0;
			for ($i = 1; $i <= 12 ; $i++) { 
				$sumRates += $this->rateJustMonthMajor($yearOnly.'-'.$i);
			}
			return ($sumRates / 12);
		}

		/** La fonction qui renvoie le nombre des justifications dans l'école durant une plage donnée */

		public function nbJustBandMajor($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT salary.Matricule, justification.Date FROM salary, justification WHERE (justification.Date BETWEEN :day1 AND :day2) AND (salary.Matricule = justification.Matricule) AND (salary.Rang > :rang)");
			$query->execute(array(':day1' => $begin, ':day2' => $end, ':rang' => $this->degree));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return count($tab);
		}

		/** La fonction qui renvoie le taux des justifications dans l'école durant une plage donnée */

		public function rateJustBandMajor($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->nbJustBandMajor($begin, $end) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * $this->numberEmployee) * 100);
		}
		/*************************************************************************/
		/** Les méthodes concernants le nombre d'heures d'absence et du travail **/
		/*************************************************************************/

		/** La fonction qui renvoie le nombre des heures d'absence de l'école pendant un jour */

		public function majorHeuresAbsJour($day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			return (8 * $this->numberEmployee - $this->getHourMajor($day));
		}

		/** La fonction qui renvoie le nombre des heures du travail de l'école pendant un jour */

		public function majorHeuresTravJour($day) // $day a le format YYYY-MM-DD
		{
			return ($this->getHourMajor($day));
		}

		/** La fonction qui renvoie le nombre des heures d'absence de l'école pendant un mois */

		public function majorHeuresAbsMois($month) // $month a le format YYYY-MM
		{
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$sumHours = $this->majorHeuresTravMois($month);
			return (8 * ($taille - $this->nbWeekendMois($month)) * $this->numberEmployee - $sumHours);
		}

		/** La fonction qui renvoie le nombre des heures du travail de l'école pendant un mois */

		public function majorHeuresTravMois($month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$taille = cal_days_in_month(CAL_GREGORIAN, substr($month, 5, 2), substr($month, 0, 4));
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':day1' => ($format.'-01'), ':day2' => ($format.'-'.$taille), ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return $sumHour;
		}

		/** La fonction qui renvoie le nombre des heures d'absence de l'école pendant une année */

		public function majorHeuresAbsAnnee($year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumHours = $this->majorHeuresTravAnnee($year); // Les heures de travail.
			$nbDays = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$nbDays += (cal_days_in_month(CAL_GREGORIAN, $i, $yearOnly) - $this->nbWeekendMois($yearOnly.'-'.$i));
			}
			return (8 * $nbDays * $this->numberEmployee - $sumHours);
		}

		/** La fonction qui renvoie le nombre des heures du travail de l'école pendant une année */

		public function majorHeuresTravAnnee($year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :day1 AND :day2) AND salary.Rang > :rang)");
			$request->execute(array(':day1' => $firstDay, ':day2' => $lastDay, ':rang' => $this->degree));
			$data = $request->fetch();
			$sumHour = $data['Sum']; // La somme des heures.
			if ($sumHour == NULL) {
				$sumHour = 0;
			}
			return $sumHour;
		}

		/** La fonction qui renvoie le nombre des heures d'absence de l'école pendant une période */

		public function majorHeuresAbsPeriode($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->bandMajor($begin, $end));
		}

		/** La fonction qui renvoie le nombre des heures du travail de l'école pendant une période */

		public function majorHeuresTravPeriode($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT SUM((HeureFin - HeureDebut) / 10000) AS Sum FROM schedule, salary WHERE (salary.Matricule = schedule.Matricule AND (schedule.Date BETWEEN :beg AND :en) AND salary.Rang > :rang)");
			$request->execute(array(':beg' => $begin, ':en' => $end, ':rang' => $this->degree));
			$data = $request->fetch();
			$sum = $data['Sum'];
			if ($sum == NULL) {
				$sum = 0;
			}
			return $sum;
		}
		/** La fonction qui renvoie le nombre d'employés absents pendant la journée donnée pour l'école */
		/*--------------------------------------------------------------------*/
		public function majorAbsent($day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			return ($this->numberEmployee - $this->majorPresent($day));
		}

		/** La fonction qui renvoie le nombre d'employés présents pendant la journée donnée pour l'école */

		public function majorPresent($day) // $day a le format YYYY-MM-DD
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$gettingTab = $this->datab->prepare("SELECT schedule.Matricule, salary.Service FROM schedule, salary WHERE (schedule.Date = :day AND schedule.Matricule = salary.Matricule AND salary.Rang > :rang)");
			$gettingTab->execute(array(':day' => $day, ':rang' => $this->degree));
			$tabEmployee = $gettingTab->fetchAll(PDO::FETCH_OBJ);
			return (count($tabEmployee));
		}
		/*******************************************************************************************/
		/** Les méthodes concernants les détails d'un service ou un employé qlq pour le Directeur **/
		/*******************************************************************************************/

		// Pour ceci il suffit d'appeler les méthodes héritées de la classe ChefService en passant les paramètres qu'il faut.
	}
?>