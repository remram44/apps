    <div class="mainsplit">
      <div class="box">
{HTML_DESCRIPTION}
      </div>
    </div>
    <div class="mainsplit">
      <div class="box">
        <h3>Suivi des demandes</h3>
        <p>Derni�res activit�s sur les demandes :</p>
        <ul>
<!-- BEGIN DEMANDE -->
          <li><a href="index.php?mod=demande&amp;id={DEMANDE.ID}" class="demande_{DEMANDE.STATUT}">{DEMANDE.ID}</a> : {DEMANDE.TITRE}</li>
<!-- END DEMANDE -->
<!-- BEGIN ZERO_DEMANDES -->
          <li class="liste_vide">Il n'y a aucune demande � afficher.</li>
<!-- END ZERO_DEMANDES -->
        </ul>
        <p><a href="index.php?mod=liste_demandes">D�tails</a></p>
      </div>
      <div class="box">
        <h3>Utilisateurs actifs</h3>
        <ul>
<!-- BEGIN UTILISATEUR -->
          <li>{UTILISATEUR.PSEUDO} ({UTILISATEUR.NOM}, {UTILISATEUR.PROMO}) sur <a href="index.php?mod=projet&amp;id={UTILISATEUR.PROJET_ID}">{UTILISATEUR.PROJET}</a></li>
<!-- END UTILISATEUR -->
<!-- BEGIN ZERO_UTILISATEURS -->
          <li class="liste_vide">Il n'y a aucune activit� � rapporter.</li>
<!-- END ZERO_UTILISATEURS -->
        </ul>
      </div>
    </div>
