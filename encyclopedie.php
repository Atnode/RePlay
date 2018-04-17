<?php

include_once("ressources/sql.php");

$titre = "Encyclopédie";
$description = "L'encyclopédie de {$config['site_titre']} où vous pourrez trouver des informations concernant différentes consoles rétro ou des jeux d'époque";

include_once("ressources/head.php");
include_once("ressources/header.php");
?>

<h1>Encyclopédie de <?php echo $config['site_titre']; ?></h1>
<?php
if (empty($_GET))
{
?>
<ul>
	<li><a href="encyclopedie.php?categories=consoles" title="Les consoles" >Les consoles</a></li>
	<li><a href="encyclopedie.php?categories=jeux" title="Les jeux" >Les jeux</a></li>
	<li><a href="encyclopedie.php?categories=tutos" title="Les tutoriaux" >Les tutoriaux</a></li>
</ul>
<?php
if ($_SESSION['grade'] == 3 || $_SESSION['grade'] == 2 || $_SESSION['grade'] == 1)
{
	?>
		<a href="encyclopedie.php?ajouter" title="Poster un article" ><input class="champ_bouton" type="submit" value="Poster un article" /></a>
	<?php
}
}
if (isset($_GET['categories']))
{
	
	switch($_GET['categories'])
	{
		case "consoles":
			$categories = "encyclo_consoles";
		break;
		
		case "jeux":
			$categories = "encyclo_jeux";
		break;
		
		case "tutos":
			$categories = "encyclo_tutos";
		break;
		
		default:
			header("Location: erreur.php?erreur=404");
			
	}
	
		if (isset($_GET['voir']))
		{
			$valide = true;
			$query = $sql->prepare('SELECT * FROM ' . $categories . ' WHERE id = :id');
			$query->execute(array(
				'id' => $_GET['voir']
				));
			$contenu_encyclo = $query->fetch();
			
			$query = $sql->prepare('SELECT * FROM membres WHERE id = :auteur');
			$query->execute(array(
				'auteur' => $contenu_encyclo['auteur']
				));
			$auteur = $query->fetch();
			
				if ($_GET['categories'] == "consoles" || $_GET['categories'] == "tutos")
				{
				?>
					<h2><?php echo $contenu_encyclo['titre']; ?></h2>
					<p><?php echo convertir(nl2br($contenu_encyclo['contenu'])); ?></p>
					<p style="float: right">Par <a class="pseudo" href="voirprofil.php?voir=<?php echo $auteur['id']; ?>"><b style="color: <?php echo $auteur['couleur_pseudo']; ?>"><?php echo $auteur['pseudo']; ?></b></a></p>
				<?php
				}
				
				if ($_GET['categories'] == "jeux")
				{
				?>
					<h2><?php echo $contenu_encyclo['titre']; ?></h2>
					<p><b>Console :</b> <?php echo $contenu_encyclo['plateforme']; ?><br />
					<b>Développeur / Éditeur :</b> <?php echo $contenu_encyclo['deved']; ?><br />
					<b>Date de sortie européenne :</b> <?php echo $contenu_encyclo['date_sortie']; ?></p>
					<p><?php echo convertir(nl2br($contenu_encyclo['contenu'])); ?></p>
					<p style="float: right">Par <a class="pseudo" href="voirprofil.php?voir=<?php echo $auteur['id']; ?>"><b style="color: <?php echo $auteur['couleur_pseudo']; ?>"><?php echo $auteur['pseudo']; ?></b></a></p>
				<?php
				}
			if (!$contenu_encyclo)
			{
				Header("Location: encyclopedie.php");
			}
		}
		
		if ($valide != true)
		{
		?>
		<table>
		<?php
		if ($_GET['categories'] == "consoles")
		{
		?>
			<tr>
				<th>
					Console
				</th>
				<th>
					Titre
				</th>
			</tr>
		<?php
		}
		if ($_GET['categories'] == "jeux")
		{
		?>
			<tr>
				<th>
					Console
				</th>
				<th>
					Nom
				</th>
				<th>
					Développeur / Éditeur
				</th>
				<th>
					Date de sortie européenne
				</th>
			</tr>
		<?php
		}
		if ($_GET['categories'] == "tutos")
		{
		?>
			<tr>
				<th>
					Console
				</th>
				<th>
					Nom
				</th>
			</tr>
		<?php
		}
		
		$query = $sql->query('SELECT * FROM ' . $categories);
			while($titre_encyclo = $query->fetch())
			{
				if ($_GET['categories'] == "consoles")
				{
					?>
					<tr>
						<td><?php echo $titre_encyclo['plateforme']; ?></td>
						<td><a href="encyclopedie.php?categories=<?php echo $_GET['categories']; ?>&voir=<?php echo $titre_encyclo['id']; ?>"><?php echo $titre_encyclo['titre']; ?></a></td>
					</tr>
					<?php
				}
				
				if ($_GET['categories'] == "jeux")
				{
					?>
					<tr>
						<td><?php echo $titre_encyclo['plateforme']; ?></td>
						<td><a href="encyclopedie.php?categories=<?php echo $_GET['categories']; ?>&voir=<?php echo $titre_encyclo['id']; ?>"><?php echo $titre_encyclo['titre']; ?></a></td>
						<td><?php echo $titre_encyclo['deved']; ?></td>
						<td><?php echo $titre_encyclo['date_sortie']; ?></td>
					</tr>
					<?php
				}
				
				if ($_GET['categories'] == "tutos")
				{
					?>
					<tr>
						<td><?php echo $titre_encyclo['plateforme']; ?></td>
						<td><a href="encyclopedie.php?categories=<?php echo $_GET['categories']; ?>&voir=<?php echo $titre_encyclo['id']; ?>"><?php echo $titre_encyclo['titre']; ?></a></td>
					</tr>
					<?php
				}
			}
			?>
			</table>
			<?php
		}
}

