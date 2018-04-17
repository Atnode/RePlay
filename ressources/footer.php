	</main>
		<?php
		$query = $sql->prepare('SELECT * FROM membres ORDER BY ID DESC LIMIT 0,1');
		$query->execute();
		$derniermembre = $query->fetch();
		$query->closeCursor();
		
		$query = $sql->prepare('SELECT id_membre, temps_connexion, ip_membre, page_membre, id, pseudo, couleur_pseudo FROM enligne LEFT JOIN membres ON id_membre = id WHERE temps_connexion > :temps_limite');
		$query->execute(array(
			'temps_limite' => $temps_limite
		));
		?>
		<footer>
			<p><?php echo $config['site_titre']; ?> - <?php echo date('Y'); ?><br />
			<?php echo "Version : {$config['version']}"; ?></p>
			<?php if (isset($_SESSION['id']))
			{
			?>
			<p><a href="enligne.php">Qui est en ligne ?</a><br />
			<?php
			}
			else
			{
			?>
			<p><b>Qui est en ligne ?</b><br />
			<?php	
			}
			while($enligne = $query->fetch())
			{
			?>
				<a href="voirprofil.php?voir=<?php echo $enligne['id_membre'] ?>" title="Voir le membre en ligne"><b style='color: <?php echo $enligne['couleur_pseudo']; ?>;'><?php echo $enligne['pseudo']; ?></b></a>
			<?php
			}
			?></p>
			<p>Le dernier membre est : <a href="voirprofil.php?voir=<?php echo $derniermembre['id']; ?>" title="Voir le dernier membre inscrit"><b style='color: <?php echo $derniermembre['couleur_pseudo']; ?>;'><?php echo $derniermembre['pseudo']; ?></b></a></p>
		</footer>
	</body>
</html>
