<?php

	/********************
	* La classe Employée
	*********************/

	class Employe
	{
		protected $datab; // Le nom de la base de données qu'on travaille avec.
		protected $tablogin = 'salary'; // La table qui contient les détails des employés ansi que leurs Login & Password.
		protected $tabpointage = 'schedule'; // La table qui contient les données de la pointeuse.
		protected $tablist = 'list'; // La table qui contient le N° de deppartement de chaque employé.
		protected $tabdepart = 'department'; // La table qui contient les infos des deppartements.

		/** Les champs caractérisants un employé */
		protected $id = 0;
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
		}

		/** Les Getters */

		public function getMatricule(){
			return $this->matricule;
		}

		/** La fonction qui renvoie un tableau de boolean avec comme indices les heures de travail dans une journée */

		public function simpleArrayByDay($matricule, $day) // Format de $day YYYY-MM-DD
		{
			$results = $this->datab->prepare("SELECT * FROM schedule WHERE Matricule = :matricule and Date = :date");
			$results->execute(array(':matricule' => $matricule, ':date' => $day));
			$result=$results->fetch();
			$hour1 = intval(substr($result['HeureDebut'], 0,2));
			$hour2 = intval(substr($result['HeureFin'], 0,2));
			$checkDay = [];
			for ($i = 0; $i < 12; $i++) { 
				if (($i + 6 >= $hour1) and ($i + 6 < $hour2)) {
					$checkDay[$i] = true;
				}
				else
				{
					$checkDay[$i] = false;
				}
			}
			return $checkDay;
		}

		/** La fonction qui renvoie le nombre des heures travaillées dans l'emploi de temps d'un employé durant une journée  */

		public function getHour($check){
			$cpt = 0;
			foreach ($check as $hour) {
				if ($hour == true) {
					$cpt++;
				}
			}
			return $cpt;
		}

		/** La fonction qui renvoie un tableau de nombre d'heures travaillées avec comme indices les jours du mois donné */

		public function simpleArrayByMonth($matricule, $month){ // Format de $month YYYY-MM
			$monthOnly = substr($month, 5, 2); // On récupère juste le mois.
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
			$format = substr($month, 0, 7); // On récupère le format YYYY-MM.
			for ($i = 0; $i < $taille; $i++) { 
				$checkMonth[$i] = $this->getHour($this->simpleArrayByDay($matricule, $format.'-'.($i + 1)));
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie le taux de travaille dans l'emploi de temps d'un employé pendant un mois */
		
		public function getRate($check){
			$sumHour = 0; // La somme des heures.
			$taille = 0;
			foreach ($check as $day) {
				$sumHour += $day;
				$taille++;
			}
			return (($sumHour / (8 * $taille)) * 100); // On considère que chaque employée travaille 8 heures par jour.
		}

		/** La fonction qui renvoie un tableau de taux de travail avec comme indices les mois de l'année donnée */

		public function simpleArrayByYear($matricule, $year){ // Format YYYY
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRate($this->simpleArrayByMonth($matricule, $yearOnly.'-'.($i + 1)));
			}
			return $checkYear;
		}

		/** La fonction qui renvoie le taux d'absences d'un employé pendant un jour */

		public function dayRate($matricule, $date){ // Renvoie le taux d'abscence de l'employée. Format de date YYYY-MM-DD
			$nbAbscence = 8 - $this->getHour($this->simpleArrayByDay($matricule, $date));
			return (($nbAbscence / 8) * 100);
		}

		/** La fonction qui renvoie le taux d'absences d'un employé pendant un mois */

		public function monthRate($matricule, $month){ // Format de month: YYYY-MM
			return (100 - $this->getRate($this->simpleArrayByMonth($matricule, $month)));
		}

		/** La fonction qui renvoie le taux d'absences d'un employé pendant une année */

		public function yearRate($matricule, $year){ // Format de year: YYYY
			$sumMonthRate = 0;
			foreach ($this->simpleArrayByYear($matricule, $year) as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}
	}	
?>