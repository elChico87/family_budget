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

  $fkwydatek = $_SERVER['REQUEST_URI'];
  $vid = parse_url($fkwydatek, PHP_URL_QUERY);

  $id = substr($vid,4);

  $budget_list = $connection->prepare('DELETE FROM BUD_PARAM_VALUE_GROUP WHERE id = :id');
  $budget_list->bindValue(':id', $id, PDO::PARAM_INT);
  $budget_list->execute();
  echo "Wydatek został usunięty!"

  ?>
<div class="container">

    <a href= "javascript:history.go(-1);" class="btn btn-success" role="button">Wróć</a>
    <p></p>

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
