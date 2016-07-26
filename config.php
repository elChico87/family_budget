  <?php
  $connection = new PDO('mysql:dbname=;host=localhost;port=3306', 'user', 'pass', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $stmt = $connection->prepare("SELECT id FROM BUDGET ORDER BY id DESC", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_FIRST);
  $highest_budget_id = $row[0];
  $stmt ->closeCursor();
  ?>
