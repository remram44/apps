<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <title>{TITRE} - {PROJ_TITRE} - Versions</title>
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
<!-- BEGIN VERSION -->
    <div class="box">
      <h3>{VERSION.NOM}</h3>
{VERSION.DESCR}
      <h4>Demandes associées :</h4>
        <ul>
<!-- BEGIN DEMANDE -->
          <li><a href="index.php?mod=demande&amp;id={VERSION.DEMANDE.ID}" class="demande_{VERSION.DEMANDE.STATUT}">{VERSION.DEMANDE.ID}</a> : {VERSION.DEMANDE.DESCR}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="liste_vide">{VERSION.ZERO_DEMANDES.MSG}</li>
<!-- END ZERO_DEMANDES -->
        </ul>
    </div>
<!-- END VERSION -->
<!-- BEGIN ZERO_VERSIONS -->
    <div class="box">
      <h3>Aucune version</h3>
      <p class="liste_vide">{ZERO_VERSIONS.MSG}</p>
    </div>
<!-- END ZERO_VERSIONS -->
  </body>
</html>
