<?php

include_once('ressources/sql.php');

$titre = "Modifier l'avatar";

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

	if (empty($_FILES['avatar']['size']))
	{
	?>
	<form method="post" action="modifieravatar.php" enctype="multipart/form-data">
		<fieldset>
			<legend>Avatar</legend>
			<input type="file" class="champ_bouton" name="avatar" id="avatar" /><br />
			<span>Poids max : 120 Ko</span><br />
			<input type="submit" class="champ_bouton" value="Changer l'avatar" />
		</fieldset>
	</form>
	<?php
	}
	else
	{
		
		$poids_max = 122880;
		/*$largeur_max = 120;
		$longueur_max = 120;*/
		
		$extensions = array("jpg", "jpeg", "gif", "png");
		
		if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0)
		{
				if ($_FILES['avatar']['size'] < $poids_max)
				{
					$avatar_tailles = getimagesize($_FILES['avatar']['tmp_name']);
					if (@getimagesize($_FILES['avatar']['tmp_name']))
					{
						/*$avatar_tailles = getimagesize($_FILE['avatar']['tmp_name']);
						if ($avatar_tailles[0] > $largeur_max || $avatar_tailles[1] > $longueur_max)
						{*/
							$infos_avatar = pathinfo($_FILES['avatar']['name']);
							$extension_avatar = strtolower( $infos_avatar['extension']);
							if (in_array($extension_avatar, $extensions))
							{
								$time = time();
								$nouveau_nom = "ressources/avatars/avatar_{$infos_membre['id']}_$time.$extension_avatar";
								move_uploaded_file($_FILES['avatar']['tmp_name'],$nouveau_nom);
								$query = $sql->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
								$query->execute(array(
									'avatar' => "/$nouveau_nom",
									'id' => $_SESSION['id']
								));
								echo "<p>Ton avatar a été téléversé et appliqué à ton compte avec succès</p>";
								header("Refresh:1.5; url=/");
							}
							else
							{
								echo "<p>L'extension de ton avatar est incorrecte</p>";
								?><script>setTimeout("history.back()", 1500);</script><?php
							}
						/*}
						else
						{
							echo "<p>Ton avatar est trop grand</p>";
						}*/
					}
					else
					{
						echo "<p>Ton avatar n'est pas une image valide</p>";
						?><script>setTimeout("history.back()", 1500);</script><?php
					}
				}
				else
				{
					echo "<p>Ton avatar est trop lourd</p>";
					?><script>setTimeout("history.back()", 1500);</script><?php
				}
		}
	}
	include_once("ressources/footer.php");
	?>
