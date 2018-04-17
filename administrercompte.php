<?php

include_once('ressources/sql.php');

$titre = "Administrer le compte";

include_once("ressources/head.php");
include_once("ressources/header.php");
if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}

		$query = $sql->prepare('SELECT * FROM membres WHERE id = :id');
		$query->execute(array(
			'id' => $_SESSION['id']
		));
		$infos_membre = $query->fetch();
?>
	<h1>Administrer le compte</h1>
	<?php
	if (empty($_POST))
	{
	?>
	<form method="post" action="administrercompte.php">
		<fieldset>
			<legend>Mot de passe</legend>
			<label for="motdepasse">Nouveau mot de passe : </label>
			<input class="champ_texte" type="password" name="motdepasse" id="motdepasse" /><br />
			<label for="motdepasse2">Confirmation du nouveau mot de passe : </label>
			<input class="champ_texte" type="password" name="motdepasse2" id="motdepasse2" /><br />
			<label for="mdpactuel">Mot de passe actuel : </label>
			<input class="champ_texte" type="password" name="mdpactuel" id="mdpactuel" /><br />			
		</fieldset>
		<br />
		<fieldset>
			<legend>Adresse e-mail</legend>
			<label for="email">Adresse e-mail : </label>
			<input class="champ_texte" type="email" name="email" id="email" value="<?php echo $infos_membre['email']; ?>">
		</fieldset>
			<br />
			<input class="champ_bouton" type="submit" value="Mettre à jour" />
			<br /><br />
		</form>
		<fieldset>
			<legend>Informations</legend>
			<?php
			if ($_SESSION['grade'] == 0)
			{
			?>
				<b style="color: darkred;">Banni</b>
			<?php	
			}
			?>
			<p>Adresse IP utilisée lors de l'inscription : <?php echo $infos_membre['ip_inscription']; ?><br />
			Adresse IP utilisée lors de cette connexion : <?php echo $infos_membre['derniere_ip']; ?><br />
			Ton grade : <?php echo $infos_membre['grade']; ?></p>
		</fieldset>
			
<?php
}
else
{
if (!empty($_POST['email']) && $_POST['email'] != $infos_membre['email'])
{
			$query = $sql->prepare('SELECT * FROM membres WHERE email = :email');
			$query->execute(array(
				'email' => $_POST['email']
				));
			$email = $query->fetch();
			if ($email)
			{
				echo "<p>L'email est déjà prise</p>";
				$erreur++;
				?><script>setTimeout("history.back()", 1500);</script><?php
			}
			
			if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
			{
				echo "<p>L'adresse email n'est pas valide</p>";
				$erreur++;
				?><script>setTimeout("history.back()", 1500);</script><?php
			}
			if ($erreur == 0)
			{
				$query = $sql->prepare('UPDATE membres SET email = :email WHERE id = :id');
				$query->execute(array(
					'email' => htmlspecialchars($_POST['email']),
					'id' => $_SESSION['id']
				));
				echo "<p>Ton adresse email a été modifiée avec succès</p>";
				header("Refresh:1.5; url=/");
			}
}
if (!empty($_POST['email']) && $_POST['email'] == $infos_membre['email'])
{
	echo "<p>Ton adresse email n'a pas été changée</p>";
	header("Refresh:1.5; url=/");
}

if (!empty($_POST['motdepasse']))
{
	$reqSelMDP = $sql->prepare('SELECT motdepasse FROM membres WHERE id = :id');
	$reqSelMDP->execute(array(
			'id' => $_SESSION['id']
		));
	$data = $reqSelMDP->fetch();
	
	if (hash('sha512', $_POST['motdepasse']) != hash('sha512', $_POST['motdepasse2']))
	{
		echo "<p>Les mots de passe ne sont pas les même</p>";
		?><script>setTimeout("history.back()", 1500);</script><?php
	}
	else if (hash('sha512', $_POST['mdpactuel']) != $data['motdepasse'])
	{
		echo "<p>Ton mot de passe actuel ne correspond pas à celui que tu as entré</p>";
		?><script>setTimeout("history.back()", 1500);</script><?php
	}
	else
	{
		$query = $sql->prepare('UPDATE membres SET motdepasse = :motdepasse WHERE id = :id');
		$query->execute(array(
			'motdepasse' => (hash('sha512', $_POST['motdepasse'])),
			'id' => $_SESSION['id']
		));
		echo "<p>Ton mot de passe a été modifié avec succès</p>";
		header("Refresh:1.5; url=/");
	}
}
}
include_once("ressources/footer.php");
?>
