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
  <link rel="stylesheet" type="text/css" href="css/datatables.css"/>
  <link href="css/starter-template.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="js/datatables.js"></script>
</head>
<body>
  <script>$(document).ready( function () {
      $('#example').DataTable();
  } );
  </script>
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
  <div class="container-fluid">
  <div class="jumbotron col-sm-offset-2 col-sm-8">
    <p>Dodawanie nowego wydatku</p>
  </div>
  <form class="form-horizontal col-sm-offset-2 col-sm-8" role="form" action="<?echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
    <div class="form-group">
      <div class="col-sm-3">
        <button type="submit" class="btn btn-success">Zapisz</button>
      </div>
    </div>
    <table class="table table-striped" id="example">
	       <thead>
           <tr>
             <th>Składnik</th>
             <th>Kwota (w zł)</th>
             <th>Opis</th>
           </tr>
         </thead>
         <tbody>
  <?php
	foreach($budget_list as $row){
	?>
	        <tr>
            <td><?= $row['name']; ?></td>
            <td><input type="text" class="form-control" name = "input[<?= $row['id']; ?>]" size="8">
            </td>
            <td><input type="text" class="form-control" name = "description[<?= $row['id']; ?>]" size="60">
          </tr>
	<?php
	}
	?>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
          </tr>
        </tfoot>
      </tbody>
    </table>
  </form>
  </div>
</div>
<?php
      $budget_list->closeCursor();
      if ($_SERVER['REQUEST_METHOD'] =="POST"){
      $input_arr = isset($_POST['input']) && is_array($_POST['input']) ? $_POST['input']  : array();
      $description_arr = isset($_POST['description']) && is_array($_POST['description']) ? $_POST['description'] : array();
      $stmt = $connection->prepare("INSERT INTO BUD_PARAM_VALUE_GROUP (fk_bud_param_group, value, value_description, date_create, fk_person_create) VALUES (:fk_bud_param_group, :value, :value_description, NOW(), :fk_person_create)");
        foreach ($input_arr as $index => $value){
            if ($value > 0){
              $stmt->bindValue(':fk_bud_param_group', $index , PDO::PARAM_INT);
              $stmt->bindValue(':value', $value, PDO::PARAM_INT);
              $stmt->bindValue(':value_description',  $description_arr[$index], PDO::PARAM_STR);
              $stmt->bindValue(':fk_person_create', $_SESSION['id'], PDO::PARAM_INT);
              $stmt->execute();
            }
          }
        $stmt = closeCursor();
        }
      $connection = null; //mysql_close($connection);
?>


<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>

<script src="js/bootstrap.min.js"></script>



</body>
​
</html>
