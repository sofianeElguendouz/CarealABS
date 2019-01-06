<?php

    function nextDay($day)
    {
      $date = strtotime($day);
      $date = $date / (60 * 60 * 24);
      $date++;
      return (substr(date('c', $date * (60 * 60 * 24)), 0, 10));
    }

    session_start();
    
    $bdd = new PDO('mysql:host=localhost; dbname=project; charset=utf8', 'root','');

    if(!isset($_SESSION['Login']))
      {
        header('Location:Login.php');
      }
    
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

    <!-- Sweet Alert -->
    <link rel="stylesheet" type="text/css" href="../style/dist/sweetalert.css">
    <script src="../style/dist/sweetalert.min.js"></script>

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
        
        <div class="right_col main" role="main">
          <div class="container">
          <br><br>
              <div class="featured-block listEmployes">
                <div class='row'>
                    
                    <form class="" method="POST" action="">
                      <div class="col-md-6 col-md-offset-3 col-sm-4 col-sm-offset-4 col-lg-6 col-lg-offset-3">
                      <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                          <h1>
                            Gestion de Congés et Justifications
                          </h1>
                        </div>
                        <div class="panel-body">
                          <div class="panel-title text-center">
                          <hr>
                            <h3 class="text-info" id="title"> Choix de type des données
                            </h3>
                            <hr>
                          </div>
                          <br>
                          <div class="panel-block">
                            <h5 id="indication">
                              Veuillez préciser s'il s'agit d'un congé ou une justification:
                            </h5>
                            <br>

                            <!-- La première partie -->

                              <div class="form-group" id="formElem1">

                                <div class="form-check">
                                  <span class="input-group-addon alert">
                                    <i class="glyphicon glyphicon-check"></i>
                                    <input type="radio" class="form-check-input" name="radioType" value="just" id="radioType2" checked>
                                      Justification
                                  </span>
                                </div>

                              </div>
                                
                              <div class="form-group" id="formElem2"> 

                                <div class="form-check">
                                  <span class="input-group-addon alert">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                    <input type="radio" class="form-check-input" name="radioType" value="conge" id="radioType1">
                                      Congé
                                </span>
                                </div>
    
                              </div>

                              <!-- /La première partie -->

                              <!-- La deuxième partie -->

                              <div class="form-group text-center" id="formElem3">
                              <h4 for="matricule" class="">Matricule: </h4>
                              <div class="input-group">

                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                              <input id="matricule" name="matricule" type="text" class="form-control" placeholder="9999999" required autofocus autocomplete="false"> 
                                
                              </div>
                              </div>

                              <div class="form-group text-center" id="formElem4">
                              <h4 for="matricule" class="">Date de justification: </h4>
                              <div class="input-group">

                                <span class="input-group-addon"><i class="glyphicon glyphicon-check"></i></span>
                                              <input id="dateJust" name="dateJust" type="date" class="form-control"> 
                                
                              </div>
                              </div>

                              <!-- /La deuxième partie -->

                              <!-- La troisième partie -->

                              <div class="form-group text-center" id="formElem5">
                              <h4 for="matricule" class="">Premier jour de congé: </h4>
                              <div class="input-group">

                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                              <input id="dateCongeDeb" name="dateCongeDeb" type="date" class="form-control"> 
                                
                              </div>
                              </div>

                              <div class="form-group text-center" id="formElem6">
                              <h4 for="matricule" class="">Dernier jour de congé: </h4>
                              <div class="input-group">

                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                              <input id="dateCongeFin" name="dateCongeFin" type="date" class="form-control"> 
                                
                              </div>
                              </div>

                              <!-- /La troisième partie -->

                            <br>
                            <div class="col-md-4 col-md-offset-4">
                              <button id="valider" class="btn btn-block btn-success" type="" onclick="nextStep()">Suivant</button>
                            </div>

                            <div class="col-md-4 col-md-offset-4" id="backBtn">
                              <button id="boutton" class="btn btn-block btn-warning"><a href="UserNiv2JustConge.php">Retour</a> </button>
                            </div>

                          </div>
                        </div>
                        <div class="panel-footer">
                          <p class="text-muted text-center">
                            Les données seront insérées dans la BDD.
                          </p>
                        </div>
                    </div>
                 </div>
                </form>

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
    <script type='text/javascript' src='style/js/jquery.canvasjs.min.js'></script>
    <script type="text/javascript" src="style/js/BeatPicker.js"></script>

    <!-- Les traitements du formulaire -->
    
    <script>
      
      function showElement(id, displayType) {
        var node = document.getElementById(id);
        node.style.display = displayType;
      }

      function hideElement(id) {
        var node = document.getElementById(id);
        node.style.display = 'none';
      }

      function initiate() {
        hideElement('formElem3');
        hideElement('formElem4');
        hideElement('formElem5');
        hideElement('formElem6');
        hideElement('backBtn');
      }

      function nextStep() {

        var title = document.getElementById('title');
        var ind = document.getElementById('indication');
        title.innerHTML = "Saisie des informations";
        ind.innerHTML = "Veuillez insérer les informations nécessaires:";

        hideElement('formElem1');
        hideElement('formElem2');
        showElement('formElem3', 'block');

        var options = document.getElementsByName('radioType');
        for (var i = 0; i < options.length; i++){
          if (options[i].checked) {
            var val = options[i].value;
            break;
          }
        }
        if (val == "just") {
          showElement('formElem4', 'block');
          var elem = document.getElementById('dateJust');
          elem.required = "true";
        }
        else{
          if (val == "conge") {
            showElement('formElem5', 'block');
            showElement('formElem6', 'block');
            var elem = document.getElementById('dateCongeDeb');
            elem.required = "true";
            elem = document.getElementById('dateCongeFin');
            elem.required = "true";
          }
        }

        showElement('backBtn', 'block');

        var btn = document.getElementById('valider');
        btn.type = "submit";
        btn.onclick = "";

      }

      function done() {
        swal({
              title: "Succès",
              text: "L'opération a été effectuée avec succès",
              type: "success",
              allowEscapeKey: "true",
              allowOutsideClick: "true",
              confirmButtonText: "Confirmer",
              confirmButtonColor: "#2AA033"
            });
      }
      
      // Les traitements:

      initiate();

    </script>

  </body>
