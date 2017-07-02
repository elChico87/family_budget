<?
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
  <title>Budżet rodzinny | Archwium</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/datatables.css"/>
  <link href="css/starter-template.css" rel="stylesheet">
  <link href="css/font-awesome.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="js/datatables.js"></script>
</head>
<body>
  <?php
  require_once('config.php');
  $pid = $_GET["pid"];
  $stmt = $connection->prepare('UPDATE BUD_PARAM_GROUP SET ispaid = :ispaid where id = :id');
  $stmt->bindValue(':ispaid', "Tak", PDO::PARAM_STR);
  $stmt->bindValue(':id', $pid, PDO::PARAM_INT);
  $stmt->execute();
  echo 'Zmienono status opłacenia';
  ?>

<div class="container">
    <a href= "javascript:history.go(-1);" class="btn btn-success" role="button">Wróć</a>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.pl.min.js"></script>
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
</html>
