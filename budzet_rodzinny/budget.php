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
<link rel="stylesheet" href="css/font-awesome.min.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/datatables.css"/>


​
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
​
<!-- Custom styles for this template -->
<link href="css/starter-template.css" rel="stylesheet">
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

  $budget_list = $connection->prepare('SELECT p.id, p.name, pg.plan_value, pg.ispaid FROM BUD_PARAM AS p INNER JOIN BUD_PARAM_GROUP AS pg ON p.ID = pg.fk_bud_param WHERE pg.fk_budget = :name');
  $budget_list->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $budget_list->execute();

  $budget_list2 = $connection->prepare('SELECT SUM(p.value) FROM BUD_PARAM_VALUE_GROUP AS p INNER JOIN BUD_PARAM_GROUP AS pg ON p.fk_bud_param_group = pg.id WHERE fk_budget = :name GROUP BY p.fk_bud_param_group');
  $budget_list2->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $budget_list2->execute();

  $budget_name = $connection->prepare('SELECT month_name FROM BUDGET WHERE id = :id');
  $budget_name->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_name->execute();

  $budget_sum = $connection -> prepare('SELECT SUM(plan_value) FROM BUD_PARAM_GROUP WHERE fk_budget = :id');
  $budget_sum->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_sum->execute();

  $budget_sum2 = $connection -> prepare('SELECT SUM(p.value) FROM BUD_PARAM_VALUE_GROUP AS p INNER JOIN BUD_PARAM_GROUP AS pg ON p.fk_bud_param_group = pg.id WHERE fk_budget = :name');
  $budget_sum2->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $budget_sum2->execute();

  $budget_reward = $connection -> prepare('SELECT income_value FROM BUD_INCOME_PARAM WHERE (fk_budget = :id)');
  $budget_reward->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_reward->execute();

  $budget_reward2 = $connection -> prepare('SELECT income_value FROM BUD_INCOME_PARAM WHERE (fk_budget = :id)');
  $budget_reward2->bindValue(':id', $highest_budget_id, PDO::PARAM_INT);
  $budget_reward2->execute();


  ?>
<div class="container">
  <a href="index.php?action=logout">Wyloguj</a>
  <div class="jumbotron">
    <h2>Budżet rodzinny</h1>
    <p>za okres: <u> <? $name = $budget_name -> fetch(); echo $name[0]; ?></u></p>
    <div class="budget_info">
    <p>Wynagrodzenie: <strong><? $reward = $budget_reward ->fetch(); echo $reward[0];?> <span> zł</span></strong></p>
    <p>Stan bieżący: <strong><? $reward2 = $budget_reward2 ->fetch(); $sum2 = $budget_sum2 -> fetch(); echo $reward[0] - $sum2[0];?><span> zł</span></strong></p>
    <p>Ile procent budżetu wykorzystano: <strong><? echo round((($sum2[0]/$reward[0])*100),2);?><span>%</span></strong></p>
    <p class="text-success">Oszędności: <strong><? $reward = $budget_reward ->fetch(); echo $reward[0];?> <span> zł</span></strong></p>
    <p>w tym, pożyczyliśmy: <strong><? $reward = $budget_reward ->fetch(); echo $reward[0];?> <span> zł</span></strong></p>

  </div>
  </div>
    <a href= "dodaj.php" class="btn btn-success" role="button">Dodaj nowy wydatek</a>
    <a href= "create.php" class="btn btn-success" role="button">Dodaj nowy budżet</a>
    <a href= "income.php" class="btn btn-success" role="button">Dodaj nowe wpływy</a>
      <table class="table table-striped table-bordered" id="example">
        <thead>
          <tr>
            <th>Składnik</th>
            <th>Stan planowy (w zł)</th>
            <th>Stan obecny (w zł)</th>
              <th>Szczegóły</th>
            <th>Czy przekroczone?</th>
            <th>Czy opłacone?</th>
            <th>Akcje</th>
          </tr>
        </thead>

        <?php
        foreach($budget_list as $row1){
        ?>
        <tr>
<td><?= $row1['name']; ?> </td>

<td>
  <p><?= $row1['plan_value']; ?></p>
</td>

<td>
    <p><?$sum8 = $budget_list2 -> fetch(); echo $sum8[0];?></p>
</td>
<td><a href="index4.php?pid=<?= $row1['id']; ?>"><i class="fa fa-search-plus" aria-hidden ="true"></i></a></td>
<td><? if ($row1['plan_value'] < $sum8[0]) { echo '<div class="alert alert-danger">Przekroczone!</div>';} else {echo '<div class="alert alert-success">OK!</div>';}
?>
</td>
<td><p><? if ($row1['ispaid'] == 'Tak') {echo '<div class="alert alert-info">Tak!</div>';} else { echo 'Nie';} ?></p>
</td>
<td>
  <a href="ispaid.php?pid=<?= $row1['id']; ?>"<i class="fa fa-pencil" aria-hidden="true"></i><a/>
</td>

</tr>
<?php
}
?>

<tfoot><tr><th><strong>SUMA</strong></th><th><strong><? $sum = $budget_sum -> fetch(); echo $sum[0]; ?><span> zł</span></strong></th><th><strong><? echo $sum2[0]; ?><span> zł</span></strong></th><th>
</th>
<th>
</th>
<th>
</th>
<th></th></tr><tfoot>
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
<script type="text/javascript" src="js/datatables.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.pl.min.js"></script>
<script>$(document).ready( function () {
    $('#example').DataTable();
} );</script>

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
