    <div class="box">
      <h1>Page utilisateur</h1>
    </div>
<!-- BEGIN ERREUR -->
    <div class="erreurbox">
      <p>{ERREUR.TEXTE}</p>
    </div>
<!-- END ERREUR -->
<!-- BEGIN INFO -->
    <div class="infobox">
      <p>{INFO.TEXTE}</p>
    </div>
<!-- END INFO -->
    <div class="box">
      <form method="post" action="index.php?mod=perso">
        {FORM_TOKEN}
        <p>Utilisez cette page pour modifier vos préférences.</p>
        <div style="text-align: center;">
          <p><label>Nom d'utilisateur : <input type="text" disabled value="{PSEUDO}" /></label></p>
          <p><label>Véritable nom : <input type="text" disabled value="{NOM}" /></label></p>
          <p><label>Mot de passe actuel : <input type="password" name="chg_mdp" /></label></p>
          <p><label>Nouveau mot de passe : <input type="password" name="chg_mdp1" /></label></p>
          <p><label>Nouveau mot de passe (confirmez) : <input type="password" name="chg_mdp2" /></label></p>
          <p><label>Design : <select name="chg_tpl">
<!-- BEGIN TEMPLATE -->
<!-- BEGIN ACTUEL -->
              <option value="{TEMPLATE.ACTUEL.NOM}" selected>{TEMPLATE.ACTUEL.NOM}</option>
<!-- END ACTUEL -->
<!-- BEGIN AUTRE -->
              <option value="{TEMPLATE.AUTRE.NOM}">{TEMPLATE.AUTRE.NOM}</option>
<!-- END AUTRE -->
<!-- END TEMPLATE -->
            </select></label></p>
          <p><input type="submit" value="Enregistrer" /></p>
        </div>
      </form>
    </div>
<!-- BEGIN LIEN_ADMIN -->
    <div class="box">
      <p class="admin"><a href="index.php?mod=admin">Page d'administration</a></p>
    </div>
<!-- END LIEN_ADMIN -->
