<?php 
  session_start();
  if(!isset($_SESSION['Login']))
      {
        header('Location:Login.php');
      }
  $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root','');
  $gettinginputs = $bdd->prepare("SELECT * FROM salary WHERE Login = ?");
  $gettinginputs->execute(array($_SESSION['Login']));
  $gettinginput = $gettinginputs->fetch();
 if(isset($_SESSION['Login'])) {
   $requser = $bdd->prepare("SELECT * FROM salary WHERE Login = ?");
   $requser->execute(array($_SESSION['Login']));
   $user = $requser->fetch();
   if(isset($_GET['login']) AND !empty($_GET['login']) AND $_GET['login'] != $user['Login']) {
      $newLogin = htmlspecialchars($_GET['login']);
      $insertLogin = $bdd->prepare("UPDATE salary SET Login = ? WHERE Login = ?");
      $insertLogin->execute(array($newLogin, $_SESSION['Login']));
   }
   if(isset($_GET['place']) AND !empty($_GET['place']) AND $_GET['place'] != $user['Adresse']) {
      $newPlace = htmlspecialchars($_GET['place']);
      $insertPlace = $bdd->prepare("UPDATE salary SET Adresse = ? WHERE Login = ?");
      $insertPlace->execute(array($newPlace, $_SESSION['Login']));
   }
   if(isset($_GET['phone']) AND !empty($_GET['phone']) AND $_GET['phone'] != $user['Telephone']) {
    
      $newPhone = htmlspecialchars($_GET['phone']);
      $insertPhone = $bdd->prepare("UPDATE salary SET Telephone = ? WHERE Login = ?");
      $insertPhone->execute(array($newPhone, $_SESSION['Login']));
   }
   if(isset($_GET['password']) AND !empty($_GET['password']) AND $_GET['password'] != $user['Password']) {
      $newPassword = htmlspecialchars($_GET['password']);
      $insertPassword = $bdd->prepare("UPDATE salary SET Password = ?  WHERE Login = ?");
      $insertPassword->execute(array($newPassword, $_SESSION['Login']));
   }
 }
  $bdd = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root','');
  $gettinginputs = $bdd->prepare("SELECT * FROM salary WHERE Login = ?");
  $gettinginputs->execute(array($_SESSION['Login']));
  $gettinginput = $gettinginputs->fetch();
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
    <link rel="stylesheet" href="style/js/jsprofil.js">
    <link rel="stylesheet" href="style/sweetalert.css">
    <link rel="icon" type="image/png" href="images/notreLogo1.png" />
    
    <script language="JavaScript" type="text/javascript">
      function activer(){document.getElementById('email').disabled=false;document.getElementById('phone').disabled=false;document.getElementById('place').disabled=false;document.getElementById('password').disabled=false;document.getElementById('login').disabled=false;}
    </script>
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
                <span>Mr. Directeur</span>
                <?php echo"<h2>".$_SESSION['Nom']."</h2>"?>
              </div>
            </div>
            <!-- /menu profile quick info -->
            <br/>
            <!-- sidebar menu -->
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="UserNiv1InfoGen.php"><i class="fa fa-home"></i>Accueil</a></li>
                  <li><a><i class="fa fa-edit"></i> Gestion Des Absences <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv1Ecole.php">Ecole</a></li>
                      <li><a href="UserNiv1Dep.php">Départements</a></li>
                      <li><a href="UserNiv1Serv.php">Services</a></li>
                      <li><a href="UserNiv1Emp.php">Employés</a></li>
                      <li><a href="UserNiv1Moi.php">Moi</a></li>
                      <?php if ($_SESSION['Service']==19) {
                        echo "<li><a href='UserNiv1JustConge.php'>Gestion congés et justifications</a></li>";
                      } ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-table"></i> Gestion Des Retenus <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv1Ret.php">Gestion De Mes Retenus</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-desktop"></i> Hièrarchie <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv1org.php">Organigramme</a></li>
                      <li><a href="UserNiv1Str.php">Structure Administration</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-windows"></i> Autres Pages <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="UserNiv1Profile.php">Profil</a></li>
                      <li><a href="UserNiv1Info.php">A propos de nous</a></li>
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
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="Lohin.php">
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
                    <li><a href="UserNiv1Profile.php"> Profil</a></li>
                    <li><a href="UserNiv1Aide.php">Aide</a></li>
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
          <div class="ccc">
            <div class="page-title">
              <div class="title_left">
                <h3>Profil Utilisateur</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Aller!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rapport Utilisateur <small>Informations Personnelles</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img class="img-responsive avatar-view" src="images/img.jpg" alt="Avatar" title="Change the avatar">
                        </div>
                      </div>
                      <?php echo"<h3>".$_SESSION['Nom']."</h3>";?>
                      <ul class="list-unstyled user_data">
                        <li><i class="fa fa-map-marker user-profile-icon"></i> Oued Smar Alger Algerie
                        </li>

                        <li>
                          <i class="fa fa-briefcase user-profile-icon"></i><?php echo"<h6> Employé de Niveau : ".$_SESSION['Rang']."</h6>"?>
                        </li>

                        <li class="m-top-xs">
                          <i class="fa fa-external-link user-profile-icon"></i>
                          <a href="http://www.kimlabs.com/profile/" target="_blank">www.esi.dz</a>
                        </li>
                      </ul>

                      <div onclick="javascript:activer();"><a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a></div>
                      <br />
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <div class="profile_title">
                        <div class="col-md-6">
                          <h2>Profil</h2>
                        </div>
                      </div>
                      <!-- start of user-activity-graph -->
                      <div class="custom-form">
                          <form action="UserNiv1Profile.php" method="GET" id="target" class="form1">
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-user input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="name" name="name">
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-barcode input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="matricule" name="matricule">
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-globe input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="deppartement" name="deppartement">
                                
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-map-marker input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="place" name="place">
                                
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-earphone input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="phone" name="phone">
                                
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-envelope input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="email" name="email"> 
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-log-in input-place input-group-addon"></span>
                                <input type="text" class="form-control form-input champ" disabled id="login" name="login">    
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 input-group">
                                <span class="glyphicon glyphicon-eye-close input-place input-group-addon"></span>
                                <input type="password" class="form-control form-input champ" placeholder="Password" disabled id="password" name="password">
                            </div>
                            <!--.................................................................................-->
                            <div class="col-lg-12 col-md-12 text-center">
                                <button class="btn btn-info btn-lg custom-btn bbb valider" id="submit"><span class="glyphicon glyphicon-save" ></span> Save</button>
                            </div>
                          </form>
                      </div>
                      <!-- end of user-activity-graph -->
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
    <script src="style/js/sweetalert.min.js"></script>
    <script src="style/js/Miseenforme.js"></script>
    <script type="text/javascript">
        document.getElementById('name').value = "<?php echo $gettinginput['Nom'].' '.$gettinginput['Prenom']?>";
        document.getElementById('email').value = "<?php echo $gettinginput['Rang'].'_'.$gettinginput['Nom'].'@esi.dz'?>";
        document.getElementById('matricule').value = "<?php echo $gettinginput['Matricule']?>";
        document.getElementById('login').value = "<?php echo $gettinginput['Login']?>";
         document.getElementById('place').value = "<?php echo $gettinginput['Adresse']?>";
        document.getElementById('phone').value = "<?php echo $gettinginput['Telephone']?>";
        document.getElementById('deppartement').value = "<?php echo 'Département : '.$gettinginput['Departement']?>";
        document.getElementById('password').value = "<?php echo $gettinginput['Password']?>";
    </script>
</body>
</html>
