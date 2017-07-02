<?
	try {
		$connection = new PDO('mysql:host=localhost;dbname=kamjol_test;port:3306','kamjol_test1','Tester123');
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		die('Sorry, datebase problem');
	}
   

?>