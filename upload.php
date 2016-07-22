<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
<body>
<? define("UPLOAD_DIR","/home/kamjol/domains/kamjol.ayz.pl/public_html/budzet/test/");
  if (!empty($_FILES['myFile'])) {
    $my_file = $_FILES['myFile'];
      //Sprawdzanie czy nie wystąpił błąd podczas dodawania plików

      if ($my_file['error'] !== UPLOAD_ERR_OK){
        echo "Wystąpił błąd podczas dodawania pliku. Spróbuj jeszcze raz";
        exit;
      } else {
          //Sprawdzanie czy plik nie zawiera niedozwolonych znaków

          $name = preg_replace("/[^A-Z0-9._-]/i","_",$my_file['name']);

          //Nadpisywanie pliku
          $i = 0;
          $parts = pathinfo($name);
          while (file_exists(UPLOAD_DIR . $name)){
            $i++;
            $name = $parts['filename']."(".$i.")".".".$parts['extension'];
          }

          //Przeniesienie pliku z tmp do folderu docelowego
          $success = move_uploaded_file($my_file['tmp_name'], UPLOAD_DIR.$name);

          if (!$success){
            echo "<p>Nie mogłem zapisać pliku. Spróbuj jeszcze raz</p>";
          } else {

          echo "<p>Plik <strong>".$my_file['name'] . "</strong> został przesłany prawidłowo!</p>";
          chmod(UPLOAD_DIR . $name, 0755);
        }
      }
      }
      else {
        echo "Nie wybrałeś żadnego pliku!";
      }



?>
</body>
​
</html>
