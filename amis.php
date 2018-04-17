<?php

include_once('ressources/sql.php');

$titre = "Gérer ses amis";

include_once("ressources/head.php");
include_once("ressources/header.php");

if (!isset($_SESSION['id']))
{
	header("Location: erreur.php?erreur=403");
}

echo "<h1>Gérer ses amis</h1>";

if (empty($_GET))
{
echo "<h2>Demandes d'amis</h2>";

$query = $sql->prepare('SELECT ami_demandeur, ami_receveur, pseudo, couleur_pseudo, id, avatar FROM amis LEFT JOIN membres ON id = ami_demandeur WHERE ami_receveur = :id AND attente = :attente');
$query->execute(array(
	'id' => $_SESSION['id'],
	'attente' => "1"
	));

if ($query->rowCount() == 0)
{
	echo "<p>Aucune demande en attente</p>";
}
while($attente = $query->fetch())
{
?>
	<div id="liste_amis_attente">
			<img src="<?php echo $attente['avatar']; ?>" alt="Avatar de <?php echo $attente['pseudo']; ?>" />
			<a class="pseudo" href="voirprofil.php?voir=<?php echo $attente['id']; ?>"><b style="color: <?php echo $attente['couleur_pseudo']; ?>"><?php echo $attente['pseudo']; ?></b></a><br />
			<a href="amis.php?confirmer=<?php echo $attente['id']; ?>&amp;jeton=<?php echo $_SESSION['jeton']; ?>">Accepter</a>
			<a href="amis.php?retirer=<?php echo $attente['id']; ?>&amp;jeton=<?php echo $_SESSION['jeton']; ?>">Refuser</a>
	</div>
	<br />

<?php
}
$query->closeCursor();

echo "<h2>Requêtes d'amis</h2>";

$query = $sql->prepare('SELECT ami_demandeur, ami_receveur, pseudo, couleur_pseudo, id, avatar FROM amis LEFT JOIN membres ON id = ami_receveur WHERE ami_demandeur = :id AND attente = :attente');
$query->execute(array(
	'id' => $_SESSION['id'],
	'attente' => "1"
	));

if ($query->rowCount() == 0)
{
	echo "<p>Aucune requête</p>";
}
while($demande = $query->fetch())
{
?>
	<div id="liste_amis_requete">
			<img src="<?php echo $demande['avatar']; ?>" alt="Avatar de <?php echo $demande['pseudo']; ?>" />
			<a class="pseudo" href="voirprofil.php?voir=<?php echo $demande['id']; ?>"><b style="color: <?php echo $demande['couleur_pseudo']; ?>"><?php echo $demande['pseudo']; ?></b></a><br />
			<a href="amis.php?retirer=<?php echo $demande['id']; ?>&amp;jeton=<?php echo $_SESSION['jeton']; ?>">Retirer</a>
	</div>
	<br />

<?php
}
$query->closeCursor();
?>

<h2>Amis</h2>
<?php
	$query = $sql->prepare('SELECT ami_demandeur, ami_receveur, pseudo, couleur_pseudo, avatar, id 
	FROM amis 
	LEFT JOIN membres 
	ON id = (ami_demandeur + ami_receveur - :id) 
	WHERE ami_demandeur = :id 
	AND attente = :attente
	OR ami_receveur = :id 
	AND attente = :attente');
	$query->execute(array(
		'id' => $_SESSION['id'],
		'attente' => "0"
		));
		
		if ($query->rowCount() == 0)
		{
			echo "<p>Tu n'as pas d'amis pour le moment</p>";
		}
		
		while ($liste_amis = $query->fetch())
		{?>
		<div id="liste_amis">
			<img src="<?php echo $liste_amis['avatar']; ?>" alt="Avatar de <?php echo $liste_amis['pseudo']; ?>" />
			<a class="pseudo" href="voirprofil.php?voir=<?php echo $liste_amis['id']; ?>"><b style="color: <?php echo $liste_amis['couleur_pseudo']; ?>"><?php echo $liste_amis['pseudo']; ?></b></a><br />
			<a href="amis.php?retirer=<?php echo $liste_amis['id']; ?>&amp;jeton=<?php echo $_SESSION['jeton']; ?>">Retirer <?php echo $liste_amis['pseudo']; ?></a>
		</div>
		<br />
		<?php
		}
		$query->closeCursor();

}
else
{

if (isset($_GET['ajouter']) || isset($_GET['confirmer']) || isset($_GET['retirer']))
{

	if (isset($_GET['ajouter']))
	{
		if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
		{
			$query = $sql->prepare('SELECT * FROM membres WHERE id = :id');
			$query->execute(array(
				'id' => $_GET['ajouter']
			));
			$membre = $query->fetch();
			
			$id_receveur = $membre['id'];
			
		if(!$membre || $_GET['ajouter'] == 1)
		{
			echo "<p>Ce membre n'existe pas</p>";
			$erreur++;
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
		
		if($_SESSION['id'] == $id_receveur)
		{
			echo "<p>Tu ne peux pas t'ajouter toi même</p>";
			$erreur++;
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
		
			$query = $sql->prepare('SELECT COUNT(*) FROM amis WHERE ami_demandeur = :id_demandeur AND ami_receveur = :id_receveur OR ami_demandeur = :id_receveur AND ami_receveur = :id_demandeur');
			$query->execute(array(
				'id_demandeur' => $_SESSION['id'],
				'id_receveur' => $membre['id']
			));
			$deja_ami = $query->fetch();
			
		if ($deja_ami[0] != 0)
		{
			echo "<p>Tu es déjà ami avec ce membre</p>";
			$erreur++;
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
		if ($erreur == 0)
		{
			$query = $sql->prepare('INSERT INTO amis(ami_demandeur, ami_receveur, attente, date_amitie) VALUES(:ami_demandeur, :ami_receveur, :attente, :date_amitie)');
			$query->execute(array(
					'ami_demandeur' => htmlspecialchars($_SESSION['id']),
					'ami_receveur' => htmlspecialchars($id_receveur),
					'attente' => "1",
					'date_amitie' => time()
			));
			echo "<p>{$membre['pseudo']} a été ajouté à tes amis. Il faut toutefois que le membre accepte ta demande</p>";
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
		}
		else
		{
			echo "<p>Erreur de parité du jeton</p>";
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
	}
	
	
	if (isset($_GET['confirmer']))
	{
		if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
		{
		$query = $sql->prepare('SELECT COUNT(*) FROM amis WHERE ami_receveur = :id AND ami_demandeur = :membre_confirmation AND attente = :attente');
		$query->execute(array(
		'attente' => "0",
		'membre_confirmation' => $_GET['confirmer'],
		'id' => $_SESSION['id']
		));
		$deja_confirme = $query->fetch();
		
		if ($deja_confirme[0] != 0)
		{
			echo "<p>Ce membre est déjà dans ta liste d'amis</p>";
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
		else
		{
			$query = $sql->prepare('UPDATE amis SET attente = :attente WHERE ami_receveur = :id AND ami_demandeur = :membre_confirmation');
			$query->execute(array(
				'attente' => "0",
				'membre_confirmation' => $_GET['confirmer'],
				'id' => $_SESSION['id']
				));
			echo "Le membre a bien été ajouté à ta liste d'amis";
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
		}
		else
		{
			echo "<p>Erreur de parité du jeton</p>";
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
	}
	
	
	if (isset($_GET['retirer']))
	{
		if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
		{
		$query = $sql->prepare('DELETE FROM amis WHERE ami_demandeur = :id AND ami_receveur = :retirer');
		$query->execute(array(
		'id' => $_SESSION['id'],
		'retirer' => $_GET['retirer']
		));
		
		$query = $sql->prepare('DELETE FROM amis WHERE ami_demandeur = :retirer AND ami_receveur = :id');
		$query->execute(array(
		'id' => $_SESSION['id'],
		'retirer' => $_GET['retirer']
		));
		
		echo "<p>Le membre a été retiré de ta liste d'amis</p>";
		?><script>setTimeout("history.back()", 1500);</script><?php
		}
		else
		{
			echo "<p>Erreur de parité du jeton</p>";
			?><script>setTimeout("history.back()", 1500);</script><?php
		}
	}

}
else
{
	header("Location: erreur.php?erreur=404");
}

}
include_once("ressources/footer.php");
?>
