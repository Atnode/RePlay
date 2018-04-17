<?php
session_start();

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}
include_once("{$_SERVER['DOCUMENT_ROOT']}/ressources/sql.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/ressources/parser.php");

/*
$query = $sql->query('SELECT COUNT(*) AS nombre_message FROM chat_messages');
$total = $query->fetch();
$query->closeCursor();
$debut = $total[0] - 16;
*/

$query = $sql->query('SELECT * FROM chat_messages LEFT JOIN membres ON membres.id = auteur ORDER BY message_date DESC LIMIT 0, 15');
while($chat_messages = $query->fetch())
{
	if ($chat_messages['id'] == 1)
	{
	?>
	<div class="message"><b style="color: <?php echo $chat_messages['couleur_pseudo']; ?>"><?php echo $chat_messages['pseudo']; ?></b> le <?php echo date('d/m/Y à H:i', $chat_messages['message_date']); ?>
	<?php
	}
	else
	{
	?>
	<div class="message"><span style="cursor: pointer;" title="Citer <?php echo $chat_messages['pseudo']; ?>" onclick="bbcode('[couleur=<?php echo $chat_messages['couleur_pseudo']; ?>][b]<?php echo $chat_messages['pseudo']; ?> -> [/b][/couleur]', '','contenu');">@</span> <a class="pseudo" href="voirprofil.php?voir=<?php echo $chat_messages['id']; ?>"><b style="color: <?php echo $chat_messages['couleur_pseudo']; ?>"><?php echo $chat_messages['pseudo']; ?></b></a> le <?php echo date('d/m/Y à H:i', $chat_messages['message_date']); 
	}?><br />
	<p><?php echo convertir(nl2br($chat_messages['message'])); ?></p></div>

<?php
}

?>