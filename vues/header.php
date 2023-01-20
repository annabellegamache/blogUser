
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./vues/assets/css/styles.css">
            <title><?php if(isset($donnees["titre"])) echo $donnees["titre"];?></title>
    </head>
    <body>
        <div class="container bgBlanc">
        <nav>
            <!--  Accueil de l'usager authentifier-->
            <?php
                if(isset($_SESSION["usager"]))
                echo '<h2>Bonjour '. $_SESSION["prenom"].' !</h2>';
            ?>
            <a href="index.php?commande=ListeArticle"><img src="./vues/assets/img/home.svg" alt="icone-accueil">Accueil</a>
            <!-- ****** href change et texte du bouton selon état de la session -->
            <?php
                if(isset($_SESSION["usager"]))
                {     
            ?>
                    <a href="index.php?commande=Logout"><img src="./vues/assets/img/login.svg" alt="icone-login">Déconnexion</a>
            <?php        
                }
                else
                {
            ?>        
                    <a href="index.php?commande=FormLogin"><img src="./vues/assets/img/login.svg" alt="icone-login">Connexion</a>
            <?php
                }
            ?>    
        </nav>
        <section class="interaction">
            <form method="POST" action="index.php">
                <label for="recherche">Recherche d'un article :</label>
                <input type="text" name="recherche"/>
                <input type="hidden" name="commande" value="RechercheArticle"/>
                <button type="submit">Rechercher</button>
            </form>
            <!--  bouton ajouter un article si utilisateur est authentifier-->
            <?php
                if(isset($_SESSION["usager"]))
                {
            ?>
                    <div>
                        <a href="index.php?commande=FormAjoutArticle"> + Ajouter un article</a>
                    </div>
            <?php        
                }
            ?> <!--  FIN bouton ajouter -->
        </section>


   