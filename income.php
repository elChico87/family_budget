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
  <title>Budżet rodzinny | Zarządznie wpływami</title>
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
  $income_list = $connection->prepare('SELECT p.income_name, pg.id, pg.income_value FROM BUD_INCOME AS p INNER JOIN BUD_INCOME_PARAM AS pg ON p.ID = pg.fk_bud_income WHERE pg.fk_budget = :name', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $income_list->bindValue(':name', $highest_budget_id, PDO::PARAM_INT);
  $income_list->execute();
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
    <p>Zarządzanie wpływami</p>
  </div>
  <form class="form-horizontal col-sm-offset-2 col-sm-8" role="form" action="<?echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
    <div class="form-group">
      <div class="col-sm-3">
        <button type="submit" class="btn btn-success">Zapisz</button>
      </div>
    </div>
    <table class="table table-striped table-bordered" id="example">
	       <thead>
           <tr>
             <th>Składnik</th>
             <th>Kwota (w zł)</th>
           </tr>
         </thead>
         <tbody>
  <?php
	   foreach($income_list as $row){
	?>
	        <tr>
            <td><?= $row['income_name']; ?></td>
            <td><input type="text" class="form-control" name = "income[<?= $row['id']; ?>]" size="8" value = "<? echo $row['income_value']?>">
            </td>
          </tr>
	<?php
	}
  $income_list->closeCursor();
	?>
        <tfoot>
          <tr>
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

      if ($_SERVER['REQUEST_METHOD'] =="POST"){
      $income_arr = isset($_POST['income']) && is_array($_POST['income']) ? $_POST['income']  : array();
      $stmt = $connection->prepare("UPDATE BUD_INCOME_PARAM SET income_value =:income_value WHERE id =:id");

          foreach ($income_arr as $index => $value){
              $stmt->bindValue(':income_value', $value, PDO::PARAM_INT);
              $stmt->bindValue(':id', $index, PDO::PARAM_INT);
              $stmt->execute();
            }

          }

      $connection = null; //mysql_close($connection);

?>


<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>

<script src="js/bootstrap.min.js"></script>



</body>
​
</html>
