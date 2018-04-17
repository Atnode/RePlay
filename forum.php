<?php

include_once('ressources/sql.php');

$titre = "Forum";
$description = "Parlez de différents sujets sur le forum de {$config['site_titre']}";

include_once("ressources/head.php");
include_once("ressources/header.php");
?>

<h1>Forum de <?php echo $config['site_titre']; ?></h1>

<?php
	$categories_sql = $sql->query('SELECT * FROM categories_forum ORDER by nom_cat');
	$sscat_sql = $sql->prepare('SELECT * FROM sscat_forum WHERE categories = :categories ORDER BY nom_sscat');
	while($categories = $categories_sql->fetch())
	{
		?>
<div id="forum">
	<div class="titre_principal"><?php echo $categories['nom_cat']; ?></div>
		<div class="categories">
			<?php
			$sscat_sql->execute(array(
				'categories' => $categories['id_cat']
			));
			while($sscat = $sscat_sql->fetch())
			{
			?>
			<div class="ss_categories">
				<div class="infos">
					<div class="titre"><a href="/sujets.php?voir=<?php echo $sscat['id_sscat']; ?>"><?php echo $sscat['nom_sscat']; ?></a></div>
					<div class="description"><?php echo $sscat['desc_sscat']; ?></div>
				</div>
				<div class="stats">20 Sujets<br/>1 Messages</div>
				<div class="dernier_message">Par Ventilo<br />Le 01 Janvier 1970</div>
			</div>
			<?php
			}
			?>
	</div>
</div>
		<?php
	}
?>

	<!--
	<div class="titre_principal">Salut</div>
		<div class="categories">
			<div class="ss_categories">
				<div class="infos">
					<div class="titre">Salut</div>
					<div class="description">Je suis en train de causer</div>
				</div>
				<div class="stats">1200 Messages</div>
				<div class="dernier_message">LK</div>
			</div>
		</div>
	
	<div class="titre_principal">Salut</div>
		<div class="categories">
			<div class="ss_categories">
				<div class="infos">
					<div class="titre">Salut</div>
					<div class="description">Je suis en train de causer</div>
				</div>
				<div class="stats">1200 Messages</div>
				<div class="dernier_message">LK</div>
			</div>
		</div>
	-->

<?php
include_once("ressources/footer.php");