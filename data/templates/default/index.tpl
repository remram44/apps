<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <title>{TITRE}</title>
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
    <div class="mainsplit">
      <div class="box">
{HTML_DESCRIPTION}
      </div>
    </div>
    <div class="mainsplit">
      <div class="box">
        <h3>Suivi des demandes</h3>
        <p>Dernières activités sur les demandes :</p>
        <ul>
<!-- BEGIN DEMANDE -->
          <li><a href="index.php?mod=demande&amp;id={DEMANDE.ID}" class="demande_{DEMANDE.STATUT}">{DEMANDE.ID}</a> : {DEMANDE.DESCR}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="liste_vide">{ZERO_DEMANDES.MSG}</li>
<!-- END ZERO_DEMANDES -->
        </ul>
        <p><a href="index.php?mod=liste_demandes">Détails</a></p>
      </div>
      <div class="box">
        <h3>Utilisateurs actifs</h3>
        <ul>
<!-- BEGIN UTILISATEUR -->
          <li>{UTILISATEUR.PSEUDO} ({UTILISATEUR.NOM}, {UTILISATEUR.PROMO}) sur <a href="index.php?mod=projet&amp;id={UTILISATEUR.PROJET_ID}">{UTILISATEUR.PROJET}</a></li>
<!-- END UTILISATEUR -->
<!-- BEGIN ZERO_UTILISATEURS -->
          <li class="liste_vide">{ZERO_UTILISATEURS.MSG}</li>
<!-- END ZERO_UTILISATEURS -->
        </ul>
      </div>
    <hr class="clear" />
  </body>
</html>
