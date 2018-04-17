<?php

include_once('ressources/sql.php');

$titre = "Inscription";
$description = "S'inscrire sur {$config['site_titre']} vous permet de rejoindre une communauté";

$siteKey = "6Le-4zIUAAAAAL8dDYAJviVJN2QbXvhBGRy_IEFo";
$secret ="6Le-4zIUAAAAADi6T_dfkF7BJB62mwVklDDLkXn8";

include_once("ressources/head.php");
include_once("ressources/header.php");
include_once("ressources/recaptchalib.php");

	if (isset($_SESSION['id']))
	{
		header("Location: erreur.php?erreur=403");	
	}
	
	if (isset($_POST['envoyer']))
	{
		$erreur = 0;
		if (empty($_POST['pseudo'] || $_POST['email'] || $_POST['motdepasse'] || $_POST['motdepasse2']))
		{
			$message_erreur = "<p>Tu n'as pas rempli tous les champs</p>";
		}
		else
		{
			if (strlen($_POST['pseudo']) < 3)
			{
				$message_erreur = "<p>Ton pseudo est trop petit</p>";
				$erreur++;
			}
			if (strlen($_POST['pseudo']) > 25)
			{
				$message_erreur = "<p>Ton pseudo est trop grand</p>";
				$erreur++;
			}
			if (hash('sha512', $_POST['motdepasse']) != hash('sha512', $_POST['motdepasse2']))
			{
				$message_erreur = "<p>Les mots de passe ne sont pas les mêmes</p>";
				$erreur++;
			}
			
			$query = $sql->prepare('SELECT * FROM membres WHERE pseudo = :pseudo');
			$query->execute(array(
				'pseudo' => $_POST['pseudo']
				));
			$pseudo = $query->fetch();
			if ($pseudo)
			{
				$message_erreur = "<p>Le pseudo est déjà pris</p>";
				$erreur++;
			}
			
			$query = $sql->prepare('SELECT * FROM membres WHERE email = :email');
			$query->execute(array(
				'email' => $_POST['email']
				));
			$email = $query->fetch();
			if ($email)
			{
				$message_erreur = "<p>L'email est déjà prise</p>";
				$erreur++;
			}
			
			if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
			{
				$message_erreur = "<p>L'adresse email n'est pas valide</p>";
				$erreur++;
			}
			
			$reCaptcha = new ReCaptcha($secret);
			if(isset($_POST["g-recaptcha-response"]))
			{
				$resp = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
			 );
    		if ($resp != null && $resp->success)
    		{
    			//Captcha Passé
    		}
    			else 
			{
				$message_erreur = "<p>Captcha incorrect</p>";
				$erreur++;
			}
			}
			
			if ($erreur == 0)
			{
			$query = $sql->prepare('INSERT INTO membres(pseudo, email, ip_inscription, motdepasse, date_inscription, avatar, grade, couleur_pseudo, couleur_theme, couleur_theme2) VALUES(:pseudo, :email, :ip_inscription, :motdepasse, :date_inscription, :avatar, :grade, :couleur_pseudo, :couleur_theme, :couleur_theme2)');
			$query->execute(array(
				'pseudo' => htmlspecialchars($_POST['pseudo']),
				'email' => htmlspecialchars($_POST['email']),
				'ip_inscription' => $_SERVER['REMOTE_ADDR'],
				'motdepasse' => hash('sha512', $_POST['motdepasse']),
				'date_inscription' => time(),
				'avatar' => "/ressources/avatars/defaut.jpg",
				'grade' => "4",
				'couleur_pseudo' => "black",
				'couleur_theme' => "#F4992B",
				'couleur_theme2' => "#333D3F"
				));
			
			$_SESSION['pseudo'] = $_POST['pseudo'];
            $_SESSION['id'] = $sql->lastInsertId();
            $_SESSION['jeton'] = uniqid(rand(), true);
            $_SESSION['grade'] = "4";
            
            $valide = true;
            
            echo '<p>Félicitations <b>' . $_POST['pseudo'] . '</b>, ton inscription est terminée</p>';
            header("Refresh:1.5; url=moncompte.php");
			}
		}
	}
	if ($valide != true)
	{
?>
		<div class="inscription">	
			<h1>Inscription</h1>
			<?php echo $message_erreur; ?>
			<form method="post" action="inscription.php">
				<input class="champ_texte" name="pseudo" id="pseudo" type="text" placeholder="Pseudo" value="<?php echo $_POST['pseudo'] ?>" /><br />
				<input class="champ_texte" name="email" id="email" type="email" placeholder="Adresse e-mail" value="<?php echo $_POST['email'] ?>"/><br />
				<input class="champ_texte" name="motdepasse" id="motdepasse" type="password" placeholder="Mot de passe" /><br />
				<input class="champ_texte" name="motdepasse2" id="motdepasse2" type="password" placeholder="Confirme le mot de passe" /><br />
				<br />
				<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>" ></div><br />
				<input class="champ_bouton" type="submit" name="envoyer" value="S'inscrire" />
			</form>
		</div>
<?php
	}
include_once("ressources/footer.php");
?>
