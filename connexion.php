<?php

include_once('ressources/sql.php');

$titre = "Connexion";
$description = "Se connecter sur {$config['site_titre']}";

include_once("ressources/head.php");
include_once("ressources/header.php");

if (isset($_SESSION['id']))
{
	header( "Location: erreur.php?erreur=403");
}

if (isset($_POST['envoyer']))
	{
		$query = $sql->prepare('SELECT * FROM membres WHERE pseudo = :pseudo AND motdepasse = :motdepasse');
		$query->execute(array(
			'pseudo' => $_POST['pseudo'],
			'motdepasse' => hash('sha512', $_POST['motdepasse'])
			));
			
		$connexion = $query->fetch();
		
		if (!$connexion)
		{
			$message_erreur = "<p><b>Erreur de connexion, mauvais pseudo ou mot de passe</b></p>";
		}
		else
		{
			$query = $sql->prepare('SELECT * FROM membres WHERE pseudo = :pseudo AND desactive = :desactive');
			$query->execute(array(
				'pseudo' => $_POST['pseudo'],
				'desactive' => "1"
			));
			$desactive = $query->fetch();
			if (!$desactive)
			{
				if (isset($_POST['garder_session']))
				{
					$auth_encrypt = sha1($connexion['pseudo']);
					$key_encrypt = hash('sha512', $connexion['email']);
					setcookie('auth', "{$connexion['id']}_$auth_encrypt", time() + 365*24*3600, null, null, false, true);
					setcookie('key', "{$connexion['motdepasse']}_$key_encrypt", time() + 365*24*3600, null, null, false, true);
				}
				session_start();
				$_SESSION['id'] = $connexion['id'];
				$_SESSION['pseudo'] = $connexion['pseudo'];
				$_SESSION['motdepasse'] = $connexion['motdepasse'];
				$_SESSION['jeton'] = uniqid(rand(), true);
				$_SESSION['grade'] = $connexion['grade'];
				header( "Location: /");
			}
			else
			{
				$message_erreur = "<p><b>Ton compte est désactivé</b></p>";
			}
		}
	}
?>
	<div class="connexion">
		<h1>Connexion</h1>
		<?php echo $message_erreur; ?>
		<form method="post" action="connexion.php">
			<input class="champ_texte" name="pseudo" id="pseudo" type="text" placeholder="Pseudo" /><br />
			<input class="champ_texte" name="motdepasse" id="motdepasse" type="password" placeholder="Mot de passe" /><br />
			<label for="garder_session">Se souvenir de moi : </label>
			<input class="champ_case" name="garder_session" id="garder_session" type="checkbox"><br />
			<p><a href="inscription.php">Inscris-toi</a><br />
			<a href="reinitialiser_mdp.php">Mot de passe oublié ?</a></p>
			<input class="champ_bouton" type="submit" name="envoyer" value="Se connecter" />
		</form>
	</div>
<?php
include_once("ressources/footer.php");
?>
