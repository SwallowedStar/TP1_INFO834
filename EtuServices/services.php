<!DOCTYPE html>
<html>
<head>
  <title>Page PHP avec formulaires</title>
  <link rel="stylesheet" type="text/css" href="services.css">
</head>
<body>

<form action="" method="post">
  <input type="submit" name="form1" value="Service Vente">
</form>

<form action="" method="post">
  <input type="submit" name="form2" value="Service Achat">
</form>

<?php
session_start();
if (isset($_POST['form1'])) {
 
  $cmd = "C:\Users\\etien\AppData\Local\Programs\Python\Python310\python.exe main2.py '".$_SESSION["id"]."' 'Vente' ";
        
  $command = escapeshellcmd($cmd);

  $shelloutput = shell_exec($command);

  echo "Vente effectuer";
  #header('location: services.php');
}

if (isset($_POST['form2'])) {
    $cmd = "C:\Users\\etien\AppData\Local\Programs\Python\Python310\python.exe main2.py '".$_SESSION["id"]."' 'Achat' ";
        
    $command = escapeshellcmd($cmd);
  
    $shelloutput = shell_exec($command);

 
    #header('location: services.php');
    echo "Achat effectuer ";
  
}
?>

</body>
</html>