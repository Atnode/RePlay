<?php
function convertir($contenu)
{

$contenu = preg_replace('`\[b\](.+)\[/b\]`isU', '<b>$1</b>', $contenu); 
$contenu = preg_replace('`\[i\](.+)\[/i\]`isU', '<i>$1</i>', $contenu);
$contenu = preg_replace('`\[u\](.+)\[/u\]`isU', '<u>$1</u>', $contenu);
$contenu = preg_replace('`\[couleur=(.+)\](.+)\[/couleur\]`isU', '<span style="color:$1">$2</span>', $contenu);
$contenu = preg_replace('`\[img\](.+)\[/img\]`isU', '<img src="$1" alt="L\'image n\'a pas pu être affiché" />', $contenu);
$contenu = preg_replace('`\[url\](.+)\[/url\]`isU', '<a href="$1">$1</a>', $contenu);
$contenu = preg_replace('`\[url=(.+)\](.+)\[/url\]`isU', '<a href="$1">$2</a>', $contenu);

$contenu = str_replace(':)', '<img src="./ressources/images/smileys/smile.gif" alt="Souriant" title="Souriant" />', $contenu);
$contenu = str_replace(':D', '<img src="./ressources/images/smileys/smiling.gif" alt="Content" title="Content" />', $contenu);
$contenu = str_replace(':thonk:', '<img src="./ressources/images/smileys/thonk.gif" alt="Thonk" title="Thonk" />', $contenu);
$contenu = str_replace(':(', '<img src="./ressources/images/smileys/sad.gif" alt="Triste" title="Triste" />', $contenu);
$contenu = str_replace(':\'(', '<img src="./ressources/images/smileys/cry.gif" alt="Pleure" title="Pleure" />', $contenu);
$contenu = str_replace(';(', '<img src="./ressources/images/smileys/sad2.gif" alt="Fond en larmes" title="Fond en larmes" />', $contenu);
$contenu = str_replace(':o', '<img src="./ressources/images/smileys/bigeek.gif" alt="Surpris" title="Surpris" />', $contenu);
$contenu = str_replace(':hap:', '<img src="./ressources/images/smileys/hap.gif" alt="Hap" title="Hap" />', $contenu);

return $contenu;
}
?>
