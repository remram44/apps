    <div class="box">
      <h1>Page d'administration</h1>
    </div>
    <form method="post" action="index.php?mod=admin">
<!-- BEGIN ADMIN_PROJETS -->
      <div class="box">
        <h2>Projets</h2>
        <table class="liste">
          <tr><th>Nom</th><th>Statut des demandes</th><th>Nombre de membres</th><th>Nombre de demandes</th></tr>
<!-- BEGIN PROJET -->
          <tr class="{ADMIN_PROJETS.PROJET.PARITE}"><td>{ADMIN_PROJETS.PROJET.NOM}</td><td>{ADMIN_PROJETS.PROJET.OPEN_DEMANDES}<td>{ADMIN_PROJETS.PROJET.NB_MEMBRES}</td><td>{ADMIN_PROJETS.PROJET.NB_DEMANDES}</td><td><input type="submit" name="proj_del{ADMIN_PROJETS.PROJET.ID}" value="Supprimer" /> <a href="index.php?mod=edit_projet&amp;id={ADMIN_PROJETS.PROJET.ID}">Modifier</a></td></tr>
<!-- END PROJET -->
<!-- BEGIN ZERO_PROJETS -->
          <tr><td class="liste_vide" colspan="5">Il n'y a aucun projet à afficher.</td></tr>
<!-- END ZERO_PROJETS -->
        </table>
      </div>
<!-- END ADMIN_PROJETS -->
<!-- BEGIN ADMIN_UTILISATEURS -->
      <div class="box">
        <h2>Utilisateurs</h2>
        <table class="liste">
          <tr><th>Pseudo</th><th>Nom</th><th>Promotion</th><th>Permissions globales</th></tr>
<!-- BEGIN UTILISATEUR -->
          <tr class="{ADMIN_UTILISATEURS.UTILISATEUR.PARITE}"><td>{ADMIN_UTILISATEURS.UTILISATEUR.PSEUDO}</td><td>{ADMIN_UTILISATEURS.UTILISATEUR.NOM}</td><td>{ADMIN_UTILISATEURS.UTILISATEUR.PROMO}</td><td><ul>
<!-- BEGIN PERMISSION -->
            <li>{ADMIN_UTILISATEURS.UTILISATEUR.PERMISSION.NOM}</li>
<!-- END PERMISSION -->
          </ul></td></tr>
<!-- END UTILISATEUR -->
        </table>
      </div>
<!-- END ADMIN_UTILISATEURS -->
    </form>
