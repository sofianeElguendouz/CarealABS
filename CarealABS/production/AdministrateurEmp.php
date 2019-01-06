<?php 
session_start();
$bd=new PDO('mysql:dbname=project;localhost=localhost','root','');
$query=$bd->query('SELECT * FROM salary');
$employes=$query->fetchAll(PDO::FETCH_OBJ);
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
    <link href="style/css/confirmation.css" rel="stylesheet">
    <link rel="stylesheet" href="style/sweetalert.css">
    <link rel="stylesheet" href="style/Style.css"/>
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
                <span>Admin</span>
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
      <div class="container">
        <div class="featured-block text-center">
        <div class="text-center"><button class="btn btn-success ajouter" id ="myBtn"><h1>Ajouter Employé</h1></button></div><br><br>

          <div class="input-group recherche">
                    <input type="text" class="form-control barre-recherche" placeholder="Entrez Le Nom Que Vous Cherchez...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary button-recherche">Rechercher</button>
                        <button class="btn btn-danger button-retour">Retour</button>
                    </span>
          </div>
                <table class="table" style="width:700px;margin:auto;margin-top:50px;position:relative;">
                <thead>
                  <tr class="debut" style="color: #AE5553">
                    <td><h2>Matricule</h2></td>
                    <td><h2>Nom</h2></td>
                    <td><h2>Prenom</h2></td>
                  </tr>
                </thead>
                <tbody>
                 <?php foreach($employes as $v)
                 {
                  echo '<tr class="employee">
                        <td>'.$v->Matricule.'</td>
                        <td class="nom">'.$v->Nom.'</td>
                        <td>'.$v->Prenom.'</td>
                        <td> 
                         <button class="btn btn-primary"><a style="color:#fff;" href="ajoutModifEmp.php?p=modifier&id='.$v->ID.'">Modifier</a></button>
                         <button class="btn btn-danger supprimer" title="'.$v->ID.'" href="'.$v->Nom.'">Supprimer</button>
                        </td>
                        </tr>';
                 }
                 ?>
               </tbody>
              </table>
              <div class="form-container">

      <form method = "POST" class = "form" action= "" >
        <div><label>Nom : </label><input type = "text"  placeholder = "Nom de l'utilisateur" class="nom"name ="Nom"/></div>
        <div><label>Prenom : </label><input type = "text"  placeholder = "Prenom de l'utilisateur" class="prenom" name ="Prenom"/></div>
        <div><label>Montant : </label><input type = "text"  placeholder = "Montant de l'utilisateur" class="montant" name ="Montant"/></div>
        <div><label>Matricule : </label><input type = "text"  placeholder = "Matricule de l'utilisateur" class="matricule" name ="Matricule"/></div>
        <div><label>Login : </label><input type = "text"  class="login" placeholder = "Login de l'utilisateur" name ="Login"/></div>
        <div><label>Password : </label><input type = "text"  class="password" placeholder = "Mot de pass de l'utilisiateur" name = "Password"/></div>
        <div><label>Rang : </label><input type = "text" class="rang" placeholder = "Rang de l'utilisiateur" name = "Rang"/></div>
        <div><label>Service : </label><input type = "text" class="service" placeholder = "Service de l'utilisiateur" name = "Service"/></div>
        <div><label>Departement :</label><input type = "text" class="departement" placeholder = "Département de l'utilisateur" name = "Departement"/></div>
        <div><label>Adresse :</label><input type = "text" class="adresse" placeholder = "Adresse de l'utilisateur" name = "Adresse"/></div>
          <div><label>Telephone :</label><input type = "text" class="telephone" placeholder = "Telephone de l'utilisateur" name = "Telephone"/></div>
                  <div><label>Question :</label><input type = "text" class="question" placeholder = "Question de l'utilisateur" name = "Question"/></div>
                          <div><label>Answer :</label><input type = "text" class="answer" placeholder = "Reponse de l'utilisateur" name = "Answer"/></div>
        <input type = "submit" class = "btn btn-success valider"   value = "Valider" />
      </form>

    </div>
          </div>
        </div>
      </div>

        <!-- /page content -->

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
    <div class="black-opacity">
    </div>
    <div class="confirmation">
      <div style="text-align:center">
      <button class="btn btn-danger confirm-supress">Supprimer</button>
      <button class="btn btn-primary annul-supress" >Annuler</button> 
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
    <script src="style/js/recherche.js"></script>
    <script src="style/js/sweetalert.min.js"></script>
    <script src="style/js/Miseenforme2.js"></script>
    <script src="style/js/ajoutEmp.js"></script>
  </body>
</html>