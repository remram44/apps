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
