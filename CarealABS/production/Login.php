<?php  
	session_start();
   if(isset($_POST['inputLogin']) && !empty($_POST['inputLogin']))
   {
    $datab = new PDO('mysql:host=localhost;dbname=project;charset=utf8','root', '');
   $login=$_POST['inputLogin'];
   $password=$_POST['inputPassword'];
   $reponse=$datab->prepare('SELECT * FROM salary WHERE Login=? AND Password =?');
   $reponse->execute([$login,md5($password)]);
   if($reponse->rowCount()>0)
		{
         $_SESSION['Login']=$login;
         $_SESSION['Password']=$password;
         header('Location:mdp.php');
		}
    else {}
    }
	else{
?>
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
		background: url(images/media.jpg) no-repeat center fixed; 
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
	</style>

</head>
<script>var a=1;</script>
<body>

	<div class="container">
		
		<div class="mainbox col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3" id="loginbox">
		
			<div class="panel panel-default">

				<div class="panel-body">

					<div class="image">
						<img class="center-block img-circle" src="images/notreLogo1.png" alt="LogIn Image" height="40%" width="40%">
					</div>

					<form class="form-horizontal form-signin" method="POST" action="mdp.php">

						<div class="form-group">
						<div class="col-md-8 col-md-offset-2">
						<div class="page-header">

							<h2 class="form-signin-heading">Se Connecter</h2>
							
						</div>
						</div>
						</div>

						<div class="form-group">
						<div class="col-md-8 col-md-offset-2">
						<div class="input-group">

							<label for="inputLogin" class="sr-only">Identifiant</label>
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="inputLogin" name="inputLogin" type="text" class="form-control" placeholder="Identifiant" required autofocus> 
							
						</div>
						</div>
						</div>

						<div class="form-group">
						<div class="col-md-8 col-md-offset-2">
						<div class="input-group">

                            <label for="inputPassword" class="sr-only">Mot de Passe</label>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="inputPassword" name="inputPassword" type="password" class="form-control" placeholder="Mot de Passe" required>

                        </div>
                        </div>
                        </div>

                        <div class="form-group">
						<div class="col-md-8 col-md-offset-2">
                        <div class="input-group">

                          	<div class="checkbox">
                            	<label>
                              		<input type="checkbox" value="remember-me"> Se Souvenir De Moi
                            	</label>
                          	</div>

                        </div>
                        </div>
                        </div>

                        <div class="form-group">
                        <div class="col-md-8 col-md-offset-2">

                            <button id="boutton" class="btn btn-lg btn-block" type="submit">Connexion</button>
                            <br>
                            <p class="help-block"><a href="mdpoublie.php" style="color: black;">Mot De Passe Oublié ?</a></p>

                        </div>
                        </div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
  <div class='alert alert-danger erreur'>
   <p>Votre pseudonyme ou mot de passe est incorrecte , veuillez réessayer</p>
  </div>
</body>
	<script src="style/js/jquery.js"></script>
	<script>
	  $('.erreur').css({'width':$(window).width()});
	</script>
	<?php
		echo '
    	<script>
	  	$(".erreur").animate({"top":-10},500);
	  	setTimeout(function(){$(".erreur").animate({"top":-100},500);},2500);
	 	</script>
	 	';}  
		if(isset($_POST['inputLogin']) && !empty($_POST['inputLogin']))
	{?>
	<script>


	  	$('.erreur').animate({'top':-10},500);
	  	setTimeout(function(){$('.erreur').animate({'top':-100},500);},2500);
	  
	 </script>
	<?php }?>
 <script src="style/js/loginjs.js"></script>
</html>