<?
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged']= true){
  header('Location: index.php');
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
<title>Budżet rodzinny || Logowanie</title>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-datepicker.css" rel="stylesheet">
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
</head>
<body>
  <div class="container-fluid login_page">
    <h1 class = "col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 login_page--title">Budżet rodzinny</h1>
    <div id="loginbox" class="mainbox col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
      <div class="panel panel-info" >
        <div class="panel-heading">
          <div class="panel-title">Logowanie</div>
        </div>
        <div class="panel-body" >
          <form id="loginform" class="form-horizontal" role="form" method = "POST" action="auth.php">
            <div style="margin-bottom: 25px" class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="Adres e-mail">
            </div>
            <div style="margin-bottom: 25px" class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input id="login-password" type="password" class="form-control" name="password" placeholder="Hasło">
            </div>
            <?
            if (isset($_SESSION['error'])){
            echo "<p class='alert alert-danger'>". $_SESSION['error']."</p>";
            }
            ?>
            <div style="margin-top:10px" class="form-group">
              <div class="col-sm-12 col-sm-offset-10 controls">
                <button type="submit" class="btn btn-success">Zaloguj</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <small class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 center">Copyright 2016 &copy; Piotr Bielawski. Wszelkie prawa zastrzeżone.</small>
  </div>
</body>
</html>
