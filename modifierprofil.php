<?php

include_once('ressources/sql.php');

$titre = "Modifier le profil";

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
	<h1>Modifier le profil</h1>

	<?php
	if (empty($_POST))
	{
	?>
		<fieldset>
			<legend>Avatar</legend>
			<a href="modifieravatar.php" title="Modifier ton avatar">Modifier mon avatar</a>
		</fieldset>
		<br />
		<form method="post" action="modifierprofil.php">
		<fieldset>
			<legend>Couleur du thème et du pseudo</legend>
			<label for="couleur_pseudo">Couleur du pseudo : </label>
			<input type="color" name="couleur_pseudo" id="couleur_pseudo" value="<?php echo $infos_membre['couleur_pseudo'] ?>" /><br />
			<label for="couleur_theme">Couleur du thème : </label>
			<input type="color" name="couleur_theme" id="couleur_theme" value="<?php echo $infos_membre['couleur_theme'] ?>" /><br />
			<label for="couleur_theme2">Couleur secondaire du thème : </label>
			<input type="color" name="couleur_theme2" id="couleur_theme2" value="<?php echo $infos_membre['couleur_theme2'] ?>" /><br /><br />
			<button type="button" class="champ_bouton" onclick="document.getElementById('couleur_theme').value='#F4992B'; document.getElementById('couleur_theme2').value='#333D3F';">Réinitialiser</button><br /><br />
			<label for="theme_sombre">Thème sombre (En développement) :</label>
			<input type="checkbox" name="theme_sombre" id="theme_sombre" <?php if ($infos_membre['theme_sombre'] == 1) { echo "checked"; } ?> />
		</fieldset>
		<br />
		<input type="submit" class="champ_bouton" value="Mettre à jour" />
		</form>
	<?php
	}
	else
	{
		if (!empty($_POST['couleur_pseudo']) && $_POST['couleur_pseudo'] != $infos_membre['couleur_pseudo'])
		{
			$query = $sql->prepare('UPDATE membres SET couleur_pseudo = :couleur_pseudo WHERE id = :id');
			$query->execute(array(
				'couleur_pseudo' => htmlspecialchars($_POST['couleur_pseudo']),
				'id' => $_SESSION['id']
			));
			echo "<p>La couleur de ton pseudo a été changée</p>";
			header("Refresh:3; url=/");
		}
		else
		{
			echo "<p>La couleur de ton pseudo n'a pas été changée</p>";
			header("Refresh:3; url=/");
		}
		if (!empty($_POST['couleur_theme']) && $_POST['couleur_theme'] != $infos_membre['couleur_theme'])
		{
			$query = $sql->prepare('UPDATE membres SET couleur_theme = :couleur_theme WHERE id = :id');
			$query->execute(array(
				'couleur_theme' => htmlspecialchars($_POST['couleur_theme']),
				'id' => $_SESSION['id']
			));
			echo "<p>La couleur du thème a été changée</p>";
		}
		else
		{
			echo "<p>La couleur du thème n'a pas été changée</p>";
		}
		if (!empty($_POST['couleur_theme2']) && $_POST['couleur_theme2'] != $infos_membre['couleur_theme2'])
		{
			$query = $sql->prepare('UPDATE membres SET couleur_theme2 = :couleur_theme2 WHERE id = :id');
			$query->execute(array(
				'couleur_theme2' => htmlspecialchars($_POST['couleur_theme2']),
				'id' => $_SESSION['id']
			));
			echo "<p>La couleur secondaire du thème a été changée</p>";
		}
		else
		{
			echo "<p>La couleur secondaire du thème n'a pas été changée</p>";
		}
		if (!empty($_POST['theme_sombre']))
		{
			$query = $sql->prepare('UPDATE membres SET theme_sombre = :theme_sombre WHERE id = :id');
			$query->execute(array(
				'theme_sombre' => "1",
				'id' => $_SESSION['id']
			));
			echo "<p>Le thème sombre est activé</p>";
		}
		else
		{
			$query = $sql->prepare('UPDATE membres SET theme_sombre = :theme_sombre WHERE id = :id');
			$query->execute(array(
				'theme_sombre' => "0",
				'id' => $_SESSION['id']
			));
			echo "<p>Le thème sombre est désactivé</p>";
		}
	}
include_once("ressources/footer.php");
?>
