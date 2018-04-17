<?php
if (isset($_COOKIE['auth']) && isset($_COOKIE['key']))
{
	$auth = $_COOKIE['auth'];
	$auth = explode('_', $auth);
	$key = $_COOKIE['key'];
	$key = explode('_', $key);
	$_SESSION['id'] = $auth[0];
	$_SESSION['motdepasse'] = $key[0];
	$query = $sql->prepare('SELECT * FROM membres WHERE id = :id AND motdepasse = :motdepasse');
	$query->execute(array(
			'id' => $auth[0],
			'motdepasse' => $key[0],
		));
	$connexion = $query->fetch();
	$auth_valide = sha1($connexion['pseudo']);
	$key_valide = hash('sha512', $connexion['email']);
	$_SESSION['pseudo'] = $connexion['pseudo'];
	$_SESSION['grade'] = $connexion['grade'];
	if (!isset($_SESSION['jeton']))
	{
	$_SESSION['jeton'] = uniqid(rand(), true);
	}
	if ($key_valide != $key[1] || $auth_valide != $auth[1])
	{
		setcookie('auth', '', time(), null, null, false);
		setcookie('key', '', time(), null, null, false);
		session_destroy();
		header("Location: /");
	}
}
	$query = $sql->prepare('SELECT * FROM membres WHERE id = :id');
	$query->execute(array(
			'id' => $_SESSION['id'],	
		));
	$membre = $query->fetch();
	$query = $sql->prepare('SELECT * FROM membres WHERE id = :id AND desactive = :desactive');
	$query->execute(array(
			'id' => $_SESSION['id'],
			'desactive' => "1"
		));
	$desactive = $query->fetch();
	if ($desactive)
	{
		setcookie('auth', '', time(), null, null, false);
		setcookie('key', '', time(), null, null, false);
		session_destroy();
		header("Location: /");
	}
	$query = $sql->prepare('UPDATE membres SET date_derniere_visite = :date_derniere_visite, derniere_ip = :derniere_ip WHERE id = :id');
	$query->execute(array(
		'date_derniere_visite' => time(),
		'derniere_ip' => $_SERVER['REMOTE_ADDR'],
		'id' => $_SESSION['id']
	));
	
	$query = $sql->prepare('SELECT COUNT(*) FROM enligne WHERE ip_membre = :ip_membre');
	$query->execute(array(
		'ip_membre' => $_SERVER['REMOTE_ADDR']
	));
	$ip_logue = $query->fetch();
	$query->closeCursor();
	if ($ip_logue[0] == 0)
	{
		$query = $sql->prepare('INSERT INTO enligne(id_membre, temps_connexion, ip_membre, useragent_membre, page_membre) VALUES(:id_membre, :temps_connexion, :ip_membre, :useragent_membre, :page_membre)');
		$query->execute(array(
			'id_membre' => $_SESSION['id'],
			'temps_connexion' => time(),
			'ip_membre' => $_SERVER['REMOTE_ADDR'],
			'useragent_membre' => $_SERVER['HTTP_USER_AGENT'],
			'page_membre' => $titre
		));
		if (!isset($_SESSION['id']))
		{
			$query = $sql->prepare('INSERT INTO enligne(id_membre, temps_connexion, ip_membre, useragent_membre, page_membre) VALUES(:id_membre, :temps_connexion, :ip_membre, :useragent_membre, :page_membre)');
			$query->execute(array(
				'temps_connexion' => time(),
				'ip_membre' => $_SERVER['REMOTE_ADDR'],
				'useragent_membre' => $_SERVER['HTTP_USER_AGENT'],
				'page_membre' => $titre
			));
		}
	}
	else
	{
		$query = $sql->prepare('UPDATE enligne SET id_membre = :id_membre, temps_connexion = :temps_connexion, page_membre = :page_membre, useragent_membre = :useragent_membre WHERE ip_membre = :ip_membre');
		$query->execute(array(
			'id_membre' => $_SESSION['id'],
			'temps_connexion' => time(),
			'page_membre' => $titre,
			'useragent_membre' => $_SERVER['HTTP_USER_AGENT'],
			'ip_membre' => $_SERVER['REMOTE_ADDR']
		));
		if (!isset($_SESSION['id']))
		{
		$query = $sql->prepare('UPDATE enligne SET id_membre = :id_membre, temps_connexion = :temps_connexion, page_membre = :page_membre, useragent_membre = :useragent_membre WHERE ip_membre = :ip_membre');
		$query->execute(array(
			'id_membre' => 0,
			'temps_connexion' => time(),
			'page_membre' => $titre,
			'useragent_membre' => $_SERVER['HTTP_USER_AGENT'],
			'ip_membre' => $_SERVER['REMOTE_ADDR']
		));
		}
	}
	
	$temps_limite = time() - (60 * 3);
	$query = $sql->prepare('DELETE FROM enligne WHERE temps_connexion < :temps_limite');
	$query->execute(array(
		'temps_limite' => $temps_limite
	));
	$query->closeCursor();
	
?>
<body>
	<?php include_once("{$_SERVER['DOCUMENT_ROOT']}/ressources/parser.php") ?>
	<header>
		<a class="lien_accueil" href="/" title="Accueil"><!--<h3><?php echo $config['site_titre']; ?></h3>--><img src="/ressources/images/logo.png" /></a>
			<ul>
				<li><a href="/forum.php" title="Forum">Forum</a></li>
				<li><a href="/encyclopedie.php" title="Encyclopédie">Encyclopédie</a></li>
				<li><a href="/membres.php" title="Membres">Membres</a></li>
				<?php
				if (isset($_SESSION['id']))
				{
				?>
				<li><a href="chat.php" title="Chat">Chat</a></li>				
				<?php
				}
				?>
			</ul>
				<?php
				if (isset($_SESSION['id']))
				{
					?>
					<div id="membre">
					<a href="moncompte.php" title="Gérer son compte"><img class="avatar" src="<?php echo $membre['avatar']; ?>" alt="Avatar du membre" /></a>
					<b class="pseudo" style='color: <?php echo $membre['couleur_pseudo']; ?>;'><?php echo $_SESSION['pseudo']; ?></b>
					<?php
					if ($_SESSION['grade'] == 0)
					{
					?>
					<b class="pseudo" style='color: darkred;'><?php echo $_SESSION['pseudo']; ?></b>	
					<?php
					}
					?>
					<a href="messagerie.php" class="mp" title="Messages privés"><img src="/ressources/images/svg/mp_read.svg" /></a>
					<?php
					$query = $sql->prepare('SELECT COUNT(*) FROM amis WHERE ami_receveur = :id AND attente = :attente');
					$query->execute(array(
						'id' => $_SESSION['id'],
						'attente' => "1"
					));
					$notif_ami = $query->fetch();
					if ($notif_ami[0] == 0)
					{
						?>
							<a href="amis.php" class="amis" title="Amis"><img src="/ressources/images/svg/friends_unnotified.svg" /></a>
						<?php
					}
					else
					{
						?>
							<a href="amis.php" class="amis" title="Amis"><div class="notifs"><?php echo $notif_ami[0]; ?></div><img src="/ressources/images/svg/friends_notified.svg" /></a>
						<?php
					}
					?>
					</div>
					<?php
				}
				else
				{
					?>
					<div id="non_membre">
					<a href="/inscription.php" title="S'inscrire">Inscription</a>
					<a href="/connexion.php" title="Se connecter">Connexion</a>
					</div>
					<?php
				}
				?>
	</header>
	<main>
