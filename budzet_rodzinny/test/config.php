<?

try {
	$connection = new PDO('mysql: host=localhost;dbname=kamjol_test;port=3306','kamjol_test','Tester123');
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
	die('Brak polaczenia z baza danych');
}


/* Dodawanie do bazy za pomocą PDO

$name = 'test';
$description = 'test';

$sql = $connection->prepare("INSERT INTO BUD_PARAM(name, description) VALUES (:name, :description)");
$sql->bindValue(':name',$name, PDO::PARAM_STR);
$sql->bindValue(':description', $description, PDO::PARAM_INT);
$sql->execute();

*/

/* Proste wyszukianie

$sql = $connection->query('Select * FROM BUDGET');
$r = $sql->fetch();

*/

/* Wyszukiwanie za pomocą PDO
$id = 10;
$stmt = $connection->prepare('SELECT * FROM BUD_PARAM WHERE id > :id');
$stmt->bindParam(':id',$id);
$stmt->execute();
$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<pre>', print_r($r),'</pre>';
echo $connection->lastInsertID();*/


$aid = $connection->prepare("SELECT id FROM BUDGET ORDER BY id DESC", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $aid->execute();
  $row = $aid->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_FIRST);
  $highest_budget_id = $row[0];

var_dump($row);

/* Sprawdza ile jest kolumn w wynikach zapytania
$colcount = $stmt->columnCount();
echo $colcount;

/* Dump
$stmt->debugDumpParams();
*/

//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Zamyka kursor aby kolejny execute() mógł być wykonany, zwalnia zmienną

$aid->closeCursor();

 