</html>

<?php

    if (isset($_POST['matricule']) && !empty($_POST['matricule'])) {

      $query = $bdd->prepare("SELECT * FROM salary WHERE Matricule = :mat");
      $query->execute(array(':mat' => $_POST['matricule']));
      if ($query->rowCount() > 0) {
        if (isset($_POST['dateJust']) && !empty($_POST['dateJust'])) {
          
          $query = $bdd->prepare("INSERT INTO justification (Matricule, Date) VALUES (:mat, :date)");
          $query->execute(array(':mat' => $_POST['matricule'], ':date' => $_POST['dateJust']));

        }
        elseif (isset($_POST['dateCongeDeb']) && !empty($_POST['dateCongeDeb']) && isset($_POST['dateCongeFin']) && !empty($_POST['dateCongeFin'])) {
          
          $dayF = $_POST['dateCongeDeb'];
          $dayL = $_POST['dateCongeFin'];
          while (strtotime($dayF) <= strtotime($dayL)) {

            $query = $bdd->prepare("INSERT INTO justification (Matricule, Date) VALUES (:mat, :date)");
            $query->execute(array(':mat' => $_POST['matricule'], ':date' => $dayF));
            $dayF = nextDay($dayF);

          }

        }
?>

<script>
    done();
</script>

<?php
      }
      else{
?>

    <script>

      swal({
              title: "Attention",
              text: "Ce compte n'existe pas",
              type: "error",
              allowEscapeKey: "true",
              allowOutsideClick: "true",
              confirmButtonText: "Confirmer",
              confirmButtonColor: "#DE6842"
            });
      //window.location = "UserNiv2JustConge.php";

    </script>

<?php

      }

    }

?>
  </body>
</html>