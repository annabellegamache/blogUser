         <main>
            <h1>Bienvenue sur le blog</h1>
            <!-- Section affichage des articles-->
            <section>
                <?php
                    if(isset($messageErreur)) echo "<span class='erreur'>$messageErreur</span><br>";
                    $articles = $donnees["articles"];
                    if(isset($donnees["rechercheFait"])) echo '<div class="lienArticle"><img src="./vues/assets/img/return.svg" alt="icone-retour"><a href="index.php">Afficher tous les articles</a></div>';
                    //afficher dynamiquement les articles spécifiée en paramètres présents dans la base de données
                    while($rangee = mysqli_fetch_assoc($articles))
                    {
                        //à chaque tour de boucle, $rangee vaut un article
                ?>
                        <!-- Un article-->
                        <article>
                            <?php
                                /* Si l'article est écrit par l'usager on  lui ajoute sa zone admin */
                                if(isset($_SESSION["usager"]) && $rangee["idUsager"] == $_SESSION["idUsager"])
                                {
                                    echo '
                                            <div class="adminZone">
                                                 <a href="index.php?commande=FormModifArticle&id='.$rangee["idArticle"].'"><img src="./vues/assets/img/edit.svg" alt="icone-editer">Modifier</a>
                                                 <a href="index.php?commande=SupprimerArticle&id='.$rangee["idArticle"].'"><img src="./vues/assets/img/delete.svg" alt="icone-supprimer">Supprimer</a>
                                            </div>
                                        ';       
                                }/* FIN zone admin*/
                            ?> 
                            <h2><?php echo $rangee["Titre"]?></h2>
                            <p><?php echo $rangee["Texte"]?></p>
                            <h4>par: <?php echo $rangee["Prenom"]. " ".$rangee["Nom"] ?></h4>
                        </article>
                <?php    
                    }
                ?>
            </section>
        </main>

