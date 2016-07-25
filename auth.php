<?
  session_start();

  if ((!isset($_POST['username']))||(!isset($_POST['password']))){
    header('Location: login.phtml');
    exit();
  }

  $connection = new PDO('mysql:dbname=kamjol_test;host=localhost;port=3306', 'kamjol_test', 'Tester123', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $login = $_POST['username'];
  $pass = $_POST['password'];

  $stmt = $connection->prepare('SELECT * FROM PERSON WHERE email = :email');
  $stmt->bindValue(':email', $login, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();


  if ($result['id'] > 0) {

    if (password_verify($pass, $result['password'])){
    $_SESSION['logged']= true;
    $_SESSION['id'] = $result['id'];
    $_SESSION['user'] = $result['name'];
    $_SESSION['email'] = $result['email'];
    unset($_SESSION['blad']);
    $stmt ->closeCursor();
    header('Location: index.php');
  } else {
    $_SESSION['error'] = 'Nieprawidłowy login lub hasło.';
    header('Location: login.php');

  } } else {
    $_SESSION['error'] = 'Nieprawidłowy login lub hasło.';
    header('Location: login.php');
  }


  /*var_dump($result);
   if ($result > 0){
     $dane = $stmt-> fetch();

     $user = $dane['email'];
     var_dump($user);
   }else {

   }*/
  $connection = null;
/*  session_start();
  if (isset($_GET['action']) && $_GET['action'] == 'logout'){
    unset($_SESSION['zalogowany']);
  }
  if (isset($_POST['password']) && $_POST['password'] =='tajne')
  {
    $_SESSION['zalogowany'] = 1;
  }
  if (!isset($_SESSION['zalogowany'])){

 } else {
  include 'budget.php';
}*/
?>