if (isset($_GET['ajouter']))
{
	if (!isset($_SESSION['id']))
	{
		header( "Location: erreur.php?erreur=403");
	}
	
	if ($_SESSION['grade'] == 4 || $_SESSION['grade'] == 0)
	{
		header("Location: encyclopedie.php");
	}
	
	if (empty($_GET['ajouter']))
	{
	?>
	<ul>
		<li><a href="encyclopedie.php?ajouter=consoles">Ajouter une console</a></li>
		<li><a href="encyclopedie.php?ajouter=jeux">Ajouter un jeu</a></li>
		<li><a href="encyclopedie.php?ajouter=tutos">Ajouter un tutoriel ou une astuce</a></li>
	</ul>
	<?php
	}
	
	if ($_GET['ajouter'] == "jeux")
	{
	?>
	<h2>Ajouter un jeu sur l'encyclopédie</h2>
	<?php
		if (isset($_POST['envoyer']))
		{
			if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
			{
				$erreur = 0;
				
				if (empty($_POST['titre'] || $_POST['date'] || $_POST['dev'] || $_POST['contenu']))
				{
					$message_erreur = "<p>Tu n'as pas rempli tous les champs</p>";
					$erreur++;
				}
				
				$plateformes = $_POST['plateformes'];
				
				switch($plateformes)
				{
					//Sony
					case "PS1":
						$plateformes = "PS1";
					break;
					
					case "PS2":
						$plateformes = "PS2";
					break;
					
					//Nintendo
					case "NES":
						$plateformes = "NES";
					break;
			
					case "GB":
						$plateformes = "GB";
					break;
					
					case "SNES":
						$plateformes = "SNES";
					break;
					
					case "N64":
						$plateformes = "N64";
					break;
			
					case "GBC":
						$plateformes = "GBC";
					break;
					
					case "GBA":
						$plateformes = "GBA";
					break;
					
					case "NGC":
						$plateformes = "NGC";
					break;
			
					//Sega
					case "MS":
						$plateformes = "MS";
					break;
					
					case "MG":
						$plateformes = "MG";
					break;
					
					case "GG":
						$plateformes = "GG";
					break;
					
					case "DC":
						$plateformes = "DC";
					break;
					
					//Microsoft
					case "XBOX":
						$plateformes = "Xbox";
					break;
					
					default:
						$message_erreur = "<p>Erreur de publication (Valeur plateformes invalide)</p>";
						$erreur++;
				}
				
				if(!preg_match("/([0-9]){4}-([0-9]){2}-([0-9]){2}/", $_POST['date']))
				{
					$message_erreur = "<p>La date n'a pas un format valide</p>";
					$erreur++;
				}
				
				if ($erreur == 0)
				{
					$valide = true;
					
					$query = $sql->prepare('INSERT INTO encyclo_jeux(deved, auteur, titre, plateforme, date_sortie, contenu) VALUES(:deved, :auteur, :titre, :plateforme, :date_sortie, :contenu)');
					$query->execute(array(
						'deved' => htmlspecialchars($_POST['dev']),
						'auteur' => $_SESSION['id'],
						'titre' => htmlspecialchars($_POST['titre']),
						'plateforme' => htmlspecialchars($plateformes),
						'date_sortie' => htmlspecialchars($_POST['date']),
						'contenu' => htmlspecialchars($_POST['contenu'])
						));
					echo "Le jeu a bien été ajouté";
					header("Refresh:1.5; url=/");
				}
			}
			else
			{
				$message_erreur = "<p>Erreur de parité du jeton</p>";
			}
		}
		
		if ($valide != true)
		{
			echo $message_erreur;
			?>
			<form method="post" action="encyclopedie.php?ajouter=jeux&amp;jeton=<?php echo $_SESSION['jeton']; ?>">
				<select name="plateformes">
					<optgroup label="Sony">
						<option value="PS1">Sony PS1</option>
						<option value="PS2">Sony PS2</option>
					</optgroup>
					<optgroup label="Nintendo">
						<option value="NES">Nintendo NES</option>
						<option value="GB">Nintendo GameBoy</option>
						<option value="SNES">Nintendo SNES</option>
						<option value="N64">Nintendo 64</option>
						<option value="GBC">Nintendo GameBoy Color</option>
						<option value="GBA">Nintendo GameBoy Advance</option>
						<option value="NGC">Nintendo GameCube</option>
					</optgroup>
					<optgroup label="Sega">
						<option value="MS">Sega Master System</option>
						<option value="MG">Sega Mega Drive</option>
						<option value="GG">Sega Game Gear</option>
						<option value="DC">Sega Dreamcast</option>
					</optgroup>
					<optgroup label="Microsoft">
						<option value="XBOX">Microsoft Xbox</option>
					</optgroup>
				</select>
				<br />
				<br />
				<input class="champ_texte" type="text" name="titre" placeholder="Titre" value="<?php echo $_POST['titre']; ?>" />
				<br />
				<br />
				<input class="champ_texte" type="date" name="date" placeholder="Date de sortie" value="<?php echo $_POST['date']; ?>" />
				<br />
				<br />
				<input class="champ_texte" type="text" name="dev" placeholder="Développeur / Éditeur" value="<?php echo $_POST['dev']; ?>" />
				<br />
				<br />
				<?php include("ressources/bbcode.php"); ?>
				<textarea class="champ_texte" id="contenu" name="contenu" cols="50" rows="5" placeholder="Écris ici ton article du jeu"><?php echo $_POST['contenu']; ?></textarea>
				<br />
				<br />
				<input class="champ_bouton" type="submit" name="envoyer" value="Publier" />
				<br />
				<br />
			</form>
	<?php	
		}
	}

	if ($_GET['ajouter'] == "tutos")
	{
	?>
	<h2>Ajouter un tutoriel ou une astuce sur l'encyclopédie</h2>
	<?php
		if (isset($_POST['envoyer']))
		{
			if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
			{
				$erreur = 0;
				
				if (empty($_POST['titre'] || $_POST['contenu']))
				{
					$message_erreur = "<p>Tu n'as pas rempli tous les champs</p>";
					$erreur++;
				}
				
				$plateformes = $_POST['plateformes'];
				
				switch($plateformes)
				{
					//Sony
					case "PS1":
						$plateformes = "PS1";
					break;
					
					case "PS2":
						$plateformes = "PS2";
					break;
					
					//Nintendo
					case "NES":
						$plateformes = "NES";
					break;
			
					case "GB":
						$plateformes = "GB";
					break;
					
					case "SNES":
						$plateformes = "SNES";
					break;
					
					case "N64":
						$plateformes = "N64";
					break;
			
					case "GBC":
						$plateformes = "GBC";
					break;
					
					case "GBA":
						$plateformes = "GBA";
					break;
					
					case "NGC":
						$plateformes = "NGC";
					break;
			
					//Sega
					case "MS":
						$plateformes = "MS";
					break;
					
					case "MG":
						$plateformes = "MG";
					break;
					
					case "GG":
						$plateformes = "GG";
					break;
					
					case "DC":
						$plateformes = "DC";
					break;
					
					//Microsoft
					case "XBOX":
						$plateformes = "Xbox";
					break;
					
					default:
						$message_erreur = "<p>Erreur de publication (Valeur plateformes invalide)</p>";
						$erreur++;
				}
				
				if ($erreur == 0)
				{
					$valide = true;
					
					$query = $sql->prepare('INSERT INTO encyclo_tutos( auteur, titre, plateforme, contenu) VALUES(:auteur, :titre, :plateforme, :contenu)');
					$query->execute(array(
						'auteur' => $_SESSION['id'],
						'titre' => htmlspecialchars($_POST['titre']),
						'plateforme' => htmlspecialchars($plateformes),
						'contenu' => htmlspecialchars($_POST['contenu'])
						));
					echo "Le tutoriel ou l'astuce a bien été ajouté";
					header("Refresh:1.5; url=/");
				}
			}
			else
			{
				$message_erreur = "<p>Erreur de parité du jeton</p>";
			}
		}
		
		if ($valide != true)
		{
			echo $message_erreur;
			?>
			<form method="post" action="encyclopedie.php?ajouter=tutos&amp;jeton=<?php echo $_SESSION['jeton']; ?>">
				<select name="plateformes">
					<optgroup label="Sony">
						<option value="PS1">Sony PS1</option>
						<option value="PS2">Sony PS2</option>
					</optgroup>
					<optgroup label="Nintendo">
						<option value="NES">Nintendo NES</option>
						<option value="GB">Nintendo GameBoy</option>
						<option value="SNES">Nintendo SNES</option>
						<option value="N64">Nintendo 64</option>
						<option value="GBC">Nintendo GameBoy Color</option>
						<option value="GBA">Nintendo GameBoy Advance</option>
						<option value="NGC">Nintendo GameCube</option>
					</optgroup>
					<optgroup label="Sega">
						<option value="MS">Sega Master System</option>
						<option value="MG">Sega Mega Drive</option>
						<option value="GG">Sega Game Gear</option>
						<option value="DC">Sega Dreamcast</option>
					</optgroup>
					<optgroup label="Microsoft">
						<option value="XBOX">Microsoft Xbox</option>
					</optgroup>
				</select>
				<br />
				<br />
				<input class="champ_texte" type="text" name="titre" placeholder="Titre" value="<?php echo $_POST['titre']; ?>" />
				<br />
				<br />
				<?php include("ressources/bbcode.php"); ?>
				<textarea class="champ_texte" id="contenu" name="contenu" cols="50" rows="5" placeholder="Écris ici ton tutoriel ou ton astuce"><?php echo $_POST['contenu']; ?></textarea>
				<br />
				<br />
				<input class="champ_bouton" type="submit" name="envoyer" value="Publier" />
				<br />
				<br />
			</form>
	<?php	
		}
	}
	
	if ($_GET['ajouter'] == "consoles")
	{
	?>
	<h2>Ajouter une console sur l'encyclopédie</h2>
	<?php
		if (isset($_POST['envoyer']))
		{
			if (isset($_GET['jeton']) && $_GET['jeton'] == $_SESSION['jeton'])
			{
				$erreur = 0;
				
				if (empty($_POST['titre']) || empty($_POST['contenu']))
				{
					$message_erreur = "<p>Tu n'as pas rempli tous les champs</p>";
					$erreur++;
				}
				
				$plateformes = $_POST['plateformes'];
				
				switch($plateformes)
				{
					//Sony
					case "PS1":
						$plateformes = "PS1";
					break;
					
					case "PS2":
						$plateformes = "PS2";
					break;
					
					//Nintendo
					case "NES":
						$plateformes = "NES";
					break;
			
					case "GB":
						$plateformes = "GB";
					break;
					
					case "SNES":
						$plateformes = "SNES";
					break;
					
					case "N64":
						$plateformes = "N64";
					break;
			
					case "GBC":
						$plateformes = "GBC";
					break;
					
					case "GBA":
						$plateformes = "GBA";
					break;
					
					case "NGC":
						$plateformes = "NGC";
					break;
			
					//Sega
					case "MS":
						$plateformes = "MS";
					break;
					
					case "MG":
						$plateformes = "MG";
					break;
					
					case "GG":
						$plateformes = "GG";
					break;
					
					case "DC":
						$plateformes = "DC";
					break;
					
					//Microsoft
					case "XBOX":
						$plateformes = "Xbox";
					break;
					
					default:
						$message_erreur = "<p>Erreur de publication (Valeur plateformes invalide)</p>";
						$erreur++;
				}
				
				if ($erreur == 0)
				{
					$valide = true;
					
					$query = $sql->prepare('INSERT INTO encyclo_consoles( auteur, titre, plateforme, contenu) VALUES(:auteur, :titre, :plateforme, :contenu)');
					$query->execute(array(
						'auteur' => $_SESSION['id'],
						'titre' => htmlspecialchars($_POST['titre']),
						'plateforme' => htmlspecialchars($plateformes),
						'contenu' => htmlspecialchars($_POST['contenu'])
						));
					echo "La console a bien été ajouté";
					header("Refresh:1.5; url=/");
				}
			}
			else
			{
				$message_erreur = "<p>Erreur de parité du jeton</p>";
			}
		}
		
		if ($valide != true)
		{
			echo $message_erreur;
			?>
			<form method="post" action="encyclopedie.php?ajouter=consoles&amp;jeton=<?php echo $_SESSION['jeton']; ?>">
				<select name="plateformes">
					<optgroup label="Sony">
						<option value="PS1">Sony PS1</option>
						<option value="PS2">Sony PS2</option>
					</optgroup>
					<optgroup label="Nintendo">
						<option value="NES">Nintendo NES</option>
						<option value="GB">Nintendo GameBoy</option>
						<option value="SNES">Nintendo SNES</option>
						<option value="N64">Nintendo 64</option>
						<option value="GBC">Nintendo GameBoy Color</option>
						<option value="GBA">Nintendo GameBoy Advance</option>
						<option value="NGC">Nintendo GameCube</option>
					</optgroup>
					<optgroup label="Sega">
						<option value="MS">Sega Master System</option>
						<option value="MG">Sega Mega Drive</option>
						<option value="GG">Sega Game Gear</option>
						<option value="DC">Sega Dreamcast</option>
					</optgroup>
					<optgroup label="Microsoft">
						<option value="XBOX">Microsoft Xbox</option>
					</optgroup>
				</select>
				<br />
				<br />
				<input class="champ_texte" type="text" name="titre" placeholder="Titre" value="<?php echo $_POST['titre']; ?>" />
				<br />
				<br />
				<?php include("ressources/bbcode.php"); ?>
				<textarea class="champ_texte" id="contenu" name="contenu" cols="50" rows="5" placeholder="Écris ici ton article de la console"><?php echo $_POST['contenu']; ?></textarea>
				<br />
				<br />
				<input class="champ_bouton" type="submit" name="envoyer" value="Publier" />
				<br />
				<br />
			</form>
	<?php	
		}
	}
}
include_once("ressources/footer.php");
?>

