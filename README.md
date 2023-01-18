# TP1_INFO834
Le but de ce TP est de prendre en main redis .

## Création de la BDD MySQL,Redis et des fichiers de base

Pour la BDD MySQL, j'ai crée une seule table utilisateur, avec ces champs : nom,prénom,email,mdp. Pour redis,il suffit juste de l'installer.

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

Puis je vais executer un script python en lui passant en parametre l'identifiant, qui va s'occuper de gerer la connection à Redis et de valider ou non  la connection .
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

### Coté python
Je commence par me connecter à redis avec cette ligne : 
```
r=red.Redis(host="IP",port=6379)
```
Ensuite , je récupere l'argument que j'ai mis dans mon code PHP et j'essaie de récupérer la liste ( c'est ce que j'utiliserais ici ) qui lui correspond dans Redis. S'il n'y a rien , j'ajoute une nouvelle liste avec dedans, l'identifiant ainsi que la date d'ajout.
```
id = sys.argv[1]
results = r.lrange(f"conn:{id}", 0, 9)
if(results==[]):
    r.lpush(f"conn:{id}", time.time())
```
Si la liste existe déja, je vais faire en sorte de récuperer les 10 dernieres connection et de trouver la plus récente avec  sa date d'insertion. Si celle remonte à il y a moins de 10 minutes et que que j'ai bien une liste de 10 élements, j'empeche l'insertion, et donc la connection .
```
 #print(results)
    connection_times = []
    for re in results:
        connection_times.append(datetime.datetime.fromtimestamp(float(re.decode("utf-8"))))

    last_time = connection_times[-1]
    #print(connection_times)
    current_time = datetime.datetime.now()

    check = current_time - last_time
    #print("Last time:", last_time, "now:", current_time, "delta:", check)
    #print(datetime.timedelta(minutes=10))
    if check <= datetime.timedelta(minutes=10) and len(results)==10:
        print("{id} not authorized to connect. Time to 10th connection :", check)
        
```
Sinon, je peux inserer dans Redis et autoriser la connection.
```
   else:
        r.lpush(f"conn:{id}", time.time())
        print("{id} authorized to connect. Time to 10th connection :", check)
```





