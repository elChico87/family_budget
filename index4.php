<?
  session_start();
  if (!isset($_SESSION['logged'])) {
    header('Location: login.phtml');
    exit();
  }
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../../favicon.ico">
​
<title>Budżet rodzinny</title>
​
<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-datepicker.css" rel="stylesheet">
​
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
​
<!-- Custom styles for this template -->
<link href="css/starter-template.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
​
<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]>
<script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation-modes-warning.js"></script>
​
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
​
<body>
  <?php
  $connection = new PDO('mysql:dbname=kamjol_test;host=localhost;port=3306', 'kamjol_test', 'Tester123', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $aid = $connection->prepare("SELECT id FROM BUDGET ORDER BY id DESC LIMIT 5", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $aid->execute();
  $row = $aid->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_FIRST);
  $highest_budget_id = $row[0];

  $fkbudparam = $_SERVER['REQUEST_URI'];
  $fkbudparam2 = parse_url($fkbudparam, PHP_URL_QUERY);
  $fkbudparam3 = substr($fkbudparam2,4);

  $budget_list = $connection->prepare('SELECT p.id, p.value, p.value_description, p.date_create FROM BUD_PARAM_VALUE_GROUP AS p INNER JOIN BUD_PARAM_GROUP AS pg ON p.fk_bud_param_group = pg.ID WHERE (pg.fk_budget = :name) AND (pg.fk_bud_param = :fk_bud_param)');
  //$budget_list = $connection->prepare('SELECT id, value FROM BUD_PARAM_VALUE_GROUP WHERE fk_bud_param_group = :name');
  $budget_list->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $budget_list->bindValue(':fk_bud_param', $fkbudparam3, PDO::PARAM_INT);
  $budget_list->execute();

  $budget_name = $connection->prepare('SELECT month_name FROM BUDGET WHERE id = :id');
  $budget_name->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_name->execute();

  $budget_sum2 = $connection -> prepare('SELECT SUM(value) FROM BUD_PARAM_VALUE WHERE fk_budget = :id');
  $budget_sum2->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_sum2->execute();

  $budget_reward = $connection -> prepare('SELECT income_value FROM BUD_INCOME_PARAM WHERE (fk_budget = :id)');
  $budget_reward->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_reward->execute();

  $budget_reward2 = $connection -> prepare('SELECT income_value FROM BUD_INCOME_PARAM WHERE (fk_budget = :id)');
  $budget_reward2->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_reward2->execute();


  ?>
<div class="container">

    <a href= "index.php" class="btn btn-success" role="button">Wróć</a>
    <p></p>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Kwoty</th>
            <th>Opis</th>
            <th>Data dodania</th>
            <th>Akcje</th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach($budget_list as $row1){
        ?>
        <tr>
<td><?= $row1['id']; ?> </td>
<td>
  <p><?= $row1['value']; ?><span> zł</span></p>
</td>
<td>
  <p><?= $row1['value_description']; ?></p>
</td>
<td><p><?= $row1['date_create']; ?></p></td>
<td><a href="delete.php?vid=<?= $row1['id']; ?>" title = "Usuń"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
</tr>
<?php
}
?>


</tbody>
</table>
​
<?php
$budget_list->closeCursor();

    $connection = null; //mysql_close($connection);

?>


</div>
​
<!-- Bootstrap core JavaScript
 ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.pl.min.js"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script>

    $('.datepicker').datepicker({
        format: 'mm/yyyy',
        minViewMode: 1,
        maxViewMode: 2,
        language: "pl",
        clearBtn: true
    });
</script>
</body>
​
</html>
