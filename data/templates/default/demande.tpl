    <div class="box">
      <h1>Demande n°{DEMANDE_ID}</h1>
      <h2>{DEMANDE_TITRE}</h2>
    </div>
    <div class="box">
      <table id="demande">
        <tr>
          <th>Statut</th><td>{STATUT_NOM}</td>
          <th>Reporter</th><td>{AUT_NOM} ({AUT_PSEUDO})</td>
        </tr>
        <tr>
          <th>Priorite</th><td>{PRIORITE}</td>
          <th>Date d'ouverture</th><td>{CREATION}</td>
        </tr>
        <tr>
          <th>Projet</th><td><a href="index.php?mod=projet&amp;id={PROJET_ID}">{PROJET}</a></td>
          <th>Description</th><td rowspan="2">{DESCRIPTION}</td>
        </tr>
<!-- BEGIN VERSION -->
        <tr>
          <th>Version cible</th><td>{VERSION.NOM}</td>
        </tr>
<!-- END VERSION -->
      </table>
    </div>
<!-- BEGIN ADMIN_DEMANDE -->
    <div class="box">
      <p class="admin"><a href="index.php?mod=edit_demande&amp;id={DEMANDE_ID}">Modification</a></p>
    </div>
<!-- END ADMIN_DEMANDE -->
<!-- BEGIN AJOUT_COMMENTAIRE -->
    <div class="box">
      <h2>Commenter cette demande</h2>
      <form method="post" action="index.php?mod=demande&amp;id={DEMANDE_ID}">
        <p><textarea cols="80" rows="3" name="commentaire"></textarea><br />
        <input type="submit" value="Poster un commentaire" /></p>
      </form>
    </div>
<!-- END AJOUT_COMMENTAIRE -->
<!-- BEGIN COMMENTAIRE -->
    <div class="box">
      <div style="float: right"><p>{COMMENTAIRE.DATE}</p></div>
      <h3>{COMMENTAIRE.AUTEUR_PSEUDO} ({COMMENTAIRE.AUTEUR_NOM}, {COMMENTAIRE.AUTEUR_PROMO})</h3>
<!-- BEGIN TEXTUEL -->
      <p>{COMMENTAIRE.TEXTE}</p>
<!-- END TEXTUEL -->
<!-- BEGIN RESUME -->
      <p class="resume">{COMMENTAIRE.TEXTE}</p>
<!-- END RESUME -->
    </div>
<!-- END COMMENTAIRE -->
