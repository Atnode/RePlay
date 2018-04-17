<?php

include_once('ressources/sql.php');

$query = $sql->prepare('SELECT * FROM sscat_forum WHERE id_sscat = :id_sscat');
$query->execute(array(
	'id_sscat' => $_GET['voir']
));
$sscat = $query->fetch();

$query->closeCursor();

$titre = "{$sscat['nom_sscat']}";
$description = "{$sscat['desc_sscat']}";

include_once("ressources/head.php");
include_once("ressources/header.php");
?>

<h1>Forum de <?php echo $config['site_titre']; ?></h1>

<?php

$query = $sql->prepare('SELECT * FROM sujet_forum WHERE id_sscat = :id_sscat');
$query->execute(array(
		'id_sscat' => $_GET['voir']
	));

?>

<div id="forum">
	<div class="titre_principal"><?php echo $sscat['nom_sscat']; ?></div>
		<div class="categories">
			<?php
			while($sujet = $query->fetch())
			{
			?>
			<div class="ss_categories">
				<div class="infos">
					<div class="titre"><a href="voirsujet.php?voir=<?php echo $sujet['id']; ?>"><?php echo $sujet['titre']; ?></a></div>
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

include_once("ressources/footer.php");

?>