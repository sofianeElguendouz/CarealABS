<?php

	/********************
	* La classe Employée
	*********************/

	class Employe // Validé.
	{
		protected $datab; // Le nom de la base de données qu'on travaille avec.
		protected $tablogin = 'salary'; // La table qui contient les détails des employés ansi que leurs Login & Password.
		protected $tabpointage = 'schedule'; // La table qui contient les données de la pointeuse.
		protected $tabdepart = 'department'; // La table qui contient les infos des deppartements.

		/** Les champs caractérisants un employé */
		protected $id;
		protected $matricule;
		protected $login;
		protected $password;

		/*$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$gettinginputs = $datab->prepare("SELECT * FROM :login WHERE Login = :ident");
		$gettinginputs->execute(array(':login' => $tablogin, ':ident' => $_SESSION['Login']));
		$gettinginput = $gettinginputs->fetch();*/

		/********************/
		/** Le constructeur */
		/********************/

		public function __construct($_Id, $_Matricule, $_Login, $_Password)
		{
			$this->id = $_Id;
			$this->matricule = $_Matricule;
			$this->login = $_Login;
			$this->password = $_Password;
			$this->datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
			
			// Initialisation des noms des tableaux:
			$this->tablogin = 'salary';
			$this->tabpointage = 'schedule';
			$this->tabdepart = 'department';
		}

		/** Les Getters */

		public function getMatricule(){
			return $this->matricule;
		}

		public function getID(){
			return $this->id;
		}

		public function getLogin(){
			return $this->login;
		}

		/** La fonction qui renvoie un tableau de boolean avec comme indices les heures de travail dans une journée */

		public function simpleArrayByDay($matricule, $day) // Format de $day YYYY-MM-DD
		{
			$checkDay = [];
			if ($this->isWeekend($day)) {
				for ($i = 0; $i < 12; $i++) { 
					$checkDay[$i] = false;
				}
			}
			else {
				$results = $this->datab->prepare("SELECT * FROM schedule WHERE Matricule = :matricule and Date = :date");
				$results->execute(array(':matricule' => $matricule, ':date' => $day));
				$result = $results->fetch();
				$hour1 = intval(substr($result['HeureDebut'], 0, 2));
				$hour2 = intval(substr($result['HeureFin'], 0, 2));
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

		/** La fonction qui renvoie le nombre des heures travaillées dans l'emploi de temps d'un employé durant une journée  */

		public function getHour($matricule, $day)
		{
			if ($this->isWeekend($day)) {
				return 0;
			}
			$results = $this->datab->prepare("SELECT * FROM schedule WHERE Matricule = :matricule and Date = :date");
			$results->execute(array(':matricule' => $matricule, ':date' => $day));
			$result = $results->fetch();
			$hour1 = intval(substr($result['HeureDebut'], 0, 2));
			$hour2 = intval(substr($result['HeureFin'], 0, 2));
			if ($hour2 > 0) {
				return ($hour2 - $hour1);
			}
			return 0; // A traiter si l'employé a oublié de pointer.
		}

		/** La fonction qui renvoie un tableau de nombre d'heures travaillées avec comme indices les jours du mois donné */

		public function simpleArrayByMonth($matricule, $month) // Format de $month YYYY-MM
		{
			$checkMonth = [];
			$monthOnly = substr($month, 5, 2); // On récupère juste le mois.
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$format = substr($month, 0, 7); // On récupère le format YYYY-MM.
			$i = 0;
			while (checkdate($monthOnly, ($i + 1), $yearOnly)) { 
				$checkMonth[$i] = $this->getHour($matricule, $format.'-'.($i + 1));
				$i++;
			}
			return $checkMonth;
		}
		public function simpleArrayByMonthAbs($matricule, $month) // Format de $month YYYY-MM
		{
			$checkMonth = [];
			$tab = $this->simpleArrayByMonth($matricule, $month);
			for ($i = 0; $i < count($tab); $i++) { 
				$checkMonth[$i] = 8 - $tab[$i]; // Il doit travailler 8 heures chaque jour.
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie le taux de travaille dans l'emploi de temps d'un employé pendant un mois */
		
		public function getRate($matricule, $month)
		{
			$check = $this->simpleArrayByMonth($matricule, $month);
			$sumHour = 0; // La somme des heures.
			$taille = 0;
			foreach ($check as $day) {
				$sumHour += $day;
				$taille++;
			}
			return (($sumHour / (8 * ($taille - $this->nbWeekendMois($month)))) * 100); // On considère que chaque employée travaille 8 heures par jour.
		}
		/** La fonction qui renvoie un tableau de taux de travail avec comme indices les mois de l'année donnée */

		public function simpleArrayByYear($matricule, $year) // Format YYYY
		{
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRate($matricule, $yearOnly.'-'.($i + 1));
			}
			return $checkYear;
		}
		/** La fonction qui renvoie un tableau de taux d'absences avec comme indices les mois de l'année donnée */

		public function simpleArrayByYearAbs($matricule, $year) // Format YYYY
		{
			$checkYear = [];
			$tab = $this->simpleArrayByYear($matricule, $year);
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = 100 - $tab[$i];
			}
			return $checkYear;
		}

		/** La fonction qui renvoie le taux d'absences d'un employé pendant un jour */

		public function dayRate($matricule, $date) // Renvoie le taux d'abscence de l'employée. Format de date YYYY-MM-DD
		{
			$nbAbscence = 8 - $this->getHour($matricule, $date);
			return (($nbAbscence / 8) * 100);
		}

		/** La fonction qui renvoie le taux d'absences d'un employé pendant un mois */

		public function monthRate($matricule, $month) // Format de month: YYYY-MM
		{
			return (100 - $this->getRate($matricule, $month));
		}

		/** La fonction qui renvoie le taux d'absences d'un employé pendant une année */

		public function yearRate($matricule, $year) // Format de year: YYYY
		{
			$sumMonthRate = 0;
			$tab = $this->simpleArrayByYear($matricule, $year);
			foreach ($tab as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}

		/****************************** Pour la présence *******************************/

		/** La fonction qui renvoie le taux de présences d'un employé pendant un jour */

		public function dayRatePresence($matricule, $date){ // Renvoie le taux de présences de l'employée. Format de date YYYY-MM-DD
			return (100 - $this->dayRate($matricule, $date));
		}

		/** La fonction qui renvoie le taux de présences d'un employé pendant un mois */

		public function monthRatePresence($matricule, $month){ // Format de month: YYYY-MM
			return ($this->getRate($this->simpleArrayByMonth($matricule, $month)));
		}

		/** La fonction qui renvoie le taux de présences d'un employé pendant une année */

		public function yearRatePresence($matricule, $year){ // Format de year: YYYY
			return (100 - $this->yearRate($matricule, $year));
		}

		/************************ Traitement pour une plage donnée *****************************/

		/** La fonction qui renvoie le nombre des heures d'absences d'un employé dans la plage donnée */

		public function band($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT * FROM schedule WHERE Matricule = :matricule AND Date BETWEEN :beg AND :en");
			$request->execute(array(':matricule' => $matricule, ':beg' => $begin, ':en' => $end));
			$sum = 0;
			while ($data = $request->fetch()) {
				$sum += $this->getHour($matricule, $data['Date']); // Nombre des heures de présences dans la plage.
			}
			return (8 * ($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) - $sum);
		}

		/** La fonction qui renvoie le nombre de jour entre deux dates */

		public function nbDays($begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$debut = strtotime($begin) / (60 * 60 * 24);
			$fin = strtotime($end) / (60 * 60 * 24);
			if ($fin < $debut) {
				return 0;
			}
			return ($fin - $debut + 1);
		}

		/** La fonction qui renvoie le taux d'absence d'un employé dans la plage donnée */
		public function bandRate($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (($this->band($matricule, $begin, $end)) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * 8) * 100);
		}

		/** La fonction qui renvoie le taux de présence d'un employé dans la plage donnée */

		public function bandRatePres($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (100 - $this->bandRate($matricule, $begin, $end));
		}

		/** La fonction qui renvoie le jour suivant d'une date donnée */
		public function nextDay($day)
		{
			$date = strtotime($day);
			$date = $date / (60 * 60 * 24);
			$date++;
			return (substr(date('c', $date * (60 * 60 * 24)), 0, 10));
		}

		/** La fonction qui renvoie un tableau de taux de présences avec comme indices les jours dans la plage donnée pour un employé */

		public function simpleArrayBand($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$taille = $this->nbDays($begin, $end);
			$checkBand = [];
			$day = $begin;
			for ($i = 0; $i < $taille; $i++) { 
				$checkBand[0][$i] = $this->getHour($matricule, $day) / 8 * 100;
				$checkBand[1][$i] = $day;
				$day = $this->nextDay($day);
			}
			return $checkBand;
		}

		/** La fonction qui renvoie un tableau de taux d'absences avec comme indices les jours dans la plage donnée pour un employé */

		public function simpleArrayBandAbs($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$tab = $this->simpleArrayBand($matricule, $begin, $end);
			$checkBand = [];
			for ($i = 0; $i < count($tab); $i++) { 
				$checkBand[$i] = 100 - $tab[$i];
			}
			return $checkBand;
		}

		/***************************************************************************************************************/
		/************************************** Les traitements pour les Justifications ********************************/
		/***************************************************************************************************************/

		/** La fonction qui indique (avec un boolean) si un jour donné est un Week-End ou non */

		public function isWeekend($date){
			if (date('N', strtotime($date)) == 5 || date('N', strtotime($date)) == 6) {
				return true;
			}
			else{
				return false;
			}
		}
		/** La fonction qui renvoie le nombre de Weekends dans un mois donné */

		public function nbWeekendMois($month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7); // On récupère le format YYYY-MM.
			$monthOnly = substr($month, 5, 2); // On récupère juste le mois.
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$counter = 0;
			$i = 1;
			while (checkdate($monthOnly, $i, $yearOnly)) {
				if ($this->isWeekend($format.'-'.$i)) {
					$counter++;
				}
				$i++;
			}
			return $counter;
		}

		/** La fonction qui renvoie le nombre de Weekends dans la plage donnée */

		public function nbWeekendPlage($begin, $end) // Les dates ont le format YYYY-MM-DD
		{
			$dayMove = strtotime($begin) / (60 * 60 * 24);
			$dayEnd = strtotime($end) / (60 * 60 * 24);
			$date = $begin;
			$counter = 0;
			while ($dayMove <= $dayEnd) {
				if ($this->isWeekend($date)) {
					$counter++;
				}
				$date = $this->nextDay($date);
				$dayMove = strtotime($date) / (60 * 60 * 24);
			}
			return $counter;
		}

		/** La fonction qui permet d'indiquer si un employé a justifié son absence pendant un jour donné */

		public function dayJustified($matricule, $day)
		{
			$verify = false;
			$query = $this->datab->prepare("SELECT * FROM justification WHERE Matricule = :matricule AND Date = :day");
			$query->execute(array(':matricule' => $matricule, ':day' => $day));
			if ($query->rowCount() > 0) {
				$verify = true;
			}
			return $verify;
		}

		/** La fonction qui renvoie le nombre de justifications d'un employé dans un mois donné */

		public function nbJustMonth($matricule, $month) // $month a le format YYYY-MM
		{
			$format = substr($month, 0, 7);
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$i = 31;
			while (!checkdate($monthOnly, $i, $yearOnly)) {
				$i--;
			}
			$lastDay = $format.'-'.$i;
			$firstDay = $format.'-01';
			$query = $this->datab->prepare("SELECT * FROM justification WHERE Matricule = :matricule AND Date BETWEEN :day1 AND :day2");
			$query->execute(array(':matricule' => $matricule, ':day1' => $firstDay, ':day2' => $lastDay));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return (count($tab));
		}

		/** La fonction qui renvoie le nombre de justifications d'un employé dans une année donnée */

		public function nbJustYear($matricule, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$firstDay = $yearOnly.'-01-01';
			$lastDay = $yearOnly.'-12-31';
			$query = $this->datab->prepare("SELECT * FROM justification WHERE Matricule = :matricule AND Date BETWEEN :day1 AND :day2");
			$query->execute(array(':matricule' => $matricule, ':day1' => $firstDay, ':day2' => $lastDay));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return (count($tab));
		}

		/** La fonction qui renvoie le taux des justifications d'un employé dans un mois donné */

		public function rateJustMonth($matricule, $month) // $month a le format YYYY-MM
		{
			$monthOnly = substr($month, 5, 2);
			$yearOnly = substr($month, 0, 4); // On récupère juste l'année.
			$nbDays = cal_days_in_month(CAL_GREGORIAN, $monthOnly, $yearOnly) - $this->nbWeekendMois($month);
			return ($this->nbJustMonth($matricule, $month) / $nbDays * 100);
		}

		/** La fonction qui renvoie le taux des justifications d'un employé dans une année donnée */

		public function rateJustYear($matricule, $year) // $year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumRates = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$sumRates += $this->rateJustMonth($matricule, $yearOnly.'-'.$i);
			}
			return ($sumRates / 12);
		}

		/** La fonction qui renvoie le nombre des justifications d'un employé dans une plage donnée */

		public function nbJustBand($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$query = $this->datab->prepare("SELECT * FROM justification WHERE Matricule = :matricule AND Date BETWEEN :day1 AND :day2");
			$query->execute(array(':matricule' => $matricule, ':day1' => $begin, ':day2' => $end));
			$tab = $query->fetchAll(PDO::FETCH_OBJ);
			return (count($tab));
		}

		/** La fonction qui renvoie le taux des justifications d'un employé dans une plage donnée */

		public function rateJustBand($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->nbJustBand($matricule, $begin, $end) / ($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * 100);
		}

		/** La fonction qui renvoie le taux d'absence d'un employé dans un mois donné avec prise en considération les justifications */

		public function monthRateJust($matricule, $month)
		{
			$check = $this->simpleArrayByMonth($matricule, $month);
			$sumHour = 0; // La somme des heures.
			$taille = 0;
			foreach ($check as $day) {
				$sumHour += $day;
				$taille++;
			}
			$presenceRate = ((($sumHour + $this->nbJustMonth($matricule, $month) * 8) / (8 * ($taille - $this->nbWeekendMois($month)))) * 100);
			return (100 - $presenceRate);
		}

		/** La fonction qui renvoie le taux d'absence d'un employé dans une année donnée avec prise en considération les justifications */

		public function yearRateJust($matricule, $year)
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumRates = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$sumRates += $this->monthRateJust($matricule, $yearOnly.'-'.$i);
			}
			return ($sumRates / 12);
		}

		/** La fonction qui renvoie le taux de présence d'un employé dans un mois donné avec prise en considération les justifications */

		public function monthRatePresJust($matricule, $month)
		{
			return (100 - $this->monthRateJust($matricule, $month));
		}

		/** La fonction qui renvoie le taux de présence d'un employé dans une année donnée avec prise en considération les justifications */

		public function yearRatePresJust($matricule, $year)
		{
			return (100 - $this->yearRateJust($matricule, $year));
		}

		/** La fonction qui renvoie le nombre des heures d'absences d'un employé dans la plage donnée avec prise en considération les justifications */

		public function bandJust($matricule, $begin, $end)
		{
			return ($this->band($matricule, $begin, $end) - (8 * $this->nbJustBand($matricule, $begin, $end)));
		}

		/** La fonction qui renvoie le taux d'absence d'un employé dans la plage donnée avec prise en considération les justifications */

		public function bandRateJust($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return (($this->bandJust($matricule, $begin, $end)) / (($this->nbDays($begin, $end) - $this->nbWeekendPlage($begin, $end)) * 8) * 100);
		}

		/** La fonction qui renvoie le taux de présence d'un employé dans la plage donnée avec prise en considération les justifications */

		public function bandRatePresJust($matricule, $begin, $end)
		{
			return (100 - $this->bandRateJust($matricule, $begin, $end));
		}
		/*************************************************************************/
		/** Les méthodes concernants le nombre d'heures d'absence et du travail **/
		/*************************************************************************/

		/** La fonction qui renvoie le nombre des heures d'absence d'un employé pendant un jour */

		public function simpleHeuresAbsJour($matricule, $day) // $day a le format YYYY-MM-DD
		{
			return (8 - $this->getHour($matricule, $day));
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un employé pendant un jour */

		public function simpleHeuresTravJour($matricule, $day) // $day a le format YYYY-MM-DD
		{
			return ($this->getHour($matricule, $day));
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un employé pendant un mois */

		public function simpleHeuresAbsMois($matricule, $month) // $month est de format YYYY-MM
		{
			$tab = $this->simpleArrayByMonthAbs($matricule, $month);
			$format = substr($month, 0, 7);
			$day = $format.'-01';
			$sumHours = 0;
			foreach ($tab as $dayHours) {
				if (!$this->isWeekend($day)) {
					$sumHours += $dayHours;
				}
				$day = $this->nextDay($day);
			}
			return $sumHours;
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un employé pendant un mois */

		public function simpleHeuresTravMois($matricule, $month) // $month est de format YYYY-MM
		{
			$tab = $this->simpleArrayByMonth($matricule, $month);
			$sumHours = 0;
			foreach ($tab as $dayHours) {
				$sumHours += $dayHours;
			}
			return $sumHours;
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un employé pendant une année */

		public function simpleHeuresAbsAnnee($matricule, $year) //$year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumHours = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$sumHours += $this->simpleHeuresAbsMois($matricule, $yearOnly.'-'.$i);
			}
			return $sumHours;
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un employé pendant une année */

		public function simpleHeuresTravAnnee($matricule, $year) //$year a le format YYYY
		{
			$yearOnly = substr($year, 0, 4); // On récupère juste l'année.
			$sumHours = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$sumHours += $this->simpleHeuresTravMois($matricule, $yearOnly.'-'.$i);
			}
			return $sumHours;
		}

		/** La fonction qui renvoie le nombre des heures d'absence d'un employé pendant une plage */

		public function simpleHeuresAbsPeriode($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			return ($this->band($matricule, $begin, $end));
		}

		/** La fonction qui renvoie le nombre des heures du travail d'un employé pendant une plage */

		public function simpleHeuresTravPeriode($matricule, $begin, $end) // $begin et $end ont le fomat YYYY-MM-DD
		{
			$request = $this->datab->prepare("SELECT * FROM schedule WHERE Matricule = :matricule AND Date BETWEEN :beg AND :en");
			$request->execute(array(':matricule' => $matricule, ':beg' => $begin, ':en' => $end));
			$sum = 0;
			while ($data = $request->fetch()) {
				$sum += $this->getHour($matricule, $data['Date']); // Nombre des heures de présences dans la plage.
			}
			return $sum;
		}
	}	
?>