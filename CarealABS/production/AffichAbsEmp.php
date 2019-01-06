<?php 
 	 require 'classes/Employe.php';
 	 $lesMois = array(' ','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
     $e=new Employe(0,0,' ',' ');
    /*-----------------------------------------*/
    function tableHistoJour($jour)
    {
        $emp=new Employe(0,0,' ',' ');
        $absDay=$emp->simpleArrayByDay($_GET['Matricule'],$jour);
        return $absDay;
    }
    function tableHistoMois($mois)
    {
        $emp=new Employe(0,0,' ',' ');
        $absMonth=$emp->simpleArrayByMonthPres($_GET['Matricule'],$mois);
        $nbJours=count($absMonth);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$mois."-".($i+1),"y" =>$absMonth[$i]));
            }
        return $dataPoints;
    }
    function tableHistoAnnee($year)
    {
        $emp=new Employe(0,0,' ',' ');
        $absYear=$emp->simpleArrayByYear($_GET['Matricule'],$year);
        $nbMois=count($absYear);
        $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
        $dataPoints = array();
        for($i=0;$i<$nbMois;$i++)
            {
                array_push($dataPoints,array("y" =>$absYear[$i],"label"=>$lesMois[$i]));
            }
        return $dataPoints;
    }
    function piePeriode($p1,$p2)
    {
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->band($_GET['Matricule'],$p1,$p2);
        $nbD=$emp->nbDays($p1,$p2);
        $dataPoints = array();
        array_push($dataPoints,array("y" =>$abs*100/($abs+$nbD),"name"=>"% Absence Durant La Période", "exploded"=>"true"));
        array_push($dataPoints,array("y" =>$nbD*100/($abs+$nbD),"name"=>"% Présences Durant La Période"));
        return $dataPoints;
    }
    function tableHistoPeriode($d1,$d2)
    {
    	$emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayBand($_GET['Matricule'],$d1,$d2);
        $nbJours=count($abs[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$abs[0][$i],"label"=>$abs[1][$i]));
            }
        return $dataPoints;
    }
    function tableHistoMoisNbHAbs($mois)
    {
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayByMonthAbs($_GET['Matricule'],$mois);
        $nbJours=count($abs);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("label"=>$mois."-".($i+1),"y" =>$abs[$i]));
            }
        return $dataPoints;
    }
    function tableHistoMoisTauxAbs($mois)
    {
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayByMonthTauxAbs($_GET['Matricule'],$mois);
        $nbJours=count($abs[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$abs[0][$i],"z" =>$abs[0][$i],"label"=>$abs[1][$i]));
            }
        return $dataPoints;
    }
    function tableHistoAnneeNbHAbs($annee)
    {
        $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayByYearNbHAbs($_GET['Matricule'],$annee);
        $nbMois=count($abs);
        $dataPoints = array();
        for($i=0;$i<$nbMois;$i++)
            {
                array_push($dataPoints,array("label"=>$lesMois[$i],"y" =>$abs[$i]));
            }
        return $dataPoints;
    }
    function tableHistoAnneeTauxAbs($annee)
    {
        $lesMois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decemebre');
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayByYearTauxAbs($_GET['Matricule'],$annee);
        $nbMois=count($abs);
        $dataPoints = array();
        for($i=0;$i<$nbMois;$i++)
            {
                array_push($dataPoints,array("label"=>$lesMois[$i],"y" =>$abs[$i],"z" =>$abs[$i]));
            }
        return $dataPoints;
    }
    function tableHistoPeriodeNbHAbs($d1,$d2)
    {
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayBandAbs($_GET['Matricule'],$d1,$d2);
        $nbJours=count($abs[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$abs[0][$i],"label"=>$abs[1][$i]));
            }
        return $dataPoints;
    }
    function tableHistoPeriodeTauxAbs($d1,$d2)
    {
        $emp=new Employe(0,0,' ',' ');
        $abs=$emp->simpleArrayBandTauxAbs($_GET['Matricule'],$d1,$d2);
        $nbJours=count($abs[0]);
        $dataPoints = array();
        for($i=0;$i<$nbJours;$i++)
            {
                array_push($dataPoints,array("y" =>$abs[0][$i],"z" =>$abs[0][$i],"label"=>$abs[1][$i]));
            }
        return $dataPoints;
    }
    /*------------------------------------------------------------------------------------------*/
    $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
    $emp= $bdd->prepare("SELECT * FROM salary WHERE Matricule = :mat");
    $emp->execute(array( 'mat' => $_GET['Matricule'] ));  
    $pers = $emp->fetch();
    $date=explode('-',$_GET['Date']);
    $m=$date[0].'-'.$date[1];
    echo "<h1><a href='javascript:genPDF()' style='color:black'>Exporter</a></h1>";
    if($_GET['action']==='jour') {
    					echo "
    						<div class='text-center' style='background-color: #ddd;'>
    							<h1>Employé : ".$pers['Nom']." ".$pers['Prenom']."</h1>
    							<h1>Absences/Présences Le ".$_GET['Date']."</h1>
    						</div>";
    					echo "
          				<div class='row tile_count'>
    					  <div class='col-md-6 col-sm-4 col-xs-6 tile_stats_count'>
			              	 <span class='count_top'><h1>Total Heures Absentées</h1></span>
			                 <div class='count green'>".$e->simpleHeuresAbsJour($_GET['Matricule'],$_GET['Date'])." H </div>
			              </div>
                          <div class='col-md-6 col-sm-4 col-xs-6 tile_stats_count'>
                            <span class='count_top'><h1>Total Heures Travaillées</h1></span>
                          <div class='count green'>".$e->simpleHeuresTravJour($_GET['Matricule'],$_GET['Date'])." H </div>
                        </div>
                        </div>";
    					echo "
    						<div>
								<table class='container'>
									<tbody>
										<caption><h1>Heures Travaillées Par Jour</h1></caption>
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
											   if($absDay[$i]) {echo "<span class='glyphicon glyphicon-ok'></span>";}
											   else {echo "<span class='glyphicon glyphicon-remove'></span>";}
											echo "</td>";}
									echo "
										</tr>
									</tbody>
								</table>
							</div>
							<br><br><br>
    					";
    					/*----------------------------------------*/
    					$dayRate=$e->dayRate($_GET['Matricule'],$_GET['Date']);
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
							<h1>Taux D'absences  :  ".$dayRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
    					";
    				/*---------------------------------------------------------------------------------------------------*/
    				}	
    	elseif($_GET['action']==='mois') {
                        echo "
                            <div class='text-center' style='background-color: #ddd;'>
                                <h1>Employé : ".$pers['Nom']." ".$pers['Prenom']."</h1>
                                <h1>Absences/Présences En  ".$lesMois[intval($date[1])]."  ".$date[0]."</h1>
                            </div><br><br>";
    					/*--------------------------------------*/
				    	$dataPoints1=tableHistoMois($m);
				    	echo "
				    	<div class='row'>
				    		<div id='presencesMois' style='height: 300px;' class='col-md-12'></div>
				    		<div id='cercle' class='col-md-4'></div>
				    	</div>
						<script type='text/javascript'>
						 var moi = {
						        title:  { text: 'Taux De Présences Durant le Mois' },
						        animationEnabled: true,
						        axisX: { title: 'Jours' },
						        axisY: { title: 'Taux' },
						        legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
						        data: 
						        [
						                    {
						                        type: 'column', //change it to line, area, bar, pie, etc
						                        legendText: 'Jours',
						                        dataPoints: ".json_encode($dataPoints1, JSON_NUMERIC_CHECK)."
						                    }
						        ]
						    };
						    $('#presencesMois').CanvasJSChart(moi);
						</script>
						";
                        /*---------------------------------*/
                        $dataPoints7=tableHistoMoisTauxAbs($m);
                        echo "
                        <div id='mt' style='height: 300px; width: 100%;'></div>

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
                                                dataPoints: ".json_encode($dataPoints7, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#mt').CanvasJSChart(moi);
                        </script>
                        ";
						/*---------------------------------*/
                        $dataPoints2=tableHistoMoisNbHAbs($m);
                        echo "
                        <div id='AbsencesMois' style='height: 300px; width: 100%;'></div>

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
                                                dataPoints: ".json_encode($dataPoints2, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#AbsencesMois').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------------*/
						echo "
          				<div class='row tile_count'>
    					  <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Absentées</h2></span>
			              	<div class='count green'>".$e->simpleHeuresAbsMois($_GET['Matricule'],$m)." H</div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Travaillées</h2></span>
			              	<div class='count green'>".$e->simpleHeuresTravMois($_GET['Matricule'],$m)." H</div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Absences Justifiées</h2></span>
			              	<div class='count green'>".$e->nbJustMonth($_GET['Matricule'],$m)." Abs</div>
			              </div>
			            </div>";
                        /*---------------------------*/
                        $monthRate=$e->monthRate($_GET['Matricule'],$m);
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
                            </script>
                        <div col-md-7>
                            <h1>Taux D'absence  :  ".$monthRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                        </div><br>
                        ";
						/*---------------------------------*/
						$monthRateJustif=$e->rateJustMonth($_GET['Matricule'],$m);
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
							</script>
                            <h1>Taux D'absences Justifiées :  ".$monthRateJustif." %</h1>
        					<div id='myProgress'>
        						<div id='myBar'></div>
        					</div>
        					<br><br>
    					";
                        /*-------------------------------*/
    			}
    	elseif($_GET['action']==='annee') {
                        echo "
                            <div class='text-center' style='background-color: #ddd;'>
                                <h1>Employé : ".$pers['Nom']." ".$pers['Prenom']."</h1>
                                <h1>Absences/Présences En ".$date[0]."</h1>
                        </div>";
                        /*--------------------------*/
				    	$dataPoints2=tableHistoAnnee($date[0]);
				    	echo "
				    	<div id='presencesAnnee' style='height: 300px; width: 100%;'></div>

						<script type='text/javascript'>
						 var moi = {
						        title:  { text: 'Taux Des Présences En Une Année' },
						        animationEnabled: true,
						        axisX: { title: 'Mois' },
						        axisY: { title: 'Taux Heures Travaillées' },
						        legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
						        data: 
						        [
						                    {
						                        type: 'line', //change it to line, area, bar, pie, etc
						                        legendText: 'Mois',
						                        dataPoints: ".json_encode($dataPoints2, JSON_NUMERIC_CHECK)."
						                    }
						        ]
						    };
						    $('#presencesAnnee').CanvasJSChart(moi);
						</script>
						
						";
						/*-------------------------*/
                        $dataPoints8=tableHistoAnneeTauxAbs($date[0]);
                        echo "
                        <div id='at' style='height: 300px; width: 100%;'></div>

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
                                                dataPoints: ".json_encode($dataPoints8, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#at').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------------*/
                        $dataPoints3=tableHistoAnneeNbHAbs($date[0]);
                        echo "
                        <div id='AbsencesAnnee' style='height: 300px; width: 100%;'></div>

                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Nombre Heures Absentées' },
                                animationEnabled: true,
                                axisX: { title: 'Mois' },
                                axisY: { title: 'Nombre Heures' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'line', //change it to line, area, bar, pie, etc
                                                legendText: 'Mois',
                                                dataPoints: ".json_encode($dataPoints3, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#AbsencesAnnee').CanvasJSChart(moi);
                        </script>
                        
                        ";
                        /*-------------------------*/
    					echo "
          				<div class='row tile_count'>
    					  <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Absentées</h2></span>
			              	<div class='count green'>".$e->simpleHeuresAbsAnnee($_GET['Matricule'],$date[0])." H</div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Travaillées</h2></span>
			              	<div class='count green'>".$e->simpleHeuresTravAnnee($_GET['Matricule'],$date[0])." H</div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Absences Justifiées</h2></span>
			              	<div class='count green'>".$e->nbJustYear($_GET['Matricule'],$date[0])." Abs</div>
			              </div>
			            </div>";
						/*---------------------------------*/
                        $yearRate=$e->yearRate($_GET['Matricule'],$date[0]);
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
                            <h1>Taux D'absences  :  ".$yearRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
                        ";
                        /*---------------------------------*/
						$yearRateJustif=$e->rateJustYear($_GET['Matricule'],$date[0]);
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
    					/*---------------------------------*/
    			}
    	elseif($_GET['action']==='periode') {
    					echo "
    						<div class='text-center' style='background-color: #ddd;'>
    							<h1>Employé : ".$pers['Nom']." ".$pers['Prenom']."</h1>
    							<h1>Absences/Présences Entre ".$_GET['Date']." ET ".$_GET['Date1']."</h1>
    					</div>";
                        /*---------------------------------*/
                        $dataPoints5=tableHistoPeriode($_GET['Date'],$_GET['Date1']);
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
                                                dataPoints: ".json_encode($dataPoints5, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#presencesPeriode').CanvasJSChart(moi);
                        </script>
                        ";
                        /*----------------------------------*/
                        $dataPoints9=tableHistoPeriodeTauxAbs($_GET['Date'],$_GET['Date1']);
                        echo "
                        <div id='at' style='height: 300px; width: 100%;'></div>
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
                                                dataPoints: ".json_encode($dataPoints9, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#at').CanvasJSChart(moi);
                        </script>
                        ";
                        /*---------------------------------*/
                        $dataPoints6=tableHistoPeriodeNbHAbs($_GET['Date'],$_GET['Date1']);
                        echo "
                        <div id='AbsencesPeriode' style='height: 300px; width: 100%;'></div>

                        <script type='text/javascript'>
                         var moi = {
                                title:  { text: 'Nombre Heures Absentées' },
                                animationEnabled: true,
                                axisX: { title: 'Jours' },
                                axisY: { title: 'Nombre Heures' },
                                legend: { verticalAlign: 'bottom',horizontalAlign: 'center' },
                                data: 
                                [
                                            {
                                                type: 'column', //change it to line, area, bar, pie, etc
                                                legendText: 'Jours',
                                                dataPoints: ".json_encode($dataPoints6, JSON_NUMERIC_CHECK)."
                                            }
                                ]
                            };
                            $('#AbsencesPeriode').CanvasJSChart(moi);
                        </script>
                        ";      
                        /*-------------------------*/
                        $dataPoints4=piePeriode($_GET['Date'],$_GET['Date1']);
                        echo "
                            <br><br><br>
                            <div id='chartContainer' style='height: 300px;'class='row'></div>
                            <script type='text/javascript'>
                            var chart = new CanvasJS.Chart('chartContainer',
                            {
                              title:{
                                text: 'Pourcentage Absences/Presences'
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
                                toolTipContent: '{name}: <strong>{y}%</strong>',
                                indexLabel: '{name} {y}%',
                                dataPoints: ".json_encode($dataPoints4, JSON_NUMERIC_CHECK)."
                                    }
                                    ]
                                    });
                                    chart.render();
                            </script>
                        ";
                        /*----------------------------------------*/
						$periodeRate=$e->bandRate($_GET['Matricule'],$_GET['Date'],$_GET['Date1']);
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
                            <h1>Taux D'absences :  ".$periodeRate." %</h1>
                            <div id='myProgress'>
                                <div id='myBar'></div>
                            </div>
                            <br><br>
    					";
    					/*-------------------------*/
                        $periodeRateJustif=$e->rateJustBand($_GET['Matricule'],$_GET['Date'],$_GET['Date1']);
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
                        /*---------------------------------*/
						echo "
          				<div class='row tile_count'>
    					  <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Absentées</h2></span>
			              	<div class='count green'>".$e->simpleHeuresAbsPeriode($_GET['Matricule'],$_GET['Date'],$_GET['Date1'])." H</div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Heures Travaillées</h2></span>
			              	<div class='count green'>".$e->simpleHeuresTravPeriode($_GET['Matricule'],$_GET['Date'],$_GET['Date1'])." H</div>
			              </div>
			              <div class='col-md-3 col-sm-4 col-xs-6 tile_stats_count'>
			              	<span class='count_top'><i class='fa fa-user'></i><h2>Total Absences Justifiées</h2></span>
			              	<div class='count green'>".$e->nbJustBand($_GET['Matricule'],$_GET['Date'],$_GET['Date1'])." Abs</div>
			              </div>
			            </div>";
						/*----------------------------------------*/
    			}
?>