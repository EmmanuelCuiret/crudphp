<?php

require_once "config.php";

try {
	// We create a new instance of the class PDO
	$db = new PDO("mysql:host=". $config['HOST'] .";dbname=".$config['DB'].";port=".$config['PORT'], $config['LOGIN'], $config['PASSWORD']);
 	//We want any issues to throw an exception with details, instead of a silence or a simple warning
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e) {
	echo $e->getMessage();
}