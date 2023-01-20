<?php
   /*obtenir les données de la requête et les placées dans le formulaire */
    $article = mysqli_fetch_assoc($donnees["article"]);

   /*Vérifier si l'usager arrive du formulaire et avait déjà essayé de modifier  */ 
    if(isset($titre)){
        $article["Titre"] = $titre;
    }

    if(isset($texte)){
        $article["Texte"] = $texte;
    }

?>
    <!-- ================================================== Formulaire modification -->
    <main>
        <h1>Modifier l'article</h1>       
        <section class="auteurZone">
        <?php if(isset($messageErreur)) echo "<span class='erreur'>$messageErreur</span><br>";?>
            <form method="POST">
                  <input type="text" name="titre" value="<?php echo htmlspecialchars($article["Titre"]) ?>"/><br>
                  <textarea name="texte"><?php echo htmlspecialchars($article["Texte"]) ?></textarea><br>
                  <input type="hidden" name="commande" value="ModifArticle"/>
                  <input type="hidden" name="id" value="<?php echo $article["ID"] ?>"/>
                  <input type="submit" name="login" value="Modifier"/>
            </form>
        </section>
    </main>

</div>