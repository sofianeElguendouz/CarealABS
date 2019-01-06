<?php
    if(!isset($_SESSION['Login']))
      {
        header('Location:login.php');
      }
    require 'Acceuil.php';
    /*----------------------------------*/
    $datetime = date("Y-m-d");
    $date=explode('-',$datetime);
    $mois=$date[0].'-'.$date[1];
    $DP=Dep();
    $DDPP=Serv($DP);
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
                <span>Administrateur</span>
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
                  <li><a href="AdministrateurAccueil.php"><i class="fa fa-home"></i> Accueil</a></li>
                  <li><a href="AdministrateurEmp.php"><i class="fa fa-edit"></i> Gestion Des Comptes Employés<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    </ul>
                  </li>
                  <li><a href="AdministrateurServ.php"><i class="fa fa-edit"></i> Gestion Des Services<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    </ul>
                  </li>
                  <li><a href="AdministrateurDep.php"><i class="fa fa-edit"></i> Gestion Des Départments<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bug"></i> Autres Pages <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="AdministrateurProfile.php">Profil</a></li>
                      <li><a href="AdministrateurInfo.php">A propos de nous</a></li>
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
                    <li><a href="AdministrateurProfile.php"> Profil</a></li>
                    <li><a href="AdministrateurAide.php">Aide</a></li>
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
          <div class="row tile_count">
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Nombre Employés Total</span>
              <div class="count green"><?php echo totalNumberEmployes(); ?></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Nombre Services</span>
              <div class="count"><?php echo totalNumberServices(); ?></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Nombre Départements</span>
              <div class="count green"><?php echo totalNumberDepartement(); ?></div>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>Nombre Hommes</span>
              <div class="count">100</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i>Nombre Femmes</span>
              <div class="count green">88</div>
            </div>
          <!-- /top tiles -->
          </div>
        <!-- /page content -->
         <div class="right_col main lol" role="main">
          <div class="container">
            <div class="featured-block ">
                  <div id='testdiv' class="right_col" role="main">
                      <div id="depserv" style="width: 100%; height: 100%;display: inline-block;"></div>
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
      </div>
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
    <script type="text/javascript" src="style/js/jspdf.min.js"></script>
    <script type="text/javascript" src="style/js/html2canvas.js"></script>
	  <script type="text/javascript">
    window.onload = function () {
     Highcharts.chart('depserv', {
              chart: {
                  type: 'pie'
              },
              title: {
                  text: '<Strong style="font-size:20px;font-weight: bold;">Les Différents Départements et Services</strong>',
                  fontColor: "#2f4f4f",
                  fontSize: 20,
                  padding: 10,
                  margin: 15,
                  backgroundColor: "#FFFFE0",
                  borderThickness: 1,
                  cornerRadius: 5,
                  fontWeight: "bold"
              },
              subtitle: {
                  text: 'Cliquez Sur Le Cercle Pour Le Developer'
              },
              plotOptions: {
                  series: {
                      dataLabels: {
                          enabled: true,
                          //format: '{point.name}: {point.y} Employés du département'
                      }
                  }
              },
              tooltip: {
                  headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                  pointFormat: '<span style="color:{point.color}">{point.name} :<b>{point.y} Employés du total</b></span><br/>'
              },
              series: [{
                  name: 'Départements',
                  colorByPoint: true,
                  data: <?php echo json_encode($DP, JSON_NUMERIC_CHECK)?>
              }],
              drilldown: {
                  series: <?php echo json_encode($DDPP, JSON_NUMERIC_CHECK)?>
              }
          }); 
     chart.render();
    }
    </script>
  </body>
</html>