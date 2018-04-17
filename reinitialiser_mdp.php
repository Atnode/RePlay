<?php

include_once('ressources/sql.php');

$titre = "Réinitialiser son mot de passe";
$description = "Réinitialiser son mot de passe sur {$config['site_titre']}";

include_once("ressources/head.php");
include_once("ressources/header.php");

if (isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}

if (isset($_GET['action']))
{
	if ($_GET['action'] == "code" && isset ($_SESSION['email']))
	{
		if (isset($_POST['envoyer_code']))
		{
			$erreur = NULL;
			
			if (empty($_POST['code']))
			{
				$message_erreur = "<p><b>Tu n'as pas entré ton code</b></p>";
				$erreur++;
			}
			
			if ($erreur == 0)
			{
				$query = $sql->prepare('SELECT * FROM reinitialisation_mdp WHERE email = :email AND code = :code');
				$query->execute(array(
					'email' => htmlspecialchars($_SESSION['email']),
					'code' => htmlspecialchars($_POST['code'])
				));
				$reinitialisation_code = $query->fetch();
				
				if (!$reinitialisation_code)
				{
					$message_erreur = "<p><b>Le code n'est pas bon</b></p>";
				}
				else
				{
					$query = $sql->prepare('DELETE FROM reinitialisation_mdp WHERE email = :email');
					$query->execute(array(
						'email' => htmlspecialchars($_SESSION['email'])
					));
					$_SESSION['code_bon'] = true;
					header("Location: reinitialiser_mdp.php?action=changer");
				}
			}
		}
		?>
		<div class="connexion">
			<h1>Mot de passe oublié</h1>
			<p><b><?php echo $_SESSION['mail']; ?></b></p>
			<?php echo $message_erreur; ?>
			<form method="post" action="reinitialiser_mdp.php?action=code">
				<input class="champ_texte" name="code" id="code" type="text" maxlength="9" placeholder="Ton code de sécurité" /><br />
				<input class="champ_bouton" type="submit" name="envoyer_code" value="Changer mon mot de passe" />
			</form>
		</div>
		<?php
	}
	
	if ($_GET['action'] == "changer" && isset ($_SESSION['code_bon']) && $_SESSION['code_bon'] == true)
	{
		
	if (isset($_POST['changer_mdp']))
	{
		$erreur = NULL;
		
		if (empty($_POST['motdepasse'] ||$_POST['motdepasse2']))
		{
				$message_erreur = "<p><b>Tu n'as pas rempli tous les champs</b></p>";
				$erreur++;			
		}
		
		if (hash('sha512', $_POST['motdepasse']) != hash('sha512', $_POST['motdepasse2']))
			{
				$message_erreur = "<p><b>Les mots de passe ne sont pas les mêmes</b></p>";
				$erreur++;
			}
			
		if ($erreur == 0)
		{
			$query = $sql->prepare('UPDATE membres SET motdepasse = :motdepasse WHERE email = :email');
			$query->execute(array(
				'motdepasse' => hash('sha512', $_POST['motdepasse']),
				'email' => $_SESSION['email']
			));
			$message_erreur = "<p><b>Ton mot de passe a été changé avec succès</b></p>";
			$valide = true;
			session_destroy();
			header("Refresh:1.5; url=/");
		}
	}
		?>
		<div class="connexion">
			<h1>Mot de passe oublié</h1>
			<?php echo $message_erreur;
			if ($valide != true)
			{?>
			<form method="post" action="reinitialiser_mdp.php?action=changer">
				<input class="champ_texte" name="motdepasse" id="motdepasse" type="password" placeholder="Mot de passe" /><br />
				<input class="champ_texte" name="motdepasse2" id="motdepasse2" type="password" placeholder="Confirme le mot de passe" /><br />
				<input class="champ_bouton" type="submit" name="changer_mdp" value="Changer mon mot de passe" />
			</form>
			<?php
			}
			?>
		</div>
		<?php
	}
}

if (isset($_POST['envoyer']))
	{
		$erreur = 0;
		if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
		{
			$message_erreur = "<p><b>L'adresse email n'est pas valide</b></p>";
			$erreur++;
		}
		
		$query = $sql->prepare('SELECT * FROM membres WHERE email = :email');
		$query->execute(array(
			'email' => $_POST['email']
			));
		$email = $query->fetch();
		if (!$email)
		{
			$message_erreur = "<p><b>Aucun membre ne possède cette adresse e-mail</b></p>";
			$erreur++;
		}
		
		if ($erreur == 0)
		{
			$_SESSION['email'] = $_POST['email'];
			$code = NULL;
			
			for($boucle = 0; $boucle < 9; $boucle++)
			{
				$code .= mt_rand(0, 9);
			}
			
			$query = $sql->prepare('SELECT * FROM reinitialisation_mdp WHERE email = :email');
			$query->execute(array(
				'email' => htmlspecialchars($_SESSION['email']),
			));
			$mail_existant = $query->rowCount();
			
			if ($mail_existant == 1)
			{
				$query = $sql->prepare('UPDATE reinitialisation_mdp SET code = :code WHERE email = :email');
				$query->execute(array(
					'code' => $code,
					'email' => htmlspecialchars($_SESSION['email'])
				));
			}
			else
			{
				$query = $sql->prepare('INSERT INTO reinitialisation_mdp(email, code) VALUES(:email, :code)');
				$query->execute(array(
					'email' => htmlspecialchars($_SESSION['email']),
					'code' => $code
				));
			}
			
			$titre = "Re:Play - Mot de passe";
			$header="MIME-Version: 1.0\r\n";
			$header.='From:"Support Re:Play"<nepasrepondre@replay.ga>'."\n";
			$header.='Content-Type:text/html; charset="uft-8"'."\n";
			$header.='Content-Transfer-Encoding: 8bit';
			
			$message='
			<html>
			<body style="margin: 0;"><h1 style="color: #333D3F;font-size: 25px;font-family: &quot;Arial&quot;;background-image: linear-gradient(to right, #F4992B, #FFF);padding: 15px;">Re:Play - Réinitialisation du mot de passe</h1>
			<h2 style="color: #000;font-family: &quot;Source Sans Pro&quot;,sans-serif;margin-left: 15px;">Ton code pour réinitialiser ton mot de passe est le suivant, si tu n\'as pas demandé à réinitialiser ton mot de passe, tu as juste à ignorer ce mail</h2>
			<a href="http://beta.replay.ga/reinitialiser_mdp.php?action=code">Clique ici pour réinitialiser ton mot de passe</a>
			<h3 style="color: #000;font-size: 25px;font-family: &quot;Arial&quot;;margin-left: 20px;">'. $code .'</h3>
			</body>
			</html>
			';
			
			mail("{$_SESSION['email']}", $titre, $message, $header);
			$message_erreur = "<p><b>Un e-mail vient d'être envoyé à l'adresse que tu as indiqué. Si tu ne le vois pas, il peut figurer dans le dossier des spams ou des courriers indésirables</b></p>";
			$valide = true;
		}
	}
	if (empty($_GET))
	{
?>
	<div class="connexion">
		<h1>Mot de passe oublié</h1>
		<?php echo $message_erreur;
		if ($valide != true)
		{?>
		<form method="post" action="reinitialiser_mdp.php">
			<input class="champ_texte" name="email" id="email" type="email" placeholder="Ton adresse e-mail" /><br />
			<input class="champ_bouton" type="submit" name="envoyer" value="Réinitialiser" />
		</form>
		<?php
		}
		?>
	</div>
<?php
	}
include_once("ressources/footer.php");
?>
