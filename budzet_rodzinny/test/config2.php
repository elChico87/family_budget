<?
try {
$conn = new PDO('mysql: host=localhost; dbname=kamjol_test; port:3306', 'kamjol_test', 'Tester123');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
	die('Brak połaczenia z baza danych'. $e->getMessage());
}

echo 'Zadanie zostalo wykonane pomyslnie';
$conn = null;
?>
