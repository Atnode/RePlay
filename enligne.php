<?php

include_once('ressources/sql.php');

$titre = "Qui est en ligne";

include_once("ressources/head.php");
include_once("ressources/header.php");

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}

?>
	<h1>Qui est en ligne</h1>
<?php
		$query = $sql->prepare('SELECT id_membre, temps_connexion, ip_membre, useragent_membre, page_membre, id, pseudo, couleur_pseudo FROM enligne LEFT JOIN membres ON id_membre = id WHERE temps_connexion > :temps_limite');
		$query->execute(array(
			'temps_limite' => $temps_limite
		));
		while($page_enligne = $query->fetch())
		{
			if ($page_enligne['id_membre'] != 0)
			{
			?>
				<p><a href="voirprofil.php?voir=<?php echo $page_enligne['id_membre'] ?>" title="Voir le membre en ligne"><b style='color: <?php echo $page_enligne['couleur_pseudo']; ?>;'><?php echo $page_enligne['pseudo']; ?></b></a><br />
			<?php
			}
			else
			{
				echo "<p><b>Invité</b><br />";
			}
			?>
			Page en cours : <?php echo $page_enligne['page_membre']; ?><br />
			<?php if ($_SESSION['grade'] == 1 || $_SESSION['grade'] == 2)
			{
			echo "IP : {$page_enligne['ip_membre']}<br />";
			echo "User Agent : {$page_enligne['useragent_membre']}";
			}
			?>
			</p>
		<?php
		}
include_once("ressources/footer.php");
?>