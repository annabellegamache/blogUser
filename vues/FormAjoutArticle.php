
    <!-- ================================================================Formulaire Ajout -->
    <main>
        <h1>Ajout d'un article</h1>
        <section class="auteurZone">
        <?php if(isset($messageErreur)) echo "<span class='erreur'>$messageErreur</span><br>";?>
            <form method="POST" >
                  <input type="text" name="titre" placeholder="Titre" value="<?php if(isset($titre)) echo htmlspecialchars($titre); ?>"/><br>
                  <textarea name="texte" placeholder="Écrire votre artcile ici"><?php if(isset($texte)) echo htmlspecialchars($texte); ?></textarea><br>
                  <input type="hidden" name="commande" value="AjoutArticle"/>
                  <input type="submit" name="login" value="Ajouter"/>
            </form>
        </section>
    </main>

