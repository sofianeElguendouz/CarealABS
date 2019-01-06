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
    background: url(images/blue2.jpg) no-repeat center fixed; 
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
    if ((isset($_POST['inputLogin']) and !empty($_POST['inputLogin']))){
        $_SESSION['inputLogin'] = $_POST['inputLogin'];
        $_SESSION['inputPassword'] = $_POST['inputPassword'];
        }
    $login=$_SESSION['inputLogin'];
    $password=$_SESSION['inputPassword'];
        $reponse=$bdd->prepare('SELECT * FROM salary WHERE Login=? AND Password =?');
        $reponse->execute([$login,$password]);
        if($reponse->rowCount()>0)
        {
    $query = $bdd->prepare('SELECT Question,Answer FROM salary WHERE Login = :id');
    $query->execute(array(':id'=>$_SESSION['inputLogin']));
    $mdp = $query->fetch();
    $bool1 = is_null($mdp['Question']);
    $bool2 = is_null($mdp['Answer']);
    if($bool1 && $bool2 )
    {
?>

    
<div class="container">
    <div class="mainbox col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3" id="loginbox">
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal form-signin form" method="POST" action="">
            <div class="form-group">
              <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h2 class="form-signin-heading">Sécurité Du Compte</h2>
                    <br><br>
                    <h3>Choix De Votre Question De Sécurité</h3>
                </div>
              </div>
            </div>
            <!--..................................................................................-->
            <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
            <div class="input-group">
                <select name="choix" class="selection form-control">
                    <option value="1">Le nom de votre père ?</option> 
                    <option value="2">Le nom de votre primaire ?</option> 
                    <option value="3">Votre animal préféré ?</option> 
                </select>
            </div>
            </div>
            </div>

            <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
            <div class="input-group">
                            <label for="inputLogin" class="sr-only">Réponse</label>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="inputLogin" name="Reponse" type="text" class="form-control" placeholder="Réponse" required autofocus> 
            </div>
            </div>
            </div>

            <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
            <div class="input-group">
                            <p>Cette procédure assura la réinitialisation de votre mot de passe en cas de besoin.</p>
            </div>
            </div>
            </div> 

            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <button id="boutton" class="btn btn-lg btn-block" type="submit">Valider</button>
                </div>
            </div>
            </form>
<!--..................................................................................-->
        </div>
      </div>
    </div>
  </div>


<script src="jquery.js"></script>
<script src="sweetalert.min.js"></script>
</body>

</html> 


<?php

if ( (isset($_POST) and !empty($_POST))){
    if ((isset($_POST['Reponse']) and !empty($_POST['Reponse']))){
        if ((isset($_POST['choix']) and !empty($_POST['choix']))) 
            
        {
            $req = $bdd->prepare('UPDATE salary SET Question=:question,Answer=:answer WHERE Login =:id');
            $req->execute(array(':question'=>$_POST['choix'],':answer'=>$_POST['Reponse'],':id'=>$_SESSION['inputLogin']));
            header("Location: Affectation.php");
        }
        
        }
    }

}
else
{
    header("Location: Affectation.php");
}
}

else
{
    header("Location: Login.php");
}


?>



