<?php
include_once('ressources/sql.php');

switch($_GET['action'])
	{
		case "nouveau_sujet":
			$titre = "Publier un nouveau sujet";
		break;
		
		case "repondre":
			$titre = "Répondre à un sujet";
		break;
		
		default:
			$titre = "Publier";
	}

include_once("ressources/head.php");
include_once("ressources/header.php");

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}

if (!empty($_GET['action']))
{
	echo "<h1>Forum de {$config['site_titre']}</h1>";
	switch($_GET['action'])
	{
		case "nouveau_sujet":
			if (isset($_POST['poster']))
			{
				$erreur = 0;
				
				if (empty($_POST['titre'] || $_POST['contenu']))
				{
					$message_erreur = "<p>Tu n'as pas rempli tous les champs</p>";
					$erreur++;
				}
				
				if ($erreur == 0)
				{
					$query = $sql->prepare('INSERT INTO sujet_forum(id_sscat, auteur, date_creation, titre, contenu) VALUES(:id_sscat, :auteur, :date_creation, :titre, :contenu)');
					$query->execute(array(
						'id_sscat' => htmlspecialchars($_GET['sscat']),
						'auteur' => $_SESSION['id'],
						'date_creation' => time(),
						'titre' => htmlspecialchars($_POST['titre']),
						'contenu' => htmlspecialchars($_POST['contenu'])
					));
					
					$valide = true;
					$message_erreur = "<p>Ton sujet a bien été posté</p>";
				}
			}
			?>
			<h2>Publier un nouveau sujet</h2>
			<?php echo $message_erreur; 
			if ($valide != true)
			{?>
			<form method="post" action="publier.php?action=nouveau_sujet&sscat=1">
				<input class="champ_texte" type="text" name="titre" placeholder="Titre" /><br />
				<textarea class="champ_texte" name="contenu" placeholder="Contenu"></textarea><br />
				<input class="champ_bouton" type="submit" name="poster" />
			</form>
			<?php
			}
		break;
		
		case "repondre":
			?>
			<h2>Répondre à un sujet</h2>
			<?php
		break;
		
		default:
			header("Location: erreur.php?erreur=404");
	}
}
else
{
	header("Location: erreur.php?erreur=404");
}

include_once('ressources/footer.php');

?>