<?php

include_once('ressources/sql.php');

$titre = "Déconnexion";

include_once("ressources/head.php");
include_once("ressources/header.php");

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}
else
{
	if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
	{
		if (isset($_COOKIE['auth']) && isset($_COOKIE['key']))
		{
			setcookie('auth', '', time(), null, null, false);
			setcookie('key', '', time(), null, null, false);
		}
	session_destroy();
	header( "Location: /");
	}
	else
	{
		echo "<p>Erreur de parité du jeton</p>";
		header("Refresh:1.5; url=/");
	}
}

include_once("ressources/footer.php");
?>
