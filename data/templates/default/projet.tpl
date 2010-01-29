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
          <li><a href="index.php?mod=demande&amp;id={DEMANDE.ID}" class="demande_{DEMANDE.STATUT}">{DEMANDE.ID}</a> : {DEMANDE.TITRE}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="liste_vide">Il n'y a aucune demande à afficher.</li>
<!-- END ZERO_DEMANDES -->
        </ul>
        <p><a href="index.php?mod=liste_demandes&amp;projet={PROJ_ID}">Détails</a>
<!-- BEGIN NOUVELLE_DEMANDE -->
          - <a href="index.php?mod=edit_demande&amp;projet={PROJ_ID}">Nouvelle demande</a>
<!-- END NOUVELLE_DEMANDE -->
        </p>
      </div>
      <div class="box">
        <h3>Versions</h3>
        <p>Versions du projet :</p>
        <ul>
<!-- BEGIN VERSION -->
          <li>{VERSION.NOM}</li>
<!-- END VERSION -->
<!-- BEGIN ZERO_VERSIONS -->
          <li class="liste_vide">Ce projet n'a défini aucune version.</li>
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
          <li class="liste_vide">Ce projet n'a aucun membre.</li>
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
