<?
  session_start();
  if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
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

  $budget_list = $connection->prepare('SELECT p.name, pg.id FROM BUD_PARAM AS p INNER JOIN BUD_PARAM_GROUP AS pg ON p.ID = pg.fk_bud_param WHERE pg.fk_budget = :name');
  $budget_list->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $budget_list->execute();
  ?>
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
