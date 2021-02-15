<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion utilisateur</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-9 text-center">
			<h3 class="h3">Bienvenue sur votre gestionnaire d'heures</h3>
		</div>
	</div>
	<div class="row justify-content-center ">
		<div class="col-md-7 col-lg-6">
			<form accept-charset="UTF-8" role="form" action="../index.php?action=connexion" method="post">
  				<div class="mb-3">
   	 				<label for="mail-user" class="form-label">E-mail de connexion</label>
    				<input type="email" class="form-control " id="mail-user" name="mailUser" aria-describedby="emailHelp">
    				<div id="emailHelp" class="form-text ">Renseignez votre mail utilisé lors de la création de votre compte par le service.</div>
  				</div>
  				<div class="mb-3">
    				<label for="pass-user" class="form-label ">Mot de passe</label>
    				<input type="password" name="password" class="form-control " id="pass-user">
  				</div>
  				<button type="submit" id="submit-login" name="connexion" value="Connexion" class="btn btn-primary">Connexion</button>
  				<div hidden="hidden" style="color:red" class="alert-empty-input">
       				Veuillez remplir tous les champs.
      			</div>
			</form>
		</div>
	</div>	
</div>
</body>
</html>

