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
  <title>Budżet rodzinny | Nowy wydatek</title>
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
  require_once('config.php');

  $budget_list = $connection->prepare('SELECT p.name, pg.id FROM BUD_PARAM AS p INNER JOIN BUD_PARAM_GROUP AS pg ON p.ID = pg.fk_bud_param WHERE pg.fk_budget = :name');
  $budget_list->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $budget_list->execute();
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
    <h2>Budżet rodzinny</h1>
    <p>Dodawanie nowego wydatku</p>
  </div>
    <form class="form-horizontal" role="form" action="dodaj.php" method="post">
      <table class="table table-striped">
	    <thead>
		<tr>
			<th>Składnik</th>
			<th>Kwota</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($budget_list as $row){
	?>
	<tr>
		<td><?= $row['name']; ?></td>
		<td>
			<input type="text" class="form-control" name = "input[<?= $row['id']; ?>]" size="2">
		</td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
​
<?php
$budget_list->closeCursor();
?>
<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Dodaj</button>
            </div>
        </div>
    </form>
    <a href= "index.php" class="btn btn-success" role="button">Wróć do budżetu</a>


<?php
        $tablica_inputow = isset($_POST['input']) && is_array($_POST['input']) ? $_POST['input']  : array();
        $chkbox = $connection->prepare("INSERT INTO BUD_PARAM_VALUE_GROUP (fk_bud_param_group, value, value_description, date_create) VALUES (:fk_bud_param_group, :value, :value_description, NOW())");


        foreach ($tablica_inputow as $fkBudParam => $value)
          {
            if ($value > 0){
          $chkbox->bindValue(':fk_bud_param_group', $fkBudParam , PDO::PARAM_INT);
          $chkbox->bindValue(':value', $value, PDO::PARAM_INT);
          $chkbox->bindValue(':value_description',  "", PDO::PARAM_STR);

          $chkbox->execute();
        }
      }


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
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.pl.min.js"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
​
</html>
