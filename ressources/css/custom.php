<?php
session_start();
header('Content-type: text/css'); 
include_once("{$_SERVER['DOCUMENT_ROOT']}/ressources/sql.php");
	$query = $sql->prepare('SELECT couleur_theme, couleur_theme2, theme_sombre FROM membres WHERE id = :id');
	$query->execute(array(
		'id' => $_SESSION['id']
	));
	$couleur = $query->fetch();
	$query->closeCursor();
	
if ($couleur['theme_sombre'] == 1)
{
?>
body
{
	background-color: #141D26;
}
main
{
	background-color: #141D26;
}
h1
{
	color: #FFF !important;
}
a
{
	color: #FFF !important;
}
legend
{
	color: #FFF;
}
label
{
	color: #FFF;
}
fieldset
{
	background-color: #1B2836;
}
p
{
	color: #FFF;
}
#chat p
{
	color: 000;
}
#chat .message
{
	background-color: #344A60;
}
<?php
}
?>
header
{
	background: <?php echo $couleur['couleur_theme']; ?>;
}
footer
{
	background: <?php echo $couleur['couleur_theme']; ?>;
}
h2
{
	color: <?php echo $couleur['couleur_theme']; ?>;
}
.champ_texte
{
	border-color: <?php echo $couleur['couleur_theme']; ?>;
}
#forum .titre_principal
{
	background-color:  <?php echo $couleur['couleur_theme']; ?>;
}
.champ_texte:hover
{
	border-color: <?php echo $couleur['couleur_theme2']; ?>;
}
select:hover
{
	color: <?php echo $couleur['couleur_theme2']; ?>;
}
.champ_bouton
{
	background-color: <?php echo $couleur['couleur_theme2']; ?>;
	border-color: <?php echo $couleur['couleur_theme2']; ?>;
}
.champ_bouton:hover
{
	background-color: <?php echo "{$couleur['couleur_theme2']}b3"; ?>;
	border-color: <?php echo "{$couleur['couleur_theme2']}b3"; ?>;
}
header #non_membre a
{
	backrgound-color: <?php echo $couleur['couleur_theme2']; ?>;
	border-color: <?php echo $couleur['couleur_theme2']; ?>;
}
.lien_accueil:hover
{
	background-color: <?php echo $couleur['couleur_theme2']; ?>;
}
h1
{
	color: <?php echo $couleur['couleur_theme2']; ?>;
}
#forum .titre_principal
{
	color: <?php echo $couleur['couleur_theme2']; ?>;
}
#forum .ss_categories
{
	border-bottom: 1px <?php echo $couleur['couleur_theme2']; ?> solid;
}
a
{
	color: <?php echo $couleur['couleur_theme2']; ?>;
}
header li a:hover
{
	background-color: <?php echo $couleur['couleur_theme2']; ?>;
}