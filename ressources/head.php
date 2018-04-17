<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" type="text/css" href="/ressources/css/style.css">
		<link rel="icon" type="image/png" href="/ressources/images/favicon.png" />
		<link rel="stylesheet" type="text/css" href="/ressources/css/custom.php">
		<script src='/ressources/bbcode.js'></script>
		<script src='/ressources/jquery.js'></script>
		<?php
		if ($_SERVER['REQUEST_URI'] == "/inscription.php")
		{?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php
		}
		if ($_SERVER['REQUEST_URI'] == "/chat.php")
		{?>
		<script src='/ressources/chat.js'></script>
		<?php
		}
		if (isset($description))
		{
		?>
		<meta name="description" content="<?php echo $description; ?>" />
		<?php
		}
		?>
	<title><?php echo "{$config['site_titre']} • $titre"; ?></title>
	</head>
