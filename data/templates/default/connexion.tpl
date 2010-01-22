<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <title>{TITRE} - {PROJ_TITRE}</title>
    <link href="{TEMPLATE_URL}/style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div id="header">
      <div id="menu">
        <ul>
<!-- BEGIN MENU -->
          <li><a href="{MENU.LIEN}">{MENU.TEXTE}</a></li>
<!-- END MENU -->
        </ul>
      </div>
      <h1>{TITRE}</h1>
    </div>
    <div class="box">
      <h1>Connexion</h1>
    </div>
    <div class="box">
      <form method="post" action="index.php">
        <p>Vous devez vous connecter pour créer des nouveaux projets ou gérer vos projets. Si vous ne possédez pas de compte, veuillez en demander un à l'un des administrateurs du site.</p>
        <div style="text-align: center;">
          <p><label>Nom d'utilisateur : <input type="text" name="conn_nom" /></label></p>
          <p><label>Mot de passe : <input type="password" name="conn_mdp" /></label></p>
          <p><input type="submit" value="Connexion" /></p>
        </div>
      </form>
    </div>
    <hr class="clear" />
  </body>
</html>
