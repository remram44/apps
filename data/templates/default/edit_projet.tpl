<!-- BEGIN AJOUT -->
    <div class="box">
      <h1>Ajout d'un projet</h1>
    </div>
    <div class="box">
      <p>Après la création du nouveau projet, vous pourrez y ajouter des membres via cette interface.</p>
      <form method="post" action="index.php?mod=edit_projet">
        <table>
          <tr><th>Nom du projet :</th><td><input type="text" name="proj_nom" value="{AJOUT.NOM}" /></td></tr>
          <tr><th>Description :</th><td><textarea name="proj_description" rows="8" cols="60">{AJOUT.DESCRIPTION}</textarea></td></tr>
          <tr><th></th><td><input type="submit" value="Ajouter" /></td></tr>
        </table>
      </form>
    </div>
<!-- END AJOUT -->
<!-- BEGIN EDIT -->
    <div class="box">
      <h1>Modification d'un projet</h1>
    </div>
    <div class="box">
      <form method="post" action="index.php?mod=edit_projet&amp;id={EDIT.PROJ_ID}">
        <table>
          <tr><th>Nom du projet :</th><td><input type="text" name="proj_nom" value="{EDIT.NOM}" /></td></tr>
          <tr><th>Description :</th><td><textarea name="proj_description" rows="8" cols="60">{EDIT.DESCRIPTION}</textarea></td></tr>
          <tr>
            <th>Membres :</th>
            <td>
              <table>
<!-- BEGIN MEMBRE -->
                <tr>
                  <td>{EDIT.MEMBRE.NOM} ({EDIT.MEMBRE.PSEUDO}) <select name="proj_mem_admin{EDIT.MEMBRE.USERID}">
<!-- BEGIN ROLE_SELECTED -->
                      <option value="{EDIT.MEMBRE.ROLE_SELECTED.VALEUR}" selected>{EDIT.MEMBRE.ROLE_SELECTED.NOM}</option>
<!-- END ROLE_SELECTED -->
<!-- BEGIN ROLE -->
                      <option value="{EDIT.MEMBRE.ROLE.VALEUR}">{EDIT.MEMBRE.ROLE.NOM}</option>
<!-- END ROLE -->
                    </select>
                  </td>
                  <td><input type="submit" name="proj_mem_rem{EDIT.MEMBRE.USERID}" value="Retirer ce membre" /></td>
                </tr>
<!-- END MEMBRE -->
<!-- BEGIN ZERO_MEMBRES -->
                <tr><td colspan="2" class="liste_vide">Aucun membre n'a été ajouté pour l'instant.</td></tr>
<!-- END ZERO_MEMBRES -->
                <tr>
                  <td>
                    <select name="proj_mem_add">
<!-- BEGIN AUTRE_UTILISATEUR -->
                      <option value="{EDIT.AUTRE_UTILISATEUR.USERID}">{EDIT.AUTRE_UTILISATEUR.NOM} ({EDIT.AUTRE_UTILISATEUR.PSEUDO})</option>
<!-- END AUTRE_UTILISATEUR -->
                    </select>
                    <select name="proj_mem_add_admin">
<!-- BEGIN ROLE -->
                      <option value="{EDIT.ROLE.VALEUR}">{EDIT.ROLE.NOM}</option>
<!-- END ROLE -->
                    </select>
                  </td>
                  <td><input type="submit" name="proj_mem_add_sub" value="Ajouter un membre" /></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <th>Création de demandes</th>
            <td><select name="proj_open_demandes">
<!-- BEGIN OPEN_DEMANDES_SELECTED -->
              <option value="{EDIT.OPEN_DEMANDES_SELECTED.VALEUR}">{EDIT.OPEN_DEMANDES_SELECTED.NOM}</option>
<!-- END OPEN_DEMANDES -->
<!-- BEGIN OPEN_DEMANDES -->
              <option value="{EDIT.OPEN_DEMANDES.VALEUR}">{EDIT.OPEN_DEMANDES.NOM}</option>
<!-- END OPEN_DEMANDES -->
            </select></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" name="proj_submit" value="Modifier" />
              <a href="index.php?mod=projet&amp;id={EDIT.PROJ_ID}">Annuler</a>
            </td>
          </tr>
        </table>
      </form>
    </div>
<!-- END EDIT -->
