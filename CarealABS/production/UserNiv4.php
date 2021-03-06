<?php 
    if(!isset($_SESSION['Login']))
      {
        header('Location:login.php');
      }
    require 'Acceuil.php';
    $datetime = date("Y-m-d");
    $date=explode('-',$datetime);
    $mois=$date[0].'-'.$date[1];
    $dataPoints1=tableHistoMoisEmp($_SESSION['Matricule'],$mois);
    $dataPoints2=tableHeuresAbsEmp($_SESSION['Matricule'],$mois);
    $notifTauxAbs=notifTauxAbsEmp($mois,$_SESSION['Matricule']);
    /*---------------------------------------------*/
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
                <span>Mr/Mme.Employé(e)</span>
                <?php echo"<h2>".$_SESSION['Nom']."</h2>"?>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="UserNiv4InfoGen.php"><i class="fa fa-home"></i>Accueil</a></li>
                  <li><a href="UserNiv4Moi.php"><i class="fa fa-edit"></i> Gestion De Mes Absences</a></li>
                    <?php if ($_SESSION['Service']==19) {
                        echo "<li><a href='UserNiv4JustConge.php'>Gestion congés et justifications</a></li>";
                      } ?>
                  <li><a><i class="fa fa-table"></i> Gestion Des Retenus <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv4Ret.php">Gestion De Mes Retenus</a></li>
                      <?php if ($_SESSION['Service']==19) {
                        echo "<li><a href='UserNiv4FichPaie.php'>Générer Fiches De Paie</a></li>";
                      } ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-desktop"></i> Hièrarchie <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv4org.php">Organigramme</a></li>
                      <li><a href="UserNiv4Str.php">Structure Administration</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bug"></i> Autres Pages <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv4Profile.php">Profil</a></li>
                      <li><a href="UserNiv4Info.php">A propos de nous</a></li>
              
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
                    <li><a href="UserNiv4Profile.php"> Profil</a></li>
                    <li><a href="UserNiv4Aide.php">Aide</a></li>
                    <li><a href="Login.php"><i class="fa fa-sign-out pull-right"></i> Déconnection</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <!-- /top tiles -->
          <div class="text-center notif">
              <h1 style="color: #2f4f4f">Résumé D'absentéisme Pour Le Mois Courant</h1>
                    <button id="myBtn0" class="btnNotif"><span class="glyphicon glyphicon-triangle-top red"></span></button>
                            <div id="myModal0" class="modal">
                              <div class="modal-content">
                                <span class="close">&times;</span>
                                <p><?php 
                                        if ($notifTauxAbs>0) {echo "Mon Taux D'absence a Augmenté de ".$notifTauxAbs." %";} 
                                        else echo "Mon Taux D'absence N'a Pas Augmenté";
                                    ?>
                                </p>
                              </div>
                            </div>
                    <!--..................................................-->
                    <button id="myBtn1" class="btnNotif"><span class="glyphicon glyphicon-triangle-bottom green"></span></button>
                            <div id="myModal1" class="modal">
                              <div class="modal-content">
                                <span class="close">&times;</span>
                                <p><?php 
                                        if ($notifTauxAbs<0) {echo "Mon Taux D'absence a Diminué de ".$notifTauxAbs." %";} 
                                        else echo "Mon Taux D'absence N'a Pas Diminué";
                                    ?>
                                </p>
                              </div>
                            </div>  
          </div>
          <br><br><br>
          <div id="chartContainer1" style="width: 45%; height: 400px;display: inline-block;"></div>
          <div id="chartContainer4" style="width: 45%; height: 400px;display: inline-block;" class="col-md-offset-1"></div>
          <br><br>
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
    <script type="text/javascript" src="style/js/highcharts.js"></script>
    <script type="text/javascript" src="style/js/drilldown.js"></script>
    <script type="text/javascript" src="style/js/data.js"></script>
    <!--...............................................................................................-->
    <script type="text/javascript">
          var modal = document.getElementById('myModal0');
          var btn0 = document.getElementById("myBtn0");
          var span0 = document.getElementsByClassName("close")[0]; 
          btn0.onclick = function() { modal.style.display = "block";}
          span0.onclick = function() { modal.style.display = "none"; }
          window.onclick = function(event) { if (event.target == modal) {modal.style.display = "none";}}
          /******************************************/
          var modal1 = document.getElementById('myModal1');
          var btn1 = document.getElementById("myBtn1");
          var span1 = document.getElementsByClassName("close")[1]; 
          btn1.onclick = function() { modal1.style.display = "block";}
          span1.onclick = function() { modal1.style.display = "none"; }
          window.onclick = function(event) { if (event.target == modal1) {modal1.style.display = "none";}}
    </script>
    <script type="text/javascript">
        window.onload = function () {
        $(function () {
        var options = {
        title: {
          text: "Mon Total Heures Perdu Pour Chaque Jour De Ce Mois",
          fontColor: "#2f4f4f",
          fontSize: 20,
          padding: 10,
          margin: 15,
          backgroundColor: "#FFFFE0",
          borderThickness: 1,
          cornerRadius: 5,
          fontWeight: "bold"
        },
        animationEnabled: true,
        axisX: {
          interval: 3
        },
        axisY: {
          title: "Nombre Heures"
        },
        legend: {
          verticalAlign: "bottom",
          horizontalAlign: "center"
        },
        data: [{
          name: "Heures",
          showInLegend: true,
          legendMarkerType: "square",
          type: "area",
          color: "rgba(40,175,101,0.6)",
          markerSize: 0,
          dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK)?>
        }]
      };
      $("#chartContainer4").CanvasJSChart(options);
    });
      /*---------------------------------------------------------*/
      $(function () {
          var options = {
            title: {
              text: "Evolution De Mon Taux De Présence Pour Ce Mois",
              fontColor: "#2f4f4f",
              fontSize: 20,
              padding: 10,
              margin: 15,
              backgroundColor: "#FFFFE0",
              borderThickness: 1,
              cornerRadius: 5,
              fontWeight: "bold"
            },
            animationEnabled: true,
            axisY: {
              includeZero: false,
              maximum: 100,
   
            },
            axisX: {
              title: "Days"
            },
            toolTip: {
              shared: true,
              content: "<span style='\"'color: {color};'\"'><strong>{name}</strong></span> {y}"
            },

            data: [
            {
              type: "splineArea",
              showInLegend: true,
              name: "Taux de présence",
              dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK)?>
            }
            ]
          };
          $("#chartContainer1").CanvasJSChart(options);
        });
        /*-------------------------------------------------------------------------------------------*/
}
</script>
  </body>
</html>