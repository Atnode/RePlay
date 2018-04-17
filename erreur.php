<?php
include_once("ressources/sql.php");

$titre = "Erreur";

include_once("ressources/head.php");

if (!isset($_GET['erreur']))
{
	header( "Location: erreur.php?erreur=403");
}

include_once("ressources/header.php");

switch($_GET['erreur'])
{
	case '403':
		header('HTTP/1.1 403 Forbidden');
		echo "<h1>Accès interdit</h1>";
	break;
	case '404':
		header('HTTP/1.1 404 Not Found');
		echo "<h1>La page n'existe pas</h1>";
	break;
	default:
		echo "<h1>Requête incorrecte</h1>";
}

include_once("ressources/footer.php");
?>