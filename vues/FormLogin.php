
    <!-- ====================================================== Formulaire login -->
    <main>
        <h1>Veuillez vous identifier</h1>
        <section class="loginZone">
            <form method="POST" >
                   <label for="user">Nom d'utilisateur :</label>  <input type="text" name="user"/><br>
                    <label for="pass">Mot de passe :</label>  <input type="password" name="pass"/><br>
                    <input type="hidden" name="commande" value="ValidationLogin"/>
                    <?php if(isset($messageErreur)) echo "<span class='erreur'>$messageErreur</span><br>";?>
                    <input type="submit" name="login" value="Login"/>
            </form>
        </section>
      
    </main>




