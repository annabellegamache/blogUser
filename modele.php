<?php

    /*WEBDEV*/
   define("SERVER", "localhost");
    define("USERNAME", "e0239070");
    define("PASSWORD", "4XhID4YoZkyycWCVsFun");
    define("DBNAME", "e0239070");

    /*LOCALHOST*/
    /*define("SERVER", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DBNAME", "blog");*/

/*Fonction qui connecte à la BD */
    function connectDB()
    {
        //se connecter à la base de données
        $c = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME);
        if(!$c)
            trigger_error("Erreur de connexion : " . mysqli_connect_error());
        //s'assurer que la connection traite du UTF8
        mysqli_query($c, "SET NAMES 'utf8'");
        return $c;
    }

    $connexion = connectDB();

/*Fonction Login renvoie true ou false quand l'usager s'authentifie */
    function login($username, $password)
    {
        global $connexion;
        $requete = "SELECT motPasse FROM usagers WHERE nomUsager=?";
        if($reqPrep = mysqli_prepare($connexion, $requete))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 's', $username);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);
            //obtenir le résultat 
            $resultats = mysqli_stmt_get_result($reqPrep);

            if(mysqli_num_rows($resultats) > 0)
            {
                $rangee = mysqli_fetch_assoc($resultats);
                $motDePasseEncrypte = $rangee["motPasse"];
                if(password_verify($password, $motDePasseEncrypte))
                    return true;
                else    
                    return false;
            }
            else
                return false;
        }
    }

/*Fonction qui renvoie liste des articles de la BD */
    function obtenir_articles()
    {
        global $connexion;
        $requete = "SELECT articles.ID as idArticle, Titre, Texte, idUsager, usagers.Prenom as Prenom, usagers.Nom as Nom FROM articles JOIN usagers ON usagers.ID= articles.idUsager ORDER BY articles.ID DESC";
        //exécuter la requête . 
        $resultats = mysqli_query($connexion, $requete);
        return $resultats;
    }

/*Fonction qui renvoie un article de la BD selon son id */
    function obtenir_article_par_id($id)
    {
        global $connexion;
        $requete = "SELECT ID, Titre, Texte FROM articles WHERE id = $id";
        $resultat = mysqli_query($connexion, $requete);
        return $resultat;
    }

/*Fonction qui ajout un article à la BD */
    function ajoute_article($titre, $texte, $idUsager)
    {
        global $connexion;
        $requete = "INSERT INTO articles(Titre, Texte, idUsager) VALUES (?, ?, $idUsager)";
        if($reqPrep = mysqli_prepare($connexion, $requete))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 'ss', $titre, $texte);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);

            //est-ce que l'insertion a fonctionné
            if(mysqli_affected_rows($connexion) > 0)
            {
                return true;
            }
            else
            {
                die("Erreur lors de l'insertion." . mysqli_error($connexion));
            }
        }
    }

/*Fonction qui supprime un article de la BD */
    function supprime_article($id)
    {
        global $connexion;
        $requete = "DELETE FROM articles WHERE ID=$id";
        $resultat = mysqli_query($connexion, $requete);
        //est-ce que la supression a fonctionné
        if(mysqli_affected_rows($connexion) > 0)
        {
            return true;
        }
        else
        {
            die("Erreur lors de la supression." . mysqli_error($connexion));
        }
    }

/*Fonction qui modifie un article de la BD */
    function modif_article($titre, $texte, $id)
    {
        global $connexion;
        $requete = "UPDATE articles SET Titre=?, Texte=? WHERE id='$id'";
        //Préparation de la requête 
        if($reqPrep = mysqli_prepare($connexion, $requete))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, "ss", $titre, $texte);
            //exécuter la requête 
            mysqli_stmt_execute($reqPrep);
            //est-ce que la modification a fonctionné?
            if(mysqli_affected_rows($connexion) >= 0) /* si rien de modifier je retourne true  */
                return true;
            else
                die("Erreur lors de la modification.");
        }
    }
  
/*Fonction qui renvoi le prénom de l'usager authentifié */
    function obtenir_prenom_usager($user)
    {
        global $connexion;
        $requete = "SELECT Prenom FROM usagers WHERE usagers.nomUsager = '$user'";
        $resultats = mysqli_query($connexion, $requete);
        $rangee = mysqli_fetch_assoc($resultats);
        return $rangee["Prenom"];
    }

/*Fonction qui renvoi le ID de l'usager authentifié */
    function obtenir_id_usager ($user)
    {
        global $connexion;
        $requete = "SELECT ID FROM usagers WHERE usagers.nomUsager = '$user'";
        $resultats = mysqli_query($connexion, $requete);
        $rangee = mysqli_fetch_assoc($resultats);
        return $rangee["ID"];
    }

/*Fonction qui renvoi les articles en fonction de larecherche de l'usager*/
    function recherche_article($recherche)
    {
        global $connexion;
        $recherche = "%" . $_REQUEST["recherche"] . "%";
        $requete = "SELECT articles.ID as idArticle, Titre, Texte, idUsager, usagers.Prenom as Prenom, usagers.Nom as Nom FROM articles JOIN usagers ON usagers.ID= articles.idUsager  WHERE Titre LIKE ? OR Texte LIKE ?";
        //exécution de la requête
        $reqPrep = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($reqPrep, "ss", $recherche, $recherche);
        mysqli_stmt_execute($reqPrep);
        $resultats = mysqli_stmt_get_result($reqPrep);
        if(mysqli_affected_rows($connexion) > 0)
            return $resultats; //retourne le resultat de la recherche       
        else 
            return false;
    }



