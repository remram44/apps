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
      <h1>{TITRE} - {PROJ_TITRE}</h1>
    </div>
    <div class="mainsplit">
      <div class="box">
        <h1>Description</h1>
{PROJ_DESCR}
      </div>
      <div class="box">
        <h3>Suivi des demandes</h3>
        <p>Dernières activités sur les demandes :</p>
        <ul>
<!-- BEGIN DEMANDE -->
          <li><a href="index.php?mod=demande&amp;id={DEMANDE.ID}" class="demande_{DEMANDE.STATUT}">{DEMANDE.ID}</a> : {DEMANDE.DESCR}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="zero_demandes">{ZERO_DEMANDES.MSG}</li>
<!-- END ZERO_DEMANDES -->
        </ul>
      </div>
    </div>
    <div class="mainsplit">
      <div class="box">
        <h3>Membres</h3>
        <p>Les utilisateurs participants à ce projet sont :</p>
        <ul>
<!-- BEGIN MEMBRE -->
          <li>{MEMBRE.PSEUDO} ({MEMBRE.NOM}, promo {MEMBRE.PROMOTION})</li>
<!-- END MEMBRE -->
<!-- BEGIN ZERO_MEMBRES -->
          <li class="zero_demandes">{ZERO_MEMBRES.MSG}</li>
<!-- END ZERO_MEMBRES -->
        </ul>
      </div>
      <div class="box">
        <h3>Dernières modifications</h3>
        <p>Liste des derniers commits :</p>
        <ul>
          <li>Demande inutile</li>
          <li>Bug inutile</li>
        </ul>
      </div>
    </div>
    <hr class="clear" />
  </body>
</html>