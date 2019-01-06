<?php 
    session_start();
    if(!isset($_SESSION['Login']))
      {
        header('Location:login.php');
      }
    require 'fonctions.php';
    $niveaux = array('Admin','Directeur','Sous-Directeur','Chef-Service','Employé-Simple');
    $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');
    $emp_dep=select_employes_departement($_SESSION['Departement']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Acceuil</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  
    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <!--................................................................................-->
    <link rel="stylesheet" href="style/css/normalize.css">
    <!--<link rel='stylesheet' href='style/Heures.css'>-->
    <link rel="stylesheet" href="style/test.css">
    <link rel="stylesheet" type="text/css" href="style/css/BeatPicker.css">
    <link href="style/css/confirmation.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>CarealABS</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Mr/Mme .Sous-Directeur(ice)</span>
                <?php echo"<h2>".$_SESSION['Nom']."</h2>"?>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="UserNiv2InfoGen.php"><i class="fa fa-home"></i> Accueil</a></li>
                  <li><a><i class="fa fa-edit"></i> Gestion Des Absences <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv2Dep.php">Département</a></li>
                      <li><a href="UserNiv2Serv.php">Services</a></li>
                      <li><a href="UserNiv2Emp.php">Employés</a></li>
                      <li><a href="UserNiv2Moi.php">Moi</a></li>
                      <?php if ($_SESSION['Service']==19) {
                        echo "<li><a href='UserNiv2JustConge.php'>Gestion congés et justifications</a></li>";
                      } ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-table"></i> Gestion Des Retenus <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv2Ret.php">Gestion De Mes Retenus</a></li>
                      <?php if ($_SESSION['Service']==19) {
                        echo "<li><a href='UserNiv2FichPaie.php'>Générer Fiches De Paie</a></li>";
                      } ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-desktop"></i> Hièrarchie <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv2org.php">Organigramme</a></li>
                      <li><a href="UserNiv2Str.php">Structure Administration</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bug"></i> Autres Pages <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv2Profile.php">Profil</a></li>
                      <li><a href="UserNiv2Info.php">A propos de nous</a></li>
                  
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="Login.php">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/img.jpg" alt=""><?php echo $_SESSION['Nom'];?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="UserNiv2Profile.php"> Profil</a></li>
                    <li><a href="UserNiv2Aide.php">Aide</a></li>
                    <li><a href="Login.php"><i class="fa fa-sign-out pull-right"></i> Déconnection</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col main" role="main" id="exporter">
          <div class="container">
            <div class="featured-block listEmployes">
              <div class='row'>
                  <?php 
                      for ($i=0; $i < count($emp_dep); $i++) { 
                        $ser = $bdd->prepare("SELECT * FROM services WHERE Num=:num");
                        $ser->execute(array(':num' =>$emp_dep[$i]['Service']));
                        $s=$ser->fetch();
                        echo "
                          <div class='col-md-3'>
                            <div class='block'>
                            <div class='thumbnailok text-center'>
                              <div class='captionok'>
                                <h1>".$emp_dep[$i]['Nom']."</h1>
                                <h6 id='".'p'.$i."'>".$emp_dep[$i]['Matricule']."</h6>
                                <h6>Niveau : ".$niveaux[$emp_dep[$i]['Rang']]."</h6>
                                <h6>Service: ".$s['Service']."</h6>
                                <a class='btnok' href='#' id='".$emp_dep[$i]['Matricule']."' style='border-radius: 15px 50px 30px;background-color:#03C9A9;'>More</a>
                              </div>
                              </div>
                            </div>
                          </div>
                        ";}
                  ?>
              </div>
            </div>
            <!--......................................................................................-->
              <div class="col-md-6 col-md-offset-3 date form-group  d">
                  </br>
                  <div class="text-center">
                      <h1>Veuillez Introduire La Date</h1>
                      <h5>Cliquez Sur Le Champ Pour Faire Apparaitre Le Calendrier</h5><br><br>
                  </div>
                  <input id="arecupa" class="arecup col-md-11" type="text" data-beatpicker="true" data-beatpicker-position="[120,50]" data-beatpicker-format="['YYYY','MM','DD']" required/>
                  <button id="afficher" class="btn btn-primary btn-lg btn-block affichbtn" type="submit">Afficher</button>
              </div>
              <div class="col-md-6 col-md-offset-3 date1 form-group  d">
                  </br>
                  <div class="text-center">
                      <h1>Veuillez Introduire La Plage De Temps</h1>
                      <h5>Cliquez Sur Le Champ Pour Faire Apparaitre Le Calendrier</h5><br><br>
                  </div>
                  <h3>Première Date :</h3>
                  <input id="arecup" class="arecup1 col-md-11" type="text" data-beatpicker="true" data-beatpicker-position="[120,50]" data-beatpicker-format="['YYYY','MM','DD']" required/>
                  <h3>Deuxième Date :</h3>
                  <input id="arecu" class="arecup2 col-md-11" type="text" data-beatpicker="true" data-beatpicker-position="[120,50]" data-beatpicker-format="['YYYY','MM','DD']" required/>
                  <button id="afficher1" class="btn btn-primary btn-lg btn-block affichbtn" type="submit">Afficher</button>
              </div>
              <!--......................................................................................-->
              <div class="row">  
                  <div class="right_col listChoix" role="main">
                    <div class="container">
                      <div class="featured-block">
                        <div class="col-md-offset-3">
                          <h1>Absences</h1>
                            <div class="btn-group khr btn-group-vertical" style="width:1100px;margin-left:-400px;">
                               <div class="rondBlanc">
                                    <div class="fond">
                                      <img src="images/fond.jpg"/>
                                    </div>
                                </div>
                               <div class="rond" style="margin-left:50px;background:#1abc9c;" title="0"><p>Par Jour</p><button type="button" class="btn btn-lg btn-primary" id="jour">Par jour</button></div>
                                <div class="rond" title="1" style="background:#3498db;"><p>Par Mois</p><button type="button" class="btn btn-lg btn-primary" id="mois">Par Mois</button></div>
                                <div class="rond" title="2" style="background:#bdc3c7;"><p>Par Annee</p><button type="button" class="btn btn-lg btn-primary" id="annee">Par Année</button></div>
                                <div class="rond" style="margin-left:210px;background:#e74c3c;" title="3"><p>Par Periode</p><button type="button" class="btn btn-lg btn-primary" id="periode">Par Période</button></div>
                                <div class="rond" title="4" style="background:#d35400;"><p>Par Historique</p><button type="button" class="btn btn-lg btn-primary" id="historique">Historique</button></div>
                               
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>

        <!-- /page content -->
        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Tout droits réservés, ESI. <a href="https://www.esi.dz"><b>Contacter-Nous</b></a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
        <div class="black-opacity"></div>
        <div class='alert alert-danger erreur'><p></p></div>
      </div>
    </div>

    

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="../vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <script src="../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="../vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="../vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	  <!--...............................................................................................-->
    <script type='text/javascript' src='style/js/jquery.canvasjs.min.js'></script>
    <script type="text/javascript" src="style/js/BeatPicker.js"></script>
    
    <!--...............................................................................................-->
    <script type="text/javascript" src="style/js/jspdf.min.js"></script>
    <script src="style/js/directeurOpacity.js"></script>
    <script type="text/javascript" src="style/js/html2canvas.js"></script>
    <script type="text/javascript">
              function genPDF(){
              html2canvas(document.getElementById("exporter"),{
                onrendered:function(canvas){
                  var img = canvas.toDataURL("image/png");
                  var doc = new jspdf("l","mm",[500,500]);
                  var nomFichier;
                  doc.addImage(img,'JPEG',0,0);
                  doc.save(exp);
                }
              });
              }
    </script>
    <!--...............................................................................................-->
   <script type="text/javascript">
      $('.listChoix').hide();
      $('.date').hide();
      $('.date1').hide();
      $('.erreur').hide();
      /*-------------------------*/
      $('.rond button').click(function(){ 
      });
      /*-------------------------*/
      $('.affichbtn').click(function(){
        var date;
        var k = $(this).attr('id').split('_');
        if(k[0]!='periode') {date = $('.arecup').val();}else{date = $('.arecup1').val();}
        var date2= $('.arecup2').val();

         
        var myDate1=date.split("-");
        var myDate2=date.split("-");
       var Sdate2=new Date(parseInt(myDate2[2], 10), parseInt(myDate2[1], 10) - 1 , parseInt(myDate1[0]), 10).getTime();
     
      
        if(date=="" || (date2=="" && k[0]=='periode'))
        {
          $('.erreur').show();
          $('.erreur p').text('veuillez introduire une date ')
          $('.erreur').css({'top':-60,'width':$('.right_col').width()+40,'left':$('.left_col').width()});
          $('.erreur').animate({'top':0},500);
          setTimeout(function(){$('.erreur').animate({'top':-100},500);},2500);
        }
        else 
        {
         var tab=$(this).attr('id').split('_');
         $('.d').hide();
         exp='EMP'+'-'+tab[0]+'-'+date+'-'+date2+'.pdf';
         $('.listEmployes').load('AffichAbsEmp.php','action='+tab[0]+'&Matricule='+tab[1]+'&Date='+date+'&Date1='+date2);
        }
      });
      /*----------------------*/
    </script>
    <script src="style/js/rondAnimation.js"></script>
  </body>
</html>