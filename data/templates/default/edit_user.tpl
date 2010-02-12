<!-- BEGIN AJOUT -->
    <div class="box">
      <h1>Ajout d'un utilisateur</h1>
    </div>
    <div class="box">
      <p>Après la création du nouvel utilisateur, vous pourrez modifier ses permissions via cette interface.</p>
      <form method="post" action="index.php?mod=edit_user">
        <table>
          <tr><th>Pseudo :</th><td><input type="text" name="user_pseudo" value="{AJOUT.PSEUDO}" /></td></tr>
          <tr><th>Nom :</th><td><input type="text" name="user_nom" value="{AJOUT.NOM}" /></td></tr>
          <tr><th>Promotion :</th><td><input type="text" name="user_promo" value="{AJOUT.PROMO}" /></td></tr>
          <tr><th>Mot de passe :</th><td><input type="password" name="user_passwd1" /></td></tr>
          <tr><th>Confirmez le mot de passe :</th><td><input type="password" name="user_passwd2" /></td></tr>
          <tr><th></th><td><input type="submit" value="Ajouter" /></td></tr>
        </table>
      </form>
    </div>
<!-- END AJOUT -->
<!-- BEGIN EDIT -->
    <div class="box">
      <h1>Modification d'un utilisateur</h1>
    </div>
    <div class="box">
      <form method="post" action="index.php?mod=edit_user&amp;id={EDIT.USERID}">
        <table>
          <tr><th>Pseudo :</th><td><input type="text" name="user_pseudo" value="{EDIT.PSEUDO}" /></td></tr>
          <tr><th>Nom :</th><td><input type="text" name="user_nom" value="{EDIT.NOM}" /></td></tr>
          <tr><th>Promotion :</th><td><input type="text" name="user_promo" value="{EDIT.PROMO}" /></td></tr>
          <tr><th>Mot de passe (si vous souhaitez changer) :</th><td><input type="password" name="user_passwd1" /></td></tr>
          <tr><th>Confirmez le mot de passe :</th><td><input type="password" name="user_passwd2" /></td></tr>
          <tr><th>Permissions :</th><td><ul>
<!-- BEGIN PERMISSION_ON -->
            <li><label>{EDIT.PERMISSION_ON.NOM} <input type="checkbox" name="user_perm{EDIT.PERMISSION_ON.NUM}" checked /></label></li>
<!-- END PERMISSION_ON -->
<!-- BEGIN PERMISSION_OFF -->
            <li><label>{EDIT.PERMISSION_OFF.NOM} <input type="checkbox" name="user_perm{EDIT.PERMISSION_OFF.NUM}" /></label></li>
<!-- END PERMISSION_OFF -->
          </ul></td></tr>
          <tr><td></td><td><input type="submit" name="user_submit" value="Modifier" /></td></tr>
        </table>
      </form>
    </div>
<!-- END EDIT -->
