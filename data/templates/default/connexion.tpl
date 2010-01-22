    <div class="box">
      <h1>Connexion</h1>
    </div>
    <div class="box">
<!-- BEGIN FORM -->
      <form method="post" action="index.php?mod=connexion">
        <p>Vous devez vous connecter pour créer des nouveaux projets ou gérer vos projets. Si vous ne possédez pas de compte, veuillez en demander un à l'un des administrateurs du site.</p>
        <div style="border: 1px solid silver; margin: auto; margin-bottom: 3em; text-align: center;width: 12em;">
          <p><label>Nom d'utilisateur : <input type="text" name="conn_nom" /></label></p>
          <p><label>Mot de passe : <input type="password" name="conn_mdp" /></label></p>
          <p><input type="submit" value="Connexion" /></p>
        </div>
      </form>
<!-- END FORM -->
<!-- BEGIN OK_REDIRECT -->
      <p>Bienvenue {OK_REDIRECT.PSEUDO}, vous êtes maintenant connecté. Si votre navigateur ne vous redirige pas, <a href="index.php">cliquez ici</a> pour aller à la page d'accueil.</p>
<!-- END OK_REDIRECT -->
    </div>
