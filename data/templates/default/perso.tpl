    <div class="box">
      <h1>Page utilisateur</h1>
    </div>
<!-- BEGIN ERREUR -->
    <div class="erreurbox">
      <p>{ERREUR.TEXTE}</p>
    </div>
<!-- END ERREUR -->
<!-- BEGIN INFO -->
    <div class="confirmbox">
      <p>{INFO.TEXTE}</p>
    </div>
<!-- END INFO -->
    <div class="box">
      <form method="post" action="index.php?mod=perso">
        <p>Utilisez cette page pour modifier vos préférences.</p>
        <div style="text-align: center;">
          <p><label>Nom d'utilisateur : <input type="text" disabled value="{PSEUDO}" /></label></p>
          <p><label>Véritable nom : <input type="text" disabled value="{NOM}" /></label></p>
          <p><label>Mot de passe actuel : <input type="password" name="chg_mdp" /></label></p>
          <p><label>Nouveau mot de passe : <input type="password" name="chg_mdp1" /></label></p>
          <p><label>Nouveau mot de passe (confirmez) : <input type="password" name="chg_mdp2" /></label></p>
          <p><label>Design : <input type="text" name="chg_tpl" value="{TEMPLATE}" disabled /></label></p>
          <p><input type="submit" value="Enregistrer" /></p>
        </div>
      </form>
    </div>
