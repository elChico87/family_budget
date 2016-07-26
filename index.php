<?
  session_set_cookie_params(300,'/');
  session_start();
  if (!isset($_SESSION['logged'])) {
    header('Location: login.phtml');
    exit();
  }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Budżet rodzinny">
  <meta name="author" content="Pior Bielawski">
  <link rel="icon" href="../../favicon.ico">
  <title>Budżet rodzinny | Strona główna</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/bootstrap-datepicker.css" rel="stylesheet">
  <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/datatables.css"/>
  <link href="css/starter-template.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
</head>
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
  $name = $budget_name -> fetch();
  $name2 = substr($name[0],0,2);
  $date = DateTime::createFromFormat('m/Y', $name[0]);

  $mon_name = $date->format('F');
  $yer_name = $date->format('Y');

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

  $last_update = $connection -> prepare('SELECT p.date_create FROM `BUD_PARAM_VALUE_GROUP` AS p INNER JOIN `BUD_PARAM_GROUP` AS pg ON p.fk_bud_param_group = pg.ID WHERE fk_budget = :fk_budget ORDER BY p.date_create DESC LIMIT 1');
  $last_update->bindValue(':fk_budget', $highest_budget_id, PDO::PARAM_INT);
  $last_update->execute();
  $last_update2 = $last_update->fetch();

  function month_polish($mon_name)

  {

   $mon_name = str_replace('January', 'Styczeń', $mon_name);
   $mon_name = str_replace('February', 'Luty', $mon_name);
   $mon_name = str_replace('March', 'Marzec', $mon_name);
   $mon_name = str_replace('April', 'Kwiecień', $mon_name);
   $mon_name = str_replace('May', 'Maj', $mon_name);
   $mon_name = str_replace('June', 'Czerwiec', $mon_name);
   $mon_name = str_replace('July', 'Lipiec', $mon_name);
   $mon_name = str_replace('August', 'Sierpień', $mon_name);
   $mon_name = str_replace('September', 'Wrzesień', $mon_name);
   $mon_name = str_replace('October', 'Październik', $mon_name);
   $mon_name = str_replace('November', 'Listopad', $mon_name);
   $mon_name = str_replace('December', 'Grudzień', $mon_name);

   return $mon_name;

  }
   $test = month_polish($mon_name);

  ?>
<div class="container-fluid">
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <!-- Grupowanie "marki" i przycisku rozwijania mobilnego menu -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Rozwiń nawigację</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Budżet rodzinny</a>
      </div>

      <!-- Grupowanie elementów menu w celu lepszego wyświetlania na urządzeniach moblinych -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Zalogowany jako: <? echo $_SESSION['user'];?><span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Profil</a></li>
              <li><a href="logout.php">Wyloguj</a></li>
            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->

    </div><!-- /.container-fluid -->
  </nav>

<div class="container">
  <div class="jumbotron">
    <p><u> <? echo  $test." ". $yer_name; ?></u></p>
    <div class="budget_info">
      <p>Wynagrodzenie: <strong><? $reward = $budget_reward ->fetch(); echo $reward[0];?> <span> zł</span></strong></p>
      <p>Stan bieżący: <strong><? $reward2 = $budget_reward2 ->fetch(); $sum2 = $budget_sum2 -> fetch(); echo $reward[0] - $sum2[0];?><span> zł</span></strong></p>
      <p>Ile procent budżetu wykorzystano: <strong><? echo round((($sum2[0]/$reward[0])*100),2);?><span>%</span></strong></p>
      <p class="text-success">Oszędności: <strong><? $reward = $budget_reward ->fetch(); echo $reward[0];?> <span> zł</span></strong></p>
      <p>w tym, pożyczyliśmy: <strong><? $reward = $budget_reward ->fetch(); echo $reward[0];?> <span> zł</span></strong></p>
      <p>Ostatnia aktualizacja: <span><? echo $last_update2[0];?></span> przez: </p>
    </div>
  </div>

    <a href= "add.php" class="btn btn-success" role="button">Dodaj nowy wydatek</a>
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
<tbody>
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
<td><? if ($row1['plan_value'] < $sum8[0]) { echo '<div class="alert alert-danger alert-table">Przekroczone!</div>';} else {echo '<div class="alert alert-success alert-table">OK!</div>';}
?>
</td>
<td><p><? if ($row1['ispaid'] == 'Tak') {echo '<div class="alert alert-info alert-table">Tak!</div>';} else { echo 'Nie';} ?></p>
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
