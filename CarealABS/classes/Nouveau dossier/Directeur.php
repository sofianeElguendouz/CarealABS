<?php

	require("ChefService.php");

	/**********************************
	* La classe concernant le directeur
	***********************************/

	class Directeur extends ChefService // Validé.
	{

		protected $tabService; // Le tableau qui contient tous les service.
		protected $numberEmployee; // Le nombre totale des employés.

		/** La fonction à appeler avant tous traitements */

		public function initiate(){

			// L'appel de la fonction du parent.
			parent::initiate();			

			// Récupération du tableau des services.
			$gettingServices = $this->datab->prepare("SELECT Num FROM department");
			$gettingServices->execute();

			// Maintenant le résultat $gettingServices contient le tableau qu'on veut récupérer.
			$this->tabService = $gettingServices->fetchAll(PDO::FETCH_OBJ);
			$this->numberEmployee = $this->totalNumberEmployee();
		}

		/****************************************************************/
		/** Cette classe utilise les méthodes de la classe ChefService **/
		/****************************************************************/
	
		/*************************************************/
		/** Les méthodes concernants l'école en général **/
		/*************************************************/

		/** La fonction qui renvoie un tableau de boolean avec comme indices les heures de travail dans une journée pour l'école en général */

		public function majorArrayByDay($day){ // $day a le format YYYY-MM-DD
			$checkDay = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkDay[$i] = false;
			}
			foreach ($this->tabService as $service) {
				$tab = $this->serviceArrayByDay($service->Num, $day);
				for ($i = 0; $i < 12; $i++) { 
					$checkDay[$i] = $checkDay[$i] or $tab[$i];
				}
			}
			return $checkDay;
		}

		/** La fonction qui renvoie le nombre des heures du travail de tous les employés pendant une journée donnée */

		public function getHourMajor($day){ // $day a le format YYYY-MM-DD
			$sumHours = 0;
			foreach ($this->tabService as $service) {
				$sumHours += $this->getHourService($service->Num, $day);
			}
			return $sumHours;
		}

		/** La fonction qui renvoie un tableau des heures de travail avec comme indices les jours du mois pour l'école en général */

		public function majorArrayByMonth($month){ // $month a le format YYYY-MM
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
				$checkMonth[$i] = $this->getHourMajor($format.'-'.($i + 1));
			}
			return $checkMonth;
		}

		/** La fonction qui renvoie le nombre total des employés */

		public function totalNumberEmployee(){
			$total = 0;
			foreach ($this->tabService as $service) {
				$total += $this->numberEmployee($service->Num);
			}
			return $total;
		}

		/** La fonction qui renvoie le taux de travaille pour l'école en général pendant un mois */

		public function getRateMajor($month){ // $month a le format YYYY-MM
			$sumHours = 0;
			$table = $this->majorArrayByMonth($month);
			foreach ($table as $dayHours) {
				$sumHours += $dayHours;
			}
			return ($sumHours / (8 * $this->numberEmployee * count($table)) * 100);
		}

		/** La fonction qui renvoie un tableau des taux de travail avec comme indices les mois d'une année pour l'école en général */

		public function majorArrayByYear($year){ // $year a le format YYYY
			$yearOnly = substr($year, 0, 4);
			$checkYear = [];
			for ($i = 0; $i < 12; $i++) { 
				$checkYear[$i] = $this->getRateMajor($yearOnly.'-'.($i + 1));
			}
			return $checkYear;
		}

		/** La fonction qui renvoie le taux d'absences de l'école pendant un jour */

		public function dayRateMajor($day){ // $day a le format YYYY-MM-DD
			$presenceRate = ($this->getHourMajor($day) / (8 * $this->numberEmployee) * 100);
			return (100 - $presenceRate);
		}

		/** La fonction qui renvoie le taux d'absences de l'école pendant un mois */

		public function monthRateMajor($month){ // $month a le format YYYY-MM
			return (100 - $this->getRateMajor($month));
		}

		/** La fonction qui renvoie le taux d'absences de l'école pendant une année */

		public function yearRateMajor($year){ // $year a le format YYYY
			$sumMonthRate = 0;
			$table = $this->majorArrayByYear($year);
			foreach ($table as $monthRate) {
				$sumMonthRate += $monthRate;
			}
			return (100 - ($sumMonthRate / 12));
		}

		/*******************************************************************************************/
		/** Les méthodes concernants les détails d'un service ou un employé qlq pour le Directeur **/
		/*******************************************************************************************/

		// Pour ceci il suffit d'appeler les méthodes héritées de la classe ChefService en passant les paramètres qu'il faut.
	}
?>