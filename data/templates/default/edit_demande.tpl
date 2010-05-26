    <div class="box">
<!-- BEGIN AJOUT -->
      <h1>Nouvelle demande</h1>
    </div>
    <div class="box">
      <form method="post" action="index.php?mod=edit_demande&amp;projet={DEM_PROJET_ID}">
<!-- END AJOUT -->
<!-- BEGIN EDIT -->
       <h1>Demande n°{EDIT.DEM_ID}</h1>
    </div>
    <div class="box">
      <form method="post" action="index.php?mod=edit_demande&amp;id={EDIT.DEM_ID}">
<!-- BEGIN VERSION_COURANTE -->
<!-- END VERSION_COURANTE -->
<!-- BEGIN VERSION -->
<!-- END VERSION -->
<!-- END EDIT -->
        <table id="demande">
          <tr>
            <th>Projet</th><td><a href="index.php?mod=projet&amp;id={DEM_PROJET_ID}">{DEM_PROJET}</a></td>
            <th>Priorité</th>
<!-- BEGIN PRIORITE_ADMIN -->
            <td><input type="text" name="dem_prio" value="{DEM_PRIO}" /></td>
<!-- END PRIORITE_ADMIN -->
<!-- BEGIN PRIORITE_NONADMIN -->
            <td><input type="text" value="{DEM_PRIO}" disabled /></td>
<!-- END PRIORITE_NONADMIN -->
          </tr>
<!-- BEGIN AJOUT -->
          <tr>
            <th>Titre</th><td><input type="text" name="dem_titre" /></td>
            <th>Description</th><td rowspan="2"><textarea name="dem_descr" rows="5" cols="40"></textarea></td>
          </tr>
        </table>
        <p><input type="submit" name="dem_submit" value="Envoyer" /></p>
<!-- END AJOUT -->
<!-- BEGIN EDIT -->
          <tr>
            <th>Version cible</th><td><select name="dem_version">
<!-- BEGIN VERSION_COURANTE -->
              <option value="{EDIT.VERSION_COURANTE.ID}" selected>{EDIT.VERSION_COURANTE.NOM}</option>
<!-- END VERSION_COURANTE -->
<!-- BEGIN VERSION -->
              <option value="{EDIT.VERSION.ID}">{EDIT.VERSION.NOM}</option>
<!-- END VERSION -->
            </select></td>
            <th>Description</th><td rowspan="2"><textarea name="dem_descr" rows="5" cols="40">{EDIT.DESCRIPTION}</textarea></td>
          </tr>
          <tr>
            <th>Titre</th><td><input type="text" name="dem_titre" value="{EDIT.DEM_TITRE}" /></td>
          </tr>
          <tr>
            <th>Statut</th><td><select name="dem_statut">
<!-- BEGIN STATUT_COURANT -->
              <option value="{EDIT.STATUT_COURANT.NB}">{EDIT.STATUT_COURANT.NOM}</option>
<!-- END STATUT_COURANT -->
<!-- BEGIN STATUT -->
              <option value="{EDIT.STATUT.NB}">{EDIT.STATUT.NOM}</option>
<!-- END STATUT -->
            </select></td>
          </tr>
        </table>
        <p><input type="submit" name="dem_submit" value="Modifier" /></p>
<!-- END EDIT -->
      </form>
    </div>
