<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <title>{TITRE} - Demandes</title>
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
    <div class="box_recherche">
      <h1>Recherche</h1>
      <form action="index.php" method="get">
        <p>Utilisez le formulaire ci-dessous pour effectuer une recherche. Le caractère * (étoile) remplace une chaîne de caractère quelconque.</p>
        <p><label>Nom : <input type="edit" name="filtre_nom" value="{FILTRE_NOM}" /></label><br />
        <label>Statut : <select name="filtre_statut">
<!-- BEGIN FILTRE_STATUT -->
          <option value="{FILTRE_STATUT.VALEUR}"{FILTRE_STATUT.SELECTED}>{FILTRE_STATUT.NOM}</option>
<!-- END FILTRE_STATUT -->
        </select></label><br />
        <input type="hidden" name="mod" value="liste_demandes" />
        <input type="submit" value="Rechercher" /></p>
      </form>
    </div>
    <div class="box">
      <table class="liste_demandes">
        <tr>
          <th>ID</th>
          <th>Projet</th>
          <th>Version</th>
          <th>Titre</th>
          <th>Auteur</th>
          <th>Statut</th>
          <th>Priorité</th>
          <th>Date de création</th>
          <th>Dernière activité</th>
        </tr>
<!-- BEGIN DEMANDE -->
        <tr class="{DEMANDE.PARITE}">
          <td><a href="index.php?mod=demande&amp;id={DEMANDE.ID}" class="demande_{DEMANDE.STATUT}">{DEMANDE.ID}</a></td>
          <td><a href="index.php?mod=projet&amp;id={DEMANDE.PROJET_ID}">{DEMANDE.PROJET}</a></td>
          <td>
<!-- BEGIN VERSION -->
            <a href="index.php?mod=versions&amp;id={DEMANDE.PROJET_ID}">{DEMANDE.VERSION.NOM}</a>
<!-- END VERSION -->
          </td>
          <td>{DEMANDE.TITRE}</td>
          <td><acronym title="{DEMANDE.AUT_NOM} ({DEMANDE.AUT_PROMO})">{DEMANDE.AUT_PSEUDO}</acronym></td>
          <td>{DEMANDE.STATUT_NOM}</td>
          <td>{DEMANDE.PRIORITE}</td>
          <td></td>
          <td></td>
        </tr>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
        <tr><td class="liste_vide" colspan="2">{ZERO_DEMANDES.MSG}</td></tr>
<!-- END ZERO_DEMANDES -->
      </table>
    </div>

<!-- BEGIN PREV_PAGE -->
    <div class="box_prev_page">
      <p><a href="{PREV_PAGE.LIEN}">Page précédente</a></p>
    </div>
<!-- END PREV_PAGE -->
<!-- BEGIN NEXT_PAGE -->
    <div class="box_next_page">
      <p><a href="{NEXT_PAGE.LIEN}">Page suivante</a></p>
    </div>
<!-- END NEXT_PAGE -->
    <hr class="clear" />
  </body>
</html>
