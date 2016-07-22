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
  $stmt = $connection->query('SELECT * FROM BUD_PARAM');

  ?>
<div class="container">
    <div class="starter-template">
        <h1>Budżet rodzinny - v0.1</h1>
        <p class="lead">Dodawanie nowego budżetu</p>
    </div>
    <form class="form-horizontal" role="form" action="index.php" method="post">
        <div class="form-group">
            <label class="control-label col-sm-2" for="budget_date">Wybierz miesiąc:</label>
            <div class="col-sm-3">
                <div class="input-group date datepicker" data-provide="datepicker">
                    <input type="text" class="form-control" name="budget_date">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped">
	<thead>
		<tr>
			<th>Składnik</th>
			<th>Czy wybrany?</th>
			<th>Stan planowy w zł</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($stmt as $row){
	?>
	<tr>
		<td><?= $row['name']; ?></td>
		<td>
			<div class="checkbox">
				<label>
					<input type="checkbox" value="<?= $row['id']; ?>" name="checkbox[]">
				</label>
			</div>
		</td>
		<td>
			<input type="text" class="form-control" name = "input[]" id="usr" size="2">
		</td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
​
<?php
$stmt->closeCursor();
?>
<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Utwórz</button>
            </div>
        </div>
    </form>


<?php
if(isset($_POST['budget_date'])){
    $budget_date = $_POST['budget_date'] ?: null;
    $date_create = new DateTime();
    $date = DateTime::createFromFormat('m/Y', $budget_date);

    $miesiac = (int)$date->format('n');
    $rok = (int)$date->format('Y');
    $month_id = $rok*12+$miesiac;

    $ins = $connection->prepare('SELECT * FROM BUDGET WHERE month_id = :month_id');
    $ins->bindValue(':month_id', $month_id, PDO::PARAM_INT);
    $ins->execute();
    $result = $ins->fetch();

    if($result === false){
        $ins = $connection->prepare('INSERT INTO BUDGET (month_id, month_name, month_start_date, month_end_date, date_create, fk_user_create)
          VALUES (:month_id, :month_name, :month_start_date, :month_end_date, NOW(), :fk_user_create)');

        $ins->bindValue(':month_id', $month_id, PDO::PARAM_INT);
        $ins->bindValue(':month_name', $budget_date, PDO::PARAM_STR);
        $ins->bindValue(':month_start_date', $date->format('Y-m-01'), PDO::PARAM_STR);
        $ins->bindValue(':month_end_date', $date->format('Y-m-t'), PDO::PARAM_STR);
        $ins->bindValue(':fk_user_create', 1, PDO::PARAM_INT);

        $ins->execute();
        $lastbudget = $connection->lastInsertID();

        echo "Rekord został dodany poprawnie. ";

        $tablica_checkboxow = isset($_POST['checkbox']) && is_array($_POST['checkbox']) ? $_POST['checkbox']  : array();
        $tablica_inputow = isset($_POST['input']) && is_array($_POST['input']) ? $_POST['input']  : array();
        $chkbox = $connection->prepare('INSERT INTO BUD_PARAM_VALUE (fk_budget, fk_bud_param, plan_value, value) VALUES (:fk_budget, :fk_bud_param, :plan_value, :value)');
       foreach ($tablica_checkboxow as $index => $fk_bud_param)
          {
          $chkbox->bindValue(':fk_budget', $lastbudget , PDO::PARAM_INT);
          $chkbox->bindValue(':fk_bud_param', $fk_bud_param, PDO::PARAM_INT);
          $chkbox->bindValue(':plan_value', $tablica_inputow[$index], PDO::PARAM_INT);
          $chkbox->bindValue(':value', 0.00 , PDO::PARAM_INT);

          $chkbox->execute();
        }

    } else {
        echo ('Wybrany budżet jest już na liście, spróbuj ponownie!');
    }
    ?>

​
<?php

    $connection = null; //mysql_close($connection);
}

?>

<a href="index.php">Przejdź do budżetu</a>
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
