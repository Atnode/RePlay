<?php

$titre = "Chat";

include_once("ressources/sql.php");
include_once("ressources/head.php");
include_once("ressources/header.php");

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}
?>
	<h1>Chat</h1>
	<?php
	if ($_SESSION['grade'] != 0)
	{
	?>
	<div id="form_chat">
		<?php include("ressources/bbcode.php"); ?>
		<input class="champ_texte" id="contenu" name="message" placeholder="Ton message" /><br />
		<button id="envoyer" class="champ_bouton" onclick="envoyerMessage()">Envoyer</button><br /><br />
	</div>	
	<?php
	}
	else
	{
	?>
	<div id="form_chat">
		<b style="color: darkred;">Tu es banni</b>
	</div>
	<?php
	}
	?>
	<div id="erreur_chat"></div>
	<div id="chat"></div>
		<script>
		$("#contenu").keyup(function(event){
			if(event.keyCode == 13){
	    	$("#envoyer").click();
			}
		});
	</script>
<?php
include_once("ressources/footer.php");
?>