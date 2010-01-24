    <div class="box_recherche">
      <h1>Recherche</h1>
      <form action="index.php" method="get">
        <p>Vous pouvez utiliser la boîte ci-dessous pour effectuer une recherche ou la laisser vide pour obtenir une liste de tous les projets. Le caractère * (étoile) remplace une chaîne de caractères quelconque.</p>
        <p><label>Rechercher : <input type="edit" name="filtre_nom" value="{FILTRE}" /></label>
        <input type="hidden" name="mod" value="liste_projets" />
        <input type="submit" value="Rechercher" /></p>
      </form>
    </div>
<!-- BEGIN PROJET -->
    <div class="box">
      <h3><a href="index.php?mod=projet&amp;id={PROJET.ID}">{PROJET.NOM}</a></h3>
{PROJET.DESCR}
    </div>
<!-- END PROJET -->
<!-- BEGIN ZERO_PROJETS -->
    <div class="box">
      <h3>Aucun résultat</h3>
      <p class="liste_vide">{ZERO_PROJETS.MSG}</p>
    </div>
<!-- END ZERO_PROJETS -->

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
<!-- BEGIN LIEN_ADMIN -->
    <div class="box">
      <p class="admin"><a href="index.php?mod=edit_projet">Ajouter un projet</a></p>
    </div>
<!-- END LIEN_ADMIN -->
