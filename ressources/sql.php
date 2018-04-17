<?php
try
	{
        $sql = new PDO('mysql:host=localhost;dbname=replay;charset=utf8', 'replay', 'YbXP9vRIRoSLQGHJ1oXK');
	}
catch (Exception $e)
	{
        die('Erreur: ' . $e->getMessage());
	}
	
	$query = $sql->query('SELECT * FROM configuration');
	$config = $query->fetch();
	$query->closeCursor();
	
?>