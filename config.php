  <?php
  try {
    $connection = new PDO('mysql:dbname=;host=localhost;port=3306', 'user', 'pass', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    die('Brak połączenia z bazą danych!');
  }
  $stmt = $connection->query("SELECT id FROM BUDGET ORDER BY id DESC");
  $row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_FIRST);
  $highest_budget_id = $row[0];
  $stmt ->closeCursor();
  ?>
