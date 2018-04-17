<?php

include_once('ressources/sql.php');

$query = $sql->prepare('SELECT * FROM membres WHERE id = :id');
$query->execute(array(
	'id' => $_GET['voir']
));
$infos_membre = $query->fetch();

$titre = "Voir le profil de {$infos_membre['pseudo']}";
$description = "Consulter le profil de {$infos_membre['pseudo']} sur {$config['site_titre']}";

include_once("ressources/head.php");
include_once("ressources/header.php");
if (!isset($_GET['voir']) || $_GET['voir'] == 1)
{
	header( "Location: erreur.php?erreur=404");
}
if ($infos_membre)
{

if ($_GET['voir'] == $_SESSION['id'])
{
if ($_SESSION['grade'] == 0)
{?>
<h1>C'est toi (<b style='color: <?php echo $infos_membre['couleur_pseudo']; ?>;'><?php echo $infos_membre['pseudo']; ?></b>) <b style="color: darkred;">(Banni)</b></h1>
<?php
}
else
{
?>
<h1>C'est toi (<b style='color: <?php echo $infos_membre['couleur_pseudo']; ?>;'><?php echo $infos_membre['pseudo']; ?></b>)</h1>
<?php
}
}
else
{?>
<h1>Profil de <b style='color: <?php echo $infos_membre['couleur_pseudo']; ?>;'><?php echo $infos_membre['pseudo']; ?></b></h1>
<?php
}
?>
<img class="avatar_profil" src="<?php echo $infos_membre['avatar']; ?>" alt="Avatar de <?php echo $infos_membre['pseudo']; ?>" /><br />
<?php
if ($infos_membre['date_derniere_visite'] > time() - (60 * 3))
{
	echo "<b style='color: darkgreen;'>Connecté</b><br />";
}
else
{
	echo "<b style='color: darkred;'>Déconnecté</b><br />";
}

if ($infos_membre['id'] != $_SESSION['id'] && $_SESSION['id'] != 0)
{

$query = $sql->prepare('SELECT COUNT(*) FROM amis WHERE ami_receveur = :membre_confirmation AND ami_demandeur = :id OR ami_receveur = :id AND ami_demandeur = :membre_confirmation');
$query->execute(array(
	'membre_confirmation' => $infos_membre['id'],
	'id' => $_SESSION['id']
));
$ami = $query->fetch();
	
if ($ami[0] != 0)
{
?>
	<a href="amis.php?retirer=<?php echo $infos_membre['id']; ?>&amp;jeton=<?php echo $_SESSION['jeton']; ?>">Retirer <?php echo $infos_membre['pseudo']; ?> de ses amis</a>	
<?php
}
else
{
?>
	<a href="amis.php?ajouter=<?php echo $infos_membre['id']; ?>&amp;jeton=<?php echo $_SESSION['jeton']; ?>">Ajouter <?php echo $infos_membre['pseudo']; ?> en ami</a>
<?php
}
}
?>
<p>Inscription : Le <?php echo date('d/m/Y à H:i', $infos_membre['date_inscription']) ?><br />
Dernière visite : Le <?php echo date('d/m/Y à H:i', $infos_membre['date_derniere_visite']) ?></p>
<?php
}
else
{
	header( "Location: erreur.php?erreur=404");
}
include_once("ressources/footer.php");
?>
