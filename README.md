# TP1_INFO834 ( Malacarne Etienne )
Le but de ce TP est de prendre en main Redis .

## Création de la BDD MySQL,Redis et des fichiers de base

Pour la BDD MySQL, j'ai crée une seule table utilisateur, avec ces champs : nom,prénom,email,mdp. Pour redis,il suffit juste de l'installer.

Suite à cela j'ai crée la base du site web en ajoutant les fichiers PHP demandés ,avec un peu de CSS.

On va retrouver une page d'accueil, où l'on peut clicker pour se connecter, ainsi que d'autre pages qui seront détaillés ci-dessous.

## Système de connection et gestion de l'accés à Redis

Une fois la base créee, il faut s'occuper du système de connection, qui va se découper en deux parties, un coté PHP, et un autre coté Python. Il faut aussi prendre en compte que l'on veut que l'utilisateur puisse, au maximum, se connecter 10 fois en 10 minutes. S'il attend ce palier, il devrat attendre 10 min avant de pouvoir se re-connecter. Enfin, une fois connecté, l'utilisateur doit etre redirigé vers la page service.


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
Enfin, je met dans la session l'id de l'utilisateur, je vérifie si la connection est autorisée, et suivant le résultat,je redirige vers la page service.
```
$_SESSION["id"]=$username;
var_dump($shelloutput);
 if(strpos($shelloutput,"not")!="false"){
   echo $shelloutput;
}else {
   header('location: services.php');
}
       
header('location: services.php');
```

### Coté python
Je commence par me connecter à redis avec cette ligne : 
```
r=red.Redis(host="IP",port=6379)
```
Ensuite , je récupere l'argument que j'ai mis dans mon code PHP et j'essaie de récupérer la liste ( c'est ce que j'utiliserais ici ) qui lui correspond dans Redis. S'il n'y a rien , j'ajoute une nouvelle liste avec, dedans, l'identifiant ainsi que la date d'ajout.
```
id = sys.argv[1]
results = r.lrange(f"conn:{id}", 0, 9)
if(results==[]):
    r.lpush(f"conn:{id}", time.time())
```
Si la liste existe déja, je vais faire en sorte de récuperer les 10 dernieres connection et de trouver la plus récente avec sa date d'insertion. Si celle ci remonte à il y a moins de 10 minutes et que que j'ai bien une liste de 10 élements, j'empeche l'insertion, et donc la connection .
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

## Stocker dans la bdd 1 de Redis les appels à un service

Ici, on va retrouver le meme fonctionnement que pour la partie précédente.

### Partie PHP

Je crée deux formulaires qui ont uniquement un bouton de validation.

Puis, quand j'appuis sur un de ces deux boutons , j'appele un script python qui va s'occuper d'ajouter l'appel au service à Redis. Je lui passe en argument l'id de l'utilisateur et le nom du service.

```
if (isset($_POST['form1'])) {
 
  $cmd = "C:\Users\\etien\AppData\Local\Programs\Python\Python310\python.exe main2.py '".$_SESSION["id"]."' 'Vente' ";
        
  $command = escapeshellcmd($cmd);

  $shelloutput = shell_exec($command);

  echo "Vente effectuer";
  #header('location: services.php');
}
```

### Partie Python

Je récupère les deux arguments et je push une liste contenant ces deux arguments ainsi que la date d'ajout.

```
id=sys.argv[1]
type_service=sys.argv[2]

r.lpush(f"id:{id} type:{type_service}", time.time())
```

## Des statistiques 
Je n'ai pas eu le temps de réellement faire cette partie, je n'ai fais que le début. 

Je cherche à afficher le top 10 des utilisateurs qui ont le plus de connection.

Dans une page stats.php ( accessible en la mettant dans l'url ) , je veux afficher cette statistique. J'appelle une nouvelle fois un script python.

Dans ce script, je récupere toutes les listes présente dans la bdd 0 de Redis. Je trie ma liste de liste par ordre décroissant sur le nombre de connection, puis je construis une liste du top 10.

```
tab_res=[]
for x in r.keys("conn:*"):

    tab_res.append([x,r.llen(x)])

tab_res.sort(key=lambda x: x[1], reverse=True)
tab_res2=[]

if(len(tab_res)<10):

    for x in range(len(tab_res)):
        tab_res2.append([tab_res[x][0],tab_res[x][1]])
        
else:
    for x in range(10):
        tab_res2.append([tab_res[x][0],tab_res[x][1]])
        
```
Ensuite, je devrais récuper ce résultat dans mon code PHP et m'occuper de l'affichage. Je me suis arreté juste avant l'affichage .

