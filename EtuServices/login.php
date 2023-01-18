<?php
// Démarrage de la session
session_start();

// Vérification de la soumission du formulaire
if(isset($_POST['submit'])) {
    // Connexion à la base de données
    $db = mysqli_connect("localhost", "root", "", "tp1_info834");

    // Récupération des données du formulaire
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Vérification de l'existence de l'utilisateur dans la base de données
    
    $query = "SELECT * FROM utilisateurs WHERE email='$username' AND mdp='$password'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1) {
        // Stockage de l'utilisateur dans la session
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Vous êtes maintenant connecté";
        #header('location: services.php');
        $cmd = "C:\Users\\etien\AppData\Local\Programs\Python\Python310\python.exe main.py '".$username."' ";
        
        $command = escapeshellcmd($cmd);
      
        $shelloutput = shell_exec($command);

        var_dump($shelloutput);
        
        
    } else {
        echo "ERROR";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Connexion</title>
  <link rel="stylesheet" href="login.css" media="screen">

</head>
<body>
  <div class="header">
    <h2>Connexion</h2>
  </div>

  <form method="post" action="login.php">
   
    <div class="input-group">
      <label>Nom d'utilisateur</label>
      <input type="text" name="username" >
    </div>
    <div class="input-group">
      <label>Mot de passe</label>
      <input type="password" name="password">
    </div>
    <div class="input-group">
      <button type="submit" class="btn" name="submit">Connexion</button>
    </div>
   
  </form>
</body>
</html>