<!-- BEGIN VERSION -->
    <div class="box">
      <h3>{VERSION.NOM}</h3>
{VERSION.DESCR}
      <h4>Demandes associées :</h4>
        <ul>
<!-- BEGIN DEMANDE -->
          <li><a href="index.php?mod=demande&amp;id={VERSION.DEMANDE.ID}" class="demande_{VERSION.DEMANDE.STATUT}">{VERSION.DEMANDE.ID}</a> : {VERSION.DEMANDE.TITRE}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="liste_vide">Aucune demande n'est associée à cette version.</li>
<!-- END ZERO_DEMANDES -->
        </ul>
    </div>
<!-- END VERSION -->
<!-- BEGIN ZERO_VERSIONS -->
    <div class="box">
      <h3>Aucune version</h3>
      <p class="liste_vide">Il n'y a aucune version à afficher.</p>
    </div>
<!-- END ZERO_VERSIONS -->
