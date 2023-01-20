<?php
    /*
    */
    //démarrer la session
    session_start();

    //création du tableau $donnees qui sera utilisé aussi dans les vues
    $donnees = array();

    //structure décisionnelle du contrôleur
    if(isset($_REQUEST["commande"]))
    {
        $commande = $_REQUEST["commande"];
    }
    else
    {
        //assigner une commande par défaut -- typiquement la commande qui mène à votre page d'accueil
        $commande = "ListeArticle";
    }

    //inclure le modele
    require_once("modele.php");

    //structure décisionnelle du contrôleur
    switch($commande)
    {
        case "FormLogin":
            $donnees["titre"]= "Login";
            require_once("vues/header.php");
            require_once("vues/FormLogin.php");
            require_once("vues/footer.php");
            break;
        case "ValidationLogin":
            if(isset($_REQUEST["user"], $_REQUEST["pass"]))
            {
                $test = login($_REQUEST["user"], $_REQUEST["pass"]);

                if($test)
                {
                    //combinaison valide
                    $_SESSION["usager"] = $_REQUEST["user"];
                    $_SESSION["idUsager"] = obtenir_id_usager($_REQUEST["user"]);
                    $_SESSION["prenom"] = obtenir_prenom_usager($_REQUEST["user"]);
                    header("Location: index.php");
                }
                else
                {
                    //combinaison invalide
                    $donnees["titre"]= "Login";
                    $messageErreur = "Mauvaise combinaison / Nom d'utilisateur  /  Mot de passe.";
                    require_once("vues/header.php");
                    require_once("vues/FormLogin.php");
                    require_once("vues/footer.php");
                }
            }
            break;  
        case "Logout":
            // Initialisation de la session.
            // Détruit toutes les variables de session
            $_SESSION = array();
            // Effacez le cookie de session.
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            // Destruction de  la session.
            session_destroy();
            //redirection vers la page d'accueil
            header("Location: index.php");
            break;  
        case "ListeArticle":
            //afficher la liste des articles
            $donnees["articles"] = obtenir_articles();
            $donnees["titre"]= "Liste des articles";
            //afficher les vues qu'on veut afficher
            require_once("vues/header.php");
            require_once("vues/ListeArticle.php");
            require_once("vues/footer.php");
            break;
        case "FormAjoutArticle":
            //est-ce que l'usager a le droit d'être ici?
            if(isset($_SESSION["usager"]))
            {
                $donnees["titre"]= "Formulaire ajout";
                //afficher le formulaire d'ajout d'équipe
                require_once("vues/header.php");
                require_once("vues/FormAjoutArticle.php");
                require_once("vues/footer.php");
                break;
            }
            else
                $messageErreur = "Vous n'avez pas les droits";
                header("Location: index.php"); /* pas les droits */   
            break;    
        case "AjoutArticle":
            //est-ce que l'usager a le droit d'être ici?
            if(isset($_SESSION["usager"]))
            {
                if(isset($_REQUEST["titre"]) && isset($_REQUEST["texte"]))
                {
                    if(trim($_REQUEST["titre"]) != "" && trim($_REQUEST["texte"]) != "")
                    {
                        //procéder à l'insertion
                        $test = ajoute_article($_REQUEST["titre"], $_REQUEST["texte"], $_SESSION["idUsager"] );
                        if($test)
                            header("Location: index.php?commande=ListeArticle");    
                    }
                    else
                    {
                        $messageErreur = "Il faut entrer des valeurs dans tous les champs.";
                        if(isset($_REQUEST["titre"])) $titre = $_REQUEST["titre"];
                        if(isset($_REQUEST["texte"])) $texte = $_REQUEST["texte"];
                        $donnees["titre"]= "Formulaire ajout";
                        require_once("vues/header.php");
                        require_once("vues/FormAjoutArticle.php");
                        require_once("vues/footer.php");
                    }
                }
            }  
            else
            {
                $messageErreur = "Vous n'avez pas les droits";
                header("Location: index.php"); /* pas les droits */ 
            }  
            break;
            case "FormModifArticle":
                //est-ce que l'usager a le droit d'être ici?
                if(isset($_SESSION["usager"]))
                {
                    //obtenir l'article dans le modèle
                    $donnees["article"] = obtenir_article_par_id($_REQUEST["id"]);
                    $donnees["titre"]= "Formulaire modification";
                    //afficher le formulaire 
                    require_once("vues/header.php");
                    require_once("vues/FormModifArticle.php");
                    require_once("vues/footer.php");
                    break;
                }
                else
                {
                    $messageErreur = "Vous n'avez pas les droits";
                    header("Location: index.php"); /* pas les droits */ 
                }  
                break;
            case "ModifArticle":
                //est-ce que l'usager a le droit d'être ici?
                if(isset($_SESSION["usager"]))
                {
                    if(isset($_REQUEST["titre"]) && isset($_REQUEST["texte"]))
                    {
                        
                        if(trim($_REQUEST["titre"]) != "" && trim($_REQUEST["texte"]) != "")
                        {
                            //procéder à la modification
                            $test = modif_article($_REQUEST["titre"], $_REQUEST["texte"], $_REQUEST["id"]);
                            if($test)
                                header("Location: index.php?commande=ListeArticle");    
                        }
                        else
                        {
                            $messageErreur= "Il faut entrer des valeurs dans tous les champs.";
                            $donnees["titre"]= "Formulaire modification";
                            /*Envoi les dernières entrer par l'usager de l'article */
                            if(isset($_REQUEST["titre"])) $titre = $_REQUEST["titre"];
                            if(isset($_REQUEST["texte"])) $texte = $_REQUEST["texte"];
                            $donnees["article"] = obtenir_article_par_id($_REQUEST["id"]);
                            require_once("vues/header.php");
                            require_once("vues/FormModifArticle.php");
                            require_once("vues/footer.php");
                        }
                    }
                    
                }  
                else
                {
                    $messageErreur = "Vous n'avez pas les droits";
                    header("Location: index.php"); /* pas les droits */ 
                }  
                break;
            case "SupprimerArticle":
                //est-ce que l'usager a le droit d'être ici?
                if(isset($_SESSION["usager"]))
                {
                   //procéder à la suppression
                    $test = supprime_article($_REQUEST["id"]);
                    if($test)
                        header("Location: index.php?commande=ListeArticle");    
                    else
                    {
                        $donnees["titre"]= "Liste des articles";
                        $donnees["messageErreur"] = "Erreur lors de la suprression";
                        require_once("vues/header.php");
                        require_once("vues/ListeArticle.php");
                        require_once("vues/footer.php");
                    }
                    
                }  
                else
                    header("Location: index.php"); /* pas les droits */    
                break;
            case "RechercheArticle":
                if(isset($_REQUEST["recherche"]))
                {
                    //validation
                    $recherche = trim($_REQUEST["recherche"]); 
                    $test = recherche_article($recherche); // test la requete
                    if($test)
                    {
                        //afficher la ou les vues qu'on veut afficher
                        $donnees["articles"] = recherche_article($recherche);
                        $donnees["titre"]= "Liste des articles";
                        $donnees["rechercheFait"]= true;
                        require_once("vues/header.php");
                        require_once("vues/ListeArticle.php");
                        require_once("vues/footer.php");
                    }
                    else 
                    {
                        $messageErreur = "Aucune donnée selon vos critères de recherches";
                        //afficher les vues 
                        $donnees["titre"]= "Liste des articles";
                        $donnees["articles"] = obtenir_articles();
                        require_once("vues/header.php");
                        require_once("vues/ListeArticle.php");  
                        require_once("vues/footer.php");
                    }
    
                }
                break;
        default:
            //action non traitée, commande invalide -- redirection
            header("Location: index.php");
    }


?>