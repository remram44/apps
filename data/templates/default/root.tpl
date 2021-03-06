<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{TITRE}</title>
    <link href="{TEMPLATE_URL}/style.css" rel="stylesheet" type="text/css" />
<!-- BEGIN HEAD_BALISE -->
{HEAD_BALISE.HTML}
<!-- END HEAD_BALISES -->
  </head>
  <body>
    <div id="header">
      <div id="menu">
        <ul>
<!-- BEGIN MENU -->
          <li class="left"><a href="{MENU.LIEN}">{MENU.TEXTE}</a></li>
<!-- END MENU -->
<!-- BEGIN MENU2_CONN -->
          <li class="right"><a href="index.php?mod=connexion">Connexion</a></li>
<!-- END MENU2_CONN -->
<!-- BEGIN MENU2_DECO -->
          <li class="right">Identifié comme <a href="index.php?mod=perso">{MENU2_DECO.PSEUDO}</a> <a href="index.php?mod=deconnexion">(déconnexion)</a></li>
<!-- END MENU2_DECO -->
<!-- BEGIN LIEN_ADMIN -->
          <li class="right admin"><a href="index.php?mod=admin">Page d'administration</a></li>
<!-- END LIEN_ADMIN -->
        </ul>
      </div>
      <h1>{TITRE}</h1>
    </div>
<!-- BEGIN MSG_ERREUR -->
    <div class="erreurbox">
      <p>{MSG_ERREUR.DESCR}</p>
    </div>
<!-- END MSG_ERREUR -->
<!-- BEGIN MSG_INFO -->
    <div class="infobox">
      <p>{MSG_INFO.DESCR}</p>
    </div>
<!-- END MSG_INFO -->
{ROOT_CONTENT}
    <hr class="clear" />
<!-- BEGIN DEBUG -->
    <div class="box" style="font-size: 60%;">
      <h2>Debug ({DEBUG.NB_REQUETES} requêtes) ({DEBUG.TEMPS} secondes)</h2>
      <ul>
<!-- BEGIN REQ -->
        <li>{DEBUG.REQ.SQL}</li>
<!-- END REQ -->
      </ul>
    </div>
<!-- END DEBUG -->
  </body>
</html>
