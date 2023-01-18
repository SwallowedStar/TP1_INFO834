<!DOCTYPE html>
<html>
<head>
  <title>Page PHP avec formulaires</title>
  <link rel="stylesheet" type="text/css" href="services.css">
</head>
<body>



<div>

    <p>Les 10 derniers utilisateur connectés : </p>
    <?php affiche_top_ten() ?>
</div>


<?php
session_start();

function affiche_top_ten(){
    $cmd = "C:\Users\\etien\AppData\Local\Programs\Python\Python310\python.exe main3.py ";
    $command = escapeshellcmd($cmd);
      
    $shelloutput = shell_exec($command);
    var_dump($shelloutput);


}
?>

</body>
</html>