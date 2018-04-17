<?php

include_once('ressources/sql.php');

$titre = "Mon compte";

include_once("ressources/head.php");
include_once("ressources/header.php");
if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}
?>
	<h1>Salut <?php echo $_SESSION['pseudo']; ?>, que veux tu faire ?</h1>
	<ul>
		<li><a href="modifierprofil.php" title="Modifier son profil">Modifier mon profil</a></li>
		<li><a href="voirprofil.php?voir=<?php echo $_SESSION['id']; ?>" title="Voir son profil">Voir mon profil</a></li>
		<li><a href="administrercompte.php" title="Administrer son compte">Administrer mon compte</a></li>
		<li><a href="deconnexion.php?jeton=<?php echo $_SESSION['jeton']; ?>" title="Se déconnecter">Me déconnecter</a></li>
	</ul>
<?php
include_once("ressources/footer.php");
?>
