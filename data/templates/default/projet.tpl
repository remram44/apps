    <div class="mainsplit">
      <div class="box">
        <h1>{PROJ_TITRE} - description</h1>
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
          <li class="liste_vide">{ZERO_DEMANDES.MSG}</li>
<!-- END ZERO_DEMANDES -->
        </ul>
        <p><a href="index.php?mod=liste_demandes&amp;projet={PROJ_ID}">Détails</a></p>
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
        <p><a href="index.php?mod=versions&amp;id={PROJ_ID}">Détails</a></p>
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
          <li class="liste_vide">{ZERO_MEMBRES.MSG}</li>
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
<!-- BEGIN ADMIN_PROJET -->
      <div class="box">
        <p class="admin"><a href="index.php?mod=edit_projet&amp;id={PROJ_ID}">Page d'administration</a></p>
      </div>
<!-- END ADMIN_PROJET -->
    </div>
