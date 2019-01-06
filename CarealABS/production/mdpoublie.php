<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
  <title>
    Acceuil - Log In
  </title>
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style/css/style.css">
  <link rel="icon" type="image/png" href="images/notreLogo1.png" />
  <style type="text/css">

  html { 
    background: url(images/blue1.jpg) no-repeat center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }
  </style>
</head>
<body>
<?php

    session_start();

    $bdd = new PDO('mysql:dbname=project;localhost=localhost','root','');
    $possible=0;$possible1=0;
    if(isset($_POST['Login']) and !empty($_POST['Login'])){
        //recup L'id
        $query = $bdd->prepare('SELECT Question,Answer FROM salary WHERE Login = :id');
        $query->execute(array(':id'=>$_POST['Login']));
        $mdp=$query->fetch();
        $i = $mdp['Question'];$possible=$i;
        switch ($i){
          case 1: $str = "Le nom de votre père";
          break;
          case 2: $str = "Le nom de votre premier primaire";
          break;
          case 3: $str = "Votre animal préféré";
          break;
        }
        $_SESSION['Login'] = $_POST['Login'];
    }
    if (isset($_POST['reponse']) && !empty($_POST['reponse'])) {
        $query = $bdd->prepare('SELECT Answer FROM salary WHERE Login = :id');
        $query->execute(array(':id'=>$_SESSION['Login']));
        $mdp=$query->fetch();
        $i = $mdp['Answer'];
        if (strcmp($i, $_POST['reponse']) == 0) {
          $query = $bdd->prepare('UPDATE salary SET Password = Matricule WHERE Login = :id');
          $query->execute(array(':id'=>$_SESSION['Login']));
          $possible1=1;
        }
        else{$possible1=2;}
        //header("Location: Login.php");
    }
?>






<div class="container">
    <div class="mainbox col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3" id="loginbox">
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal form-signin form" method="POST" action="">
            <div class="form-group">
              <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h2 class="form-signin-heading">Reinitialisation Du Mot De Passe</h2>
                </div>
              </div>
            </div>
            <!--..................................................................................-->
            <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
            <div class="input-group">
                            <label for="inputLogin" class="sr-only">Login</label>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="inputLogin" name="Login" type="text" class="form-control" placeholder="Identifiant" required autofocus> 
            </div>
            </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <button id="boutton" class="btn btn-lg btn-block" type="submit">Suivant</button>
                </div>
            </div>
            </form>
          <!--..................................................................................-->
          <form class="form-horizontal form-signin form1" method="POST" action="">
            <div class="form-group">
              <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h2 class="form-signin-heading"><?php if(isset($str) and !empty($str))echo $str;?></h2>
                </div>
              </div>
            </div>
            <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
            <div class="input-group">
                            <label for="inputPassword" class="sr-only">Réponse</label>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="inputPassword" name="reponse" type="password" class="form-control" placeholder="Réponse" required>
            </div>
            </div>
            </div> 
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <button id="boutton" class="btn btn-lg btn-block" type="submit">Valider</button>
                    <a href="Login.php"><h3 class="text-center" style="color: #33691e">Annuler</h3></a>
                </div>
            </div>
          </form>
          <!--..................................................................................-->
          <form class="form-horizontal form-signin msg" method="POST" action="Login.php">
            <div class="form-group">
              <div class="col-md-8 col-md-offset-2">
                <div class="page-header text-center">
                    <div><h2>C'est Votre Première Connexion</h2></div>
                    <div><h3>Vous N'avez Pas Encore Changé Votre M-D-P</h3></div>
                    <div><h3>Il Est Identique à Votre Matricule</h3></div>
                </div>
              </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <a href="Login.php"><h3 class="text-center" style="color: #33691e">Annuler</h3></a>
                </div>
            </div>
          </form>
          <!--..................................................................................-->
          <form class="form-horizontal form-signin msg1" method="POST" action="Login.php">
            <div class="form-group">
              <div class="col-md-8 col-md-offset-2">
                <div class="page-header text-center">
                    <div><h2>Votre Mot De Passe a été Réinitialisé à Votre Matricule</h2></div><br>
                    <div><h4>Veuillez Vous Connecter à Votre Compte et Changer Votre MDP Pour Plus De Sécurité</h4></div>
                </div>
              </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <a href="Login.php"><h3 class="text-center" style="color: #33691e">Page De Connexion</h3></a>
                </div>
            </div>
          </form>
          <!--..................................................................................-->
          <form class="form-horizontal form-signin msg2" method="POST" action="Login.php">
            <div class="form-group">
              <div class="col-md-8 col-md-offset-2">
                <div class="page-header text-center">
                    <div><h2>Votre Réponse Est Erronée</h2></div>
                    <div><h3>Veuillez Donner La Bonne Réponse, Ou Contactez L'administrateur Pour Plus D'informations</h3></div>
                </div>
              </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <a href="Login.php"><h3 class="text-center" style="color: #33691e">Page De Connexion</h3></a>
                </div>
            </div>
          </form>
          <!--..................................................................................-->
        </div>
      </div>
    </div>
  </div>
</body>
<script src="style/js/jquery.js"></script>
<script type="text/javascript">
$('.msg').hide();$('.msg1').hide();$('.msg2').hide();
$('.form1').hide();
<?php if(isset($str) and !empty($str)){?>
  $('.form1').show();
  $('.form').hide();
<?php } 
    if(isset($_POST['Login']) and !empty($_POST['Login']) && $possible==0){
?>
  $('.form').hide();
  $('.msg').show();
<?php } ?>
//document.getElementById('ttt').hide();
<?php if ($possible1==1) {echo "$('.form').hide();$('.form1').hide();$('.msg').hide();$('.msg1').show();";}
      elseif($possible1==2) {echo "$('.form').hide();$('.form1').hide();$('.msg').hide();$('.msg2').show();";}
 ?>
</script>
</html>