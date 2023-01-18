# TP1_INFO834
Le but de ce TP est de prendre en main redis .

## Création de la BDD MySQL et des fichiers de base

Pour la BDD MySQL, j'ai crée une seule table utilisateur, avec ces champs : nom,prénom,email,mdp.

Suite à cela j'ai crée la base du site web en ajoutant les fichiers PHP demandés ,avec un peu de CSS.

On va retrouver une page d'accueil, ou l'on peut clikcer pour se connecter, ainsi que d'autre pages qui seront détaillés ci-dessous.

## Système de connection et gestion de l'accés à Redis

Une fois la base créee, il faut s'occuper du système de connection, qui va se découper en deux parties, une coté PHP, et une autre coté Python. Il faut aussi prendre en compte que l'on veut que l'utilisateur puisse, au maximum, se connecter 10 fois en 10 minutes. S'il attend ce palier, il devrat attendre 10min avant de pouvoir se re-connecter. Enfin, une fois connecter, l'utilisateur doit etre redirigé vers la page service.

### Coté PHP

Je commencer par vérifier si l'utilisateur existe dans la base de donnée, et que l'identifiant et le mot de passe soit correcte.
```
 $db = mysqli_connect("localhost", "root", "", "tp1_info834");

    // Récupération des données du formulaire
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Vérification de l'existence de l'utilisateur dans la base de données
    
    $query = "SELECT * FROM utilisateurs WHERE email='$username' AND mdp='$password'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1)
```

Puis je vais executer un script python en lui passant en parametre l'identifiant.
```
$cmd = "C:\chemin\python.exe main.py '".$username."' ";
        
$command = escapeshellcmd($cmd);
      
$shelloutput = shell_exec($command);
```
Enfin, je met dans la session l'id de l'utilisateur et je redirige vers la page service.
```
$_SESSION["id"]=$username;
var_dump($shelloutput);
       
header('location: services.php');
```
