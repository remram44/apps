<!-- BEGIN AJOUT -->
    <div class="box">
      <h1>Ajout d'un projet</h1>
    </div>
    <div class="box">
      <p>Après la création du nouveau projet, vous pourrez y ajouter des membres via cette interface.</p>
      <form method="post" action="index.php?mod=edit_projet">
        <table>
          <tr><td>Nom du projet :</td><td><input type="text" name="proj_nom" value="{AJOUT.NOM}" /></td></tr>
          <tr><td>Description :</td><td><textarea name="proj_description" rows="8" cols="60">{AJOUT.DESCRIPTION}</textarea></td></tr>
          <tr><td></td><td><input type="submit" value="Ajouter" /></td></tr>
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
          <tr><td>Nom du projet :</td><td><input type="text" name="proj_nom" value="{EDIT.NOM}" /></td></tr>
          <tr><td>Description :</td><td><textarea name="proj_description" rows="8" cols="60">{EDIT.DESCRIPTION}</textarea></td></tr>
          <tr>
            <td>Membres :</td>
            <td>
              <table>
<!-- BEGIN MEMBRE -->
                <tr>
                  <td>{EDIT.MEMBRE.NOM} ({EDIT.MEMBRE.PSEUDO}) <select name="proj_mem_admin{EDIT.MEMBRE.USERID}">
<!-- BEGIN ADMIN -->
                      <option value="0">membre</option>
                      <option value="1" selected>admin</option>
<!-- END ADMIN -->
<!-- BEGIN NONADMIN -->
                      <option value="0" selected>membre</option>
                      <option value="1">admin</option>
<!-- END NONADMIN -->
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
                      <option value="0" selected>membre</option>
                      <option value="1">admin</option>
                    </select>
                  </td>
                  <td><input type="submit" name="proj_mem_add_sub" value="Ajouter un membre" /></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr><td></td><td><input type="submit" value="Modifier" /></td></tr>
        </table>
      </form>
    </div>
<!-- END EDIT -->
