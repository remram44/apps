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
    <div class="mainsplit">
      <div class="box">
        <h1>{PROJ_TITRE} - description</h1>
{PROJ_DESCR}
      </div>
      <div class="box">
        <h3>Suivi des demandes</h3>
        <p>Derni�res activit�s sur les demandes :</p>
        <ul>
<!-- BEGIN DEMANDE -->
          <li><a href="index.php?mod=demande&amp;id={DEMANDE.ID}" class="demande_{DEMANDE.STATUT}">{DEMANDE.ID}</a> : {DEMANDE.DESCR}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="liste_vide">{ZERO_DEMANDES.MSG}</li>
<!-- END ZERO_DEMANDES -->
        </ul>
        <p><a href="index.php?mod=liste_demandes&amp;projet={PROJ_ID}">D�tails</a></p>
      </div>
      <div class="box">
        <h3>Versions</h3>
        <p>Versions du projet :</p>
        <ul>
<!-- BEGIN VERSION -->
          <li>{VERSION.NOM}</li>
<!-- END VERSION -->
<!-- BEGIN ZERO_VERSIONS -->
          <li class="liste_vide">{ZERO_VERSIONS.MSG}</li>
<!-- END ZERO_VERSIONS -->
        </ul>
        <p><a href="index.php?mod=versions&amp;id={PROJ_ID}">D�tails</a></p>
      </div>
    </div>
    <div class="mainsplit">
      <div class="box">
        <h3>Membres</h3>
        <p>Les utilisateurs participants � ce projet sont :</p>
        <ul>
<!-- BEGIN MEMBRE -->
          <li>{MEMBRE.PSEUDO} ({MEMBRE.NOM}, promo {MEMBRE.PROMOTION})</li>
<!-- END MEMBRE -->
<!-- BEGIN ZERO_MEMBRES -->
          <li class="liste_vide">{ZERO_MEMBRES.MSG}</li>
<!-- END ZERO_MEMBRES -->
        </ul>
      </div>
      <div class="box">
        <h3>Derni�res modifications</h3>
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