<?php 
 	 require 'classes/ChefService.php';
    /*-----------------------------------------*/
    $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $s = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $s->initiate();
    /*-----------------------------------------*/
    function tableHistoJour($jour)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absDay=$emp->serviceArrayByDay($_GET['Service'],$jour);
        return $absDay;
    }
    function tableHistoMois($mois)
    {
    	$datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
		$gettinginputs->execute(array('mat' => 1000000));
		$gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
		$emp->initiate();
        $absMonth=$emp->serviceArrayByMonth($_GET['Service'],$mois);
        $nbJours=count($absMonth[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$absMonth[1][$i],"y" =>$absMonth[0][$i]) );
            }

        return $dataPoints;
    }
    function tableHistoAnnee($year)
    {
 $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
		$gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
		$gettinginputs->execute(array('mat' => 1000000));
		$gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
		$emp->initiate();
        $absYear=$emp->serviceArrayByYear($_GET['Service'],$year);
        $nbMois=count($absYear);
        $dataPoints = array();
        for($i=0;$i<$nbMois;$i++)
            {
                array_push($dataPoints,array("label"=>$lesMois[$i],"y" =>$absYear[$i]));
            }
        return $dataPoints;
    }
    function tableHistoAnneeTauxAbs($year)
    {
 $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absYear=$emp->serviceArrayByYear($_GET['Service'],$year);
        $nbMois=count($absYear);
        $dataPoints = array();
        for($i=0;$i<$nbMois;$i++)
            {
                array_push($dataPoints,array("label"=>$lesMois[$i],"y" =>100-$absYear[$i]));
            }
        return $dataPoints;
    }
    function tableHistoPeriode($service,$d1,$d2)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayBand($_GET['Service'],$d1,$d2);
        $nbJours=count($absPeriode[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$absPeriode[0][$i],"label"=>$absPeriode[1][$i]));
            }
        return $dataPoints;
    }
    function tableHistoPeriodeTauxAbs($service,$d1,$d2)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayBand($_GET['Service'],$d1,$d2);
        $nbJours=count($absPeriode[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t=0;
                if (!$emp->isWeekend($absPeriode[1][$i])) { $t= 100-$absPeriode[0][$i]; }
                array_push($dataPoints,array("y" =>$t,"z" =>$t,"label"=>$absPeriode[1][$i]));
            }
        return $dataPoints;
    }
    function tableHistoMoisNbHAbs($mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayByMonthAbs($_GET['Service'],$mois);
        $nbJours=count($absPeriode[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$absPeriode[1][$i],"y" =>$absPeriode[0][$i]));
            }
        return $dataPoints;
    }
    function tableHistoMoisTauxAbs($mois)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayByMonth($_GET['Service'],$mois);
        $nbJours=count($absPeriode[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                $t=0;
                if (!$emp->isWeekend($absPeriode[1][$i])) { $t= 100-$absPeriode[0][$i]; }
                array_push($dataPoints,array("label"=>$absPeriode[1][$i],"y" =>$t));
            }
        return $dataPoints;
    }
    function tableHistoAnneeNbHAbs($annee)
    {
 $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayYearNbHeuresAbs($_GET['Service'],$annee);
        $nbJours=count($absPeriode);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$absPeriode[$i],"label"=>$lesMois[$i]));
            }
        return $dataPoints;
    }
    function tableHistoPeriodeNbHAbs($d1,$d2)
    {
        $datab = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
        $gettinginputs = $datab->prepare("SELECT * FROM salary WHERE Matricule = :mat");
        $gettinginputs->execute(array('mat' => 1000000));
        $gettinginput = $gettinginputs->fetch();
    $emp = new ChefService($gettinginput['ID'], $gettinginput['Matricule'], $gettinginput['Login'], $gettinginput['Password']);
        $emp->initiate();
        $absPeriode=$emp->serviceArrayBandNbHeuresAbs($_GET['Service'],$d1,$d2);
        $nbJours=count($absPeriode[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$absPeriode[0][$i],"label"=>$absPeriode[1][$i]));
            }
        return $dataPoints;
    }
    /*------------------------------------------------------------------------------------------*/
    $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
    $serv= $bdd->prepare("SELECT * FROM services WHERE Num = :num");
    $serv->execute(array( 'num' => $_GET['Service'] ));  
    $service = $serv->fetch();

 $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
    $date=explode('-',$_GET['Date']);
    $m=$date[0].'-'.$date[1];
    echo "<h1><a href='javascript:genPDF()' style='color:black'>Exporter</a></h1>";
    if($_GET['action']==='jour') {
    					echo "
    						<div class='text-center' style='background-color: #ddd;'>
    							<h1>Service : ".$service['Service']."</h1>
    							<h1>Absences/Présences Le ".$_GET['Date']."</h1>
    						</div>";
    					echo "<div class='col-md-10 col-md-offset-1'>
								<table class='container' style='background-color:#ddd;'>
                                <caption><h1>Travail Pour Chaque Heure</h1></caption>
									<tbody>
										<tr class='trt'>
											<th>Heures</th>";
											 for ($i=0;$i<3;$i++){ echo "<th class='tht'>0".($i+6)."h-0".($i+7)."h</th>";}
											 echo "<th class='tht'>09h-10h</th>";
											 for ($i=4;$i<12;$i++){ echo "<th class='tht'>".($i+6)."h-".($i+7)."h</th>";}	
									echo"</tr>
										<tr class='trt'>
											<td class='tdt'>Heures</br>Travaillées</td>";
											$absDay=tableHistoJour($_GET['Date']);
										    for ($i=0;$i<12;$i++){ echo "<td class='tdt'>";
											   if($absDay[$i]==true) {echo "<span class='glyphicon glyphicon-ok'></span>";}
											   else {echo "<span class='glyphicon glyphicon-remove'></span>";}
											echo "</td>";}"
										</tr>
									</tbody>
								</table>
                            </div>
    					";
    					/*---------------------------------*/
                        $dayRate=$s->dayRateService($_GET['Service'],$_GET['Date']);
                        $dayRate=round($dayRate,2);
    					echo "
        					<script>
    							  var elem = document.getElementById('myBar');   
								  var width = 1;
								  var id = setInterval(frame, 10);
								  function frame() {
								    if (width >= ".$dayRate.") {
								      clearInterval(id);
								    } else {
								      width++; 
								      elem.style.width = width + '%'; 
								    }
								  }
							</script>
                        <div col-md-7>
        					<h1>Taux D'absence  :  ".$dayRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                        </div>
                            <br>
    					";
                        /*-----------------------------*/
                        $dayRateJustif=$s->rateJustDay($_GET['Service'],$_GET['Date']);
                        $dayRateJustif=round($dayRateJustif,2);
                        echo "
                            <script>
                                  var elem = document.getElementById('myBar');   
                                  var width = 1;
                                  var id = setInterval(frame, 10);
                                  function frame() {
                                    if (width >= ".$dayRateJustif.") {
                                      clearInterval(id);
                                    } else {
                                      width++; 
                                      elem.style.width = width + '%'; 
                                    }
                                  }
                            </script><br><br>
                            <h1>Taux D'absences Justifiées :  ".$dayRateJustif." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";

    					/*---------------------------------*/
                        $A=$s->serviceAbsent($_GET['Service'],$_GET['Date']);
                        $P=$s->servicePresent($_GET['Service'],$_GET['Date']);
                        $dataPoints1 = array();
                        array_push($dataPoints1,array("y" =>$P,"name"=>"Présents", "exploded"=>"true"));
                        array_push($dataPoints1,array("y" =>$A,"name"=>"Absents"));
                        echo "
                        <script>
                            var chart = new CanvasJS.Chart('chartContainer',
                            {
                              title:{
                                text: 'Nombe Absences/Présences'
                              },
                              exportFileName: 'Pie Chart',
                              exportEnabled: true,
                                          animationEnabled: true,
                              legend:{
                                verticalAlign: 'bottom',
                                horizontalAlign: 'center'
                              },
                              data: [
                              {       
                                type: 'pie',
                                showInLegend: true,
                                toolTipContent: '{name}: <strong>{y} Employés</strong>',
                                indexLabel: '{name} {y} Employés',
                                dataPoints: ".json_encode($dataPoints1, JSON_NUMERIC_CHECK)."
                            }
                            ]
                            });
                            chart.render();
                        </script>
                        <div id='chartContainer' style='height: 300px;' class='col-md-5'></div>
                        ";         
                        /*--------------------------------*/
                        echo "
                        <div class='tile_count'>
                          <div class='col-md-5 col-sm-4 col-xs-6 tile_stats_count'>
                            <span class='count_top'><h1>Total Heures Travaillées</h1></span>
                            <div class='count green'>".round($s->serviceHeuresTravJour($_GET['Service'],$_GET['Date']),2)." H </div>
                          </div>
                          <div class='col-md-5 col-sm-4 col-xs-6 tile_stats_count'>
                            <span class='count_top'><h1>Total Heures Absentées</h1></span>
                            <div class='count green'>".round($s->serviceHeuresAbsJour($_GET['Service'],$_GET['Date']),2)." H </div>
                          </div>
                          <div class='col-md-5 col-sm-4 col-xs-6 tile_stats_count'>
                            <span class='count_top'><h1>Total Absences Justifiées</h1></span>
                            <div class='count green'>".round($s->nbJustDay($_GET['Service'],$_GET['Date']),2)." Abs</div>
                          </div>
                        </div>"; 
                        /*--------------------------------*/  
    				}
    	elseif($_GET['action']==='mois') {
    					echo "
    						<div class='text-center' style='background-color: #ddd;'>
    							<h1>Service : ".$service['Service']."</h1>
    							<h1>Absences/Présences En  ".$lesMois[intval($date[1])-1]."  ".$date[0]."</h1>
    						</div><br><br>";
				    	$dataPoints2=tableHistoMois($m);
				    	echo "
				    	<div id='presencesMois' style='height: 300px; width: 100%;'></div>
                        <br><br>
						<script type='text/javascript'>
						 var moi = {
						        title:  { text: 'Les Taux Des Présences' },
						        animationEnabled: true,
						        axisX: { title: 'Jours' },
						        axisY: { title: 'Taux De Présence' },
						        legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
						        data: 
						        [
						                    {
						                        type: 'column', //change it to line, area, bar, pie, etc
						                        legendText: 'Jours',
						                        dataPoints: ".json_encode($dataPoints2, JSON_NUMERIC_CHECK)."
						                    }
						        ]
						    };
						    $('#presencesMois').CanvasJSChart(moi);
						</script>
						";
                        /*---------------------------*/
                        $dataPoints12=tableHistoMoisTauxAbs($m);
                        echo "
                        <div id='tmois' style='height: 300px; width: 100%;'></div>
                        <br><br>
                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Taux Absences' },
                                animationEnabled: true,
                                axisX: { title: 'Jours' },
                                axisY: { title: 'Taux' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'line', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints12, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#tmois').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------*/
                        $dataPoints3=tableHistoMoisNbHAbs($m);
                        echo "
                        <div id='AbsencesPeriode' style='height: 300px; width: 100%;'></div>
                        <br><br>
                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Heures Perdues' },
                                animationEnabled: true,
                                axisX: { title: 'Jours' },
                                axisY: { title: 'Nombre Heures' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'column', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints3, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#AbsencesPeriode').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------*/
                        $HA=$s->serviceHeuresAbsMois($_GET['Service'],$m);
                        $HT=$s->serviceHeuresTravMois($_GET['Service'],$m);
                        $dataPoints4 = array();
                        array_push($dataPoints4,array("y" =>$HT,"name"=>"Total Heures De Travail"));
                        array_push($dataPoints4,array("y" =>$HA,"name"=>"Total Heures D'Absences","exploded"=>"true"));
                        echo"
                            <script>
                            var chart = new CanvasJS.Chart('HAbsTrav',
                            {
                              title:{
                                text: 'Total Heures Absences/Présences'
                              },
                              exportFileName: 'Pie Chart',
                              exportEnabled: true,
                                          animationEnabled: true,
                              legend:{
                                verticalAlign: 'bottom',
                                horizontalAlign: 'center'
                              },
                              data: [
                              {       
                                type: 'pie',
                                showInLegend: true,
                                toolTipContent: '{name}: <strong>{y} Heures</strong>',
                                indexLabel: '{y} Heures',
                                dataPoints: ".json_encode($dataPoints4, JSON_NUMERIC_CHECK)."
                            }
                            ]
                            });
                            chart.render();
                        </script>
                        <div class='row'>
                        <div id='HAbsTrav' style='height: 300px;' class='col-md-5 col-md-offset-3'></div>
                        </div>
                        ";
						/*---------------------------*/
                        $monthRate=$s->monthRateService($_GET['Service'],$m);
                        $monthRate=round($monthRate,2);
    					echo "
        					<script>
    							  var elem = document.getElementById('myBar');   
								  var width = 1;
								  var id = setInterval(frame, 10);
								  function frame() {
								    if (width >= ".$monthRate.") {
								      clearInterval(id);
								    } else {
								      width++; 
								      elem.style.width = width + '%'; 
								    }
								  }
							</script><br><br>
                            <h1>Taux D'absence  :  ".$monthRate." %</h1>
        					<div id='myProgress'>
        						<div id='myBar'></div>
        					</div>
        					<br><br>
    					";
                        /*-----------------------------*/
                        $monthRateJustif=$s->rateJustMonthService($_GET['Service'],$m);
                        $monthRateJustif=round($monthRateJustif,2);
                        echo "
                            <script>
                                  var elem = document.getElementById('myBar');   
                                  var width = 1;
                                  var id = setInterval(frame, 10);
                                  function frame() {
                                    if (width >= ".$monthRateJustif.") {
                                      clearInterval(id);
                                    } else {
                                      width++; 
                                      elem.style.width = width + '%'; 
                                    }
                                  }
                            </script><br><br>
                            <h1>Taux D'absences Justifiées :  ".$monthRateJustif." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";
                        /*-----------------------------*/
                        echo"
                        <div class='tile_count'>
                          <div class='col-md-4 col-sm-4 col-xs-6 tile_stats_count'>
                            <span class='count_top'><h1>Equiv Jours Perdues</h1></span>
                            <div class='count green'>".round($s->serviceHeuresAbsMois($_GET['Service'],$m)/24)." j </div>
                          </div>
                          <div class='col-md-4 col-sm-4 col-xs-6 tile_stats_count'>
                            <span class='count_top'><h1>Total Absences Justifiées</h1></span>
                            <div class='count green'>".$s->nbJustMonthService($_GET['Service'],$m)." Abs</div>
                          </div>
                        </div>";
                        /*-----------------------------------*/
    			}
    	elseif($_GET['action']==='annee') {
    					echo "
    						<div class='text-center' style='background-color: #ddd;'>
    							<h1>Service : ".$service['Service']."</h1>
    							<h1>Absences/Présences En ".$date[0]."</h1>
    					    </div>";
                        /*-------------------------*/
                        $yearRate=$s->yearRateService($_GET['Service'],$date[0]);
                        $yearRate=round($yearRate,2);
                        echo "
                            <script>
                                  var elem = document.getElementById('myBar');   
                                  var width = 1;
                                  var id = setInterval(frame, 10);
                                  function frame() {
                                    if (width >= ".$yearRate.") {
                                      clearInterval(id);
                                    } else {
                                      width++; 
                                      elem.style.width = width + '%'; 
                                    }
                                  }
                            </script><br><br>
                            <h1>Taux D'absence  :  ".$yearRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";
                        /*--------------------------------------------*/
                        $yearRateJustif=$s->rateJustYearService($_GET['Service'],$date[0]);
                        $yearRateJustif=round($yearRateJustif,2);
                        echo "
                            <script>
                                  var elem = document.getElementById('myBar');   
                                  var width = 1;
                                  var id = setInterval(frame, 10);
                                  function frame() {
                                    if (width >= ".$yearRateJustif.") {
                                      clearInterval(id);
                                    } else {
                                      width++; 
                                      elem.style.width = width + '%'; 
                                    }
                                  }
                            </script><br><br>
                            <h1>Taux D'absences Justifiées :  ".$yearRateJustif." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";
                        /*--------------------------------------------*/
				    	$dataPoints5=tableHistoAnnee($date[0]);
				    	echo "
				    	<div id='PresencesAnnee' class='row ' style='height: 300px;'></div>
						<script type='text/javascript'>
						 var moi = {
						        title:  { text: 'Evolution Taux De Présences' },
						        animationEnabled: true,
						        axisX: { title: 'Année' },
						        axisY: { title: 'Taux De Présence' },
						        legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
						        data: 
						        [
						                    {
						                        type: 'line', //change it to line, area, bar, pie, etc
						                        legendText: 'Jours',
						                        dataPoints: ".json_encode($dataPoints5, JSON_NUMERIC_CHECK)."
						                    }
						        ]
						    };
						    $('#PresencesAnnee').CanvasJSChart(moi);
						</script>
						";
						/*-------------------------*/
                        $dataPoints11=tableHistoAnneeTauxAbs($date[0]);
                        echo "
                        <div id='tabsann' class='row ' style='height: 300px;'></div>
                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Evolution Taux Absences' },
                                animationEnabled: true,
                                axisX: { title: 'Année' },
                                axisY: { title: 'Taux' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'line', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints11, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#tabsann').CanvasJSChart(moi);
                        </script>
                        ";
                        /*-------------------------*/
                        $dataPoints10=tableHistoAnneeNbHAbs($date[0]);
                        echo "
                        <div id='AbsencesAnnee' class='row' style='height: 300px;'></div><br>
                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Evolution Nombre Heures Absentées'},
                                animationEnabled: true,
                                axisX: { title: 'Année' },
                                axisY: { title: 'Nombre Heures' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'column', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints10, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#AbsencesAnnee').CanvasJSChart(moi);
                        </script>
                        ";
                        /*--------------------------------------------*/
                        $HA=$s->serviceHeuresAbsAnnee($_GET['Service'],$date[0]);
                        $HT=$s->serviceHeuresTravAnnee($_GET['Service'],$date[0]);
                        $dataPoints6 = array();
                        array_push($dataPoints6,array("y" =>$HT,"name"=>"Total Heures De Travail"));
                        array_push($dataPoints6,array("y" =>$HA,"name"=>"Total Heures D'Absences","exploded"=>"true"));
                        echo"
                            <script>
                            var chart = new CanvasJS.Chart('HAbsTrav',
                            {
                              title:{
                                text: 'Total Heures Absences/Présences'
                              },
                              exportFileName: 'Pie Chart',
                              exportEnabled: true,
                                          animationEnabled: true,
                              legend:{
                                verticalAlign: 'bottom',
                                horizontalAlign: 'center'
                              },
                              data: [
                              {       
                                type: 'pie',
                                showInLegend: true,
                                toolTipContent: '{name}: <strong>{y} Heures</strong>',
                                indexLabel: '{y} Heures',
                                dataPoints: ".json_encode($dataPoints6, JSON_NUMERIC_CHECK)."
                            }
                            ]
                            });
                            chart.render();
                        </script>
                        <div class='row>'
                        <div id='HAbsTrav' style='height: 300px;' class='col-md-5 col-md-offset-3'></div><br>
                        </div>
                        ";
                        /*----------------------------------------*/
                        echo"
                        <div class='row tile_count'>
                          <div class='col-md-3 col-sm-4 col-xs-6 col-md-offset-1 tile_stats_count'>
                            <span class='count_top'><h1>Equiv Jours Perdues</h1></span>
                            <div class='count green'>".round($s->serviceHeuresAbsAnnee($_GET['Service'],$date[0])/24)." j </div>
                          </div>
                          <div class='col-md-3 col-sm-4 col-xs-6 col-md-offset-1 tile_stats_count'>
                            <span class='count_top'><h1>Total Absences Justifiées</h1></span>
                            <div class='count green'>".$s->nbJustYearService($_GET['Service'],$date[0])." Abs</div>
                          </div>
                        </div>";
                        /*--------------------------------------------*/
    			}
        elseif($_GET['action']==='periode') {
                        echo "
                            <div class='text-center' style='background-color: #ddd;'>
                                <h1>Service : ".$_GET['Service']."</h1>
                                <h1>Absences/Présences Entre ".$_GET['Date']." ET ".$_GET['Date1']."</h1>
                        </div>";
                        /*----------------------------*/
                        $dataPoints7=tableHistoPeriode($_GET['Service'],$_GET['Date'],$_GET['Date1']);
                        echo "
                        <div id='presencesPeriode' style='height: 300px; width: 100%;'></div>

                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Les Taux Des Présences' },
                                animationEnabled: true,
                                axisX: { title: 'Jours' },
                                axisY: { title: 'Taux De Présence' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'column', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints7, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#presencesPeriode').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------*/
                        $dataPoints13=tableHistoPeriodeTauxAbs($_GET['Service'],$_GET['Date'],$_GET['Date1']);
                        echo "
                        <div id='periodet' style='height: 300px; width: 100%;'></div>

                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Taux Absences' },
                                animationEnabled: true,
                                axisX: { title: 'Jours' },
                                axisY: { title: 'Taux' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'bubble', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints13, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#periodet').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------*/
                        $dataPoints8=tableHistoPeriodeNbHAbs($_GET['Date'],$_GET['Date1']);
                        echo "
                        <div id='AbsencesPeriode' style='height: 300px; width: 100%;'></div>

                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Heures Perdues' },
                                animationEnabled: true,
                                axisX: { title: 'Jours' },
                                axisY: { title: 'Nombre Heures' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'column', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints8, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#AbsencesPeriode').CanvasJSChart(moi);
                        </script>
                        ";
                        /*------------------------*/
                        $HA=$s->serviceHeuresAbsPeriode($_GET['Service'],$_GET['Date'],$_GET['Date1']);
                        $HT=$s->serviceHeuresTravPeriode($_GET['Service'],$_GET['Date'],$_GET['Date1']);
                        $dataPoints9 = array();
                        array_push($dataPoints9,array("y" =>$HT,"name"=>"Total Heures De Travail"));
                        array_push($dataPoints9,array("y" =>$HA,"name"=>"Total Heures D'Absences","exploded"=>"true"));
                        echo"
                            <script>
                            var chart = new CanvasJS.Chart('HAbsTrav',
                            {
                              title:{
                                text: 'Total Heures Absences/Présences'
                              },
                              exportFileName: 'Pie Chart',
                              exportEnabled: true,
                                          animationEnabled: true,
                              legend:{
                                verticalAlign: 'bottom',
                                horizontalAlign: 'center'
                              },
                              data: [
                              {       
                                type: 'pie',
                                showInLegend: true,
                                toolTipContent: '{name}: <strong>{y} Heures</strong>',
                                indexLabel: '{y} Heures',
                                dataPoints: ".json_encode($dataPoints9, JSON_NUMERIC_CHECK)."
                            }
                            ]
                            });
                            chart.render();
                        </script>
                        <div class='row'>
                        <div id='HAbsTrav' style='height: 300px;' class='col-md-5 col-md-offset-3'></div>
                        </div>
                        ";
                        /*-----------------------------*/
                        $periodeRate=$s->bandRateService($_GET['Service'],$_GET['Date'],$_GET['Date1']);
                        $periodeRate=round($periodeRate,2);
                        echo "
                            <script>
                                  var elem = document.getElementById('myBar');   
                                  var width = 1;
                                  var id = setInterval(frame, 10);
                                  function frame() {
                                    if (width >= ".$periodeRate.") {
                                      clearInterval(id);
                                    } else {
                                      width++; 
                                      elem.style.width = width + '%'; 
                                    }
                                  }
                            </script><br><br>
                            <h1>Taux D'absence  :  ".$periodeRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";
                        /*---------------------------*/
                        $periodeRateJustif=$s->rateJustBandService($_GET['Service'],$_GET['Date'],$_GET['Date1']);
                        $periodeRateJustif=round($periodeRateJustif,2);
                        echo "
                            <script>
                                  var elem = document.getElementById('myBar');   
                                  var width = 1;
                                  var id = setInterval(frame, 10);
                                  function frame() {
                                    if (width >= ".$periodeRateJustif.") {
                                      clearInterval(id);
                                    } else {
                                      width++; 
                                      elem.style.width = width + '%'; 
                                    }
                                  }
                            </script><br><br>
                            <h1>Taux D'absences Justifiées :  ".$periodeRateJustif." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";
                        /*---------------------------*/
                        echo"
                        <div class='row tile_count'>
                            <div class='col-md-4 col-sm-4 col-xs-6 tile_stats_count'>
                                <span class='count_top'><h1>Equiv Jours Perdues</h1></span>
                                <div class='count green'>".round($s->serviceHeuresAbsPeriode($_GET['Service'],$_GET['Date'],$_GET['Date1'])/24)." H </div>
                            </div>
                            <div class='col-md-4 col-sm-4 col-xs-6 col-md-offset-1 tile_stats_count'>
                                <span class='count_top'><h1>Total Absences Justifiées</h1></span>
                                <div class='count green'>".$s->nbJustBandService($_GET['Service'],$_GET['Date'],$_GET['Date1'])." Abs</div>
                            </div>
                        </div>";
                        /*-----------------------------*/
                        
        }
?>