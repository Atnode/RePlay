<?php
try
	{
        $sql = new PDO('mysql:host=localhost;dbname=replay;charset=utf8', 'replay', 'PA22W0RD');
	}
catch (Exception $e)
	{
        die('Erreur: ' . $e->getMessage());
	}
	
	$query = $sql->query('SELECT * FROM configuration');
	$config = $query->fetch();
	$query->closeCursor();
	
?>