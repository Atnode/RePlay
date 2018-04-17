<?php
session_start();

include_once("{$_SERVER['DOCUMENT_ROOT']}/ressources/sql.php");

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}

header('Content-Type: application/json');

if ($_SESSION['grade'] != 0)
{

if(empty($_POST['message']) || $_POST['message'] == '&nbsp;' || ctype_space($_POST['message']))
{
	$retour = "1";
}
else
{
	$query = $sql->prepare('SELECT * FROM chat_messages WHERE auteur = :auteur ORDER BY id DESC LIMIT 0,1');
	$query->execute(array(
		'auteur' => $_SESSION['id']
		));
	$date_verif = $query->fetch();
	$query->closeCursor();
	
	if ($date_verif['message_date'] + 3 > time())
	{
		$retour = "2";	
	}
	else
	{
		if ($_POST['message'] == '/clear' && $_SESSION['grade'] == 1 || $_SESSION['grade'] == 2)
		{
			$query = $sql->query('TRUNCATE TABLE chat_messages');
			$query = $sql->prepare('INSERT INTO chat_messages(auteur, message, message_date) VALUES(:auteur, :message, :message_date)');
			$query->execute(array(
				'auteur' => "1",
				'message' => "L'historique du chat vient d'être effacé",
				'message_date' => time() + 5
			));
		}
		
		if ($_POST['message'] == '/ventilo')
		{
			$ventilo_tableau = array("Un quai-babbe", "Des motos ? Les gens seraient renversés :hap:", "Wii U Chat va fermer ! :(");
			shuffle($ventilo_tableau);
			$query = $sql->prepare('INSERT INTO chat_messages(auteur, message, message_date) VALUES(:auteur, :message, :message_date)');
			$query->execute(array(
				'auteur' => "1",
				'message' => $ventilo_tableau[0],
				'message_date' => time() + 5
			));
		}
		
		$query = $sql->prepare('INSERT INTO chat_messages(auteur, message, message_date) VALUES(:auteur, :message, :message_date)');
		$query->execute(array(
			'auteur' => $_SESSION['id'],
			'message' => htmlspecialchars($_POST['message']),
			'message_date' => time()
		));
		$retour = "3";
	}
}

echo json_encode(array(
	'retour' => $retour
));

}
?>