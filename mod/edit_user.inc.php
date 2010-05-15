<?php

// mod/edit_user.inc.php : Ajoute ou modifie un utilisateur et change ses permissions

if(!isset($template))
    die();

// V�rification des permissions
if(!$utilisateur->autorise(PERM_MANAGE_USERS))
{
    erreur_fatale("Erreur : vous n'avez pas la permission de g�rer les utilisateurs !");
}

// R�cup�ration des donn�es de l'utilisateur, si 'id' est sp�cifi�
if(isset($_GET['id']))
{
    // Nom et description
    $user = $_GET['id'];
    $st = $db->prepare('SELECT * FROM utilisateurs WHERE id=?');
    $st->execute(array($user));
    if( !($user = $st->fetch(PDO::FETCH_ASSOC)) )
        erreur_fatale('Erreur : Projet invalide !');
}

//------------------------------------------------------------------------------
// Traitement des donn�es re�ues

// Utilisateur existant
if(isset($user))
{
    $edited_ok = true;

    // Changement du nom
    if(isset($_POST['user_nom']) && $_POST['user_nom'] != '' && $_POST['user_nom'] != $user['nom'])
    {
        $st = $db->prepare('UPDATE utilisateurs SET nom=:nom WHERE id=:utilisateur');
        $st->execute(array(
            ':utilisateur' => $user['id'],
            ':nom' => $_POST['user_nom']));
    }
    $_POST['user_nom'] = ''; unset($_POST['user_nom']);

    // Changement du pseudo
    if(isset($_POST['user_pseudo']) && $_POST['user_pseudo'] != '' && $_POST['user_pseudo'] != $user['pseudo'])
    {
        $st = $db->prepare('SELECT * FROM utilisateurs where PSEUDO=?');
        $st->execute(array($user['id']));
        if($st->rowCount() > 0)
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => 'Un utilisateur avec ce pseudo existe d�j� - impossible de changer le pseudo'));
            $edited_ok = false;
        }
        else
        {
            $st = $db->prepare('UPDATE utilisateurs SET pseudo=:pseudo WHERE id=:utilisateur');
            $st->execute(array(
                ':utilisateur' => $user['id'],
                ':pseudo' => $_POST['user_pseudo']));
        }
    }
    $_POST['user_pseudo'] = ''; unset($_POST['user_pseudo']);

    // Changement de la promotion
    if(isset($_POST['user_promo']) && intval($_POST['user_promo']) > 0 && $_POST['user_promo'] != $user['promotion'])
    {
        $st = $db->prepare('UPDATE utilisateurs SET promotion=:promotion WHERE id=:utilisateur');
        $st->execute(array(
            ':utilisateur' => $user['id'],
            ':promotion' => intval($_POST['user_promo'])));
    }
    $_POST['user_promo'] = ''; unset($_POST['user_promo']);

    // Changement du mot de passe
    if(isset($_POST['user_passwd1']) && isset($_POST['user_passwd2']) && $_POST['user_passwd1'] != '')
    {
        if($_POST['user_passwd1'] != $_POST['user_passwd2'])
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => 'Les mots de passe entr�s ne correspondent pas - mot de passe conserv�'));
            $edited_ok = false;
        }
        else
        {
            $st = $db->prepare('UPDATE utilisateurs SET password=:password WHERE id=:utilisateur');
            $st->execute(array(
                ':utilisateur' => $user['id'],
                ':password' => password_encrypt($_POST['user_passwd1'])));
        }
    }

    // Changement des permissions
    // Ici, on ne v�rifie pas avec isset() que le champ existe ; s'il n'existe pas, la case n'est pas coch�e
    // user_submit permet de v�rifier qu'on a valid� le formulaire (sans cette v�rification, l'affichage du formulaire ferait passer
    // flags � 0 avant qu'on ne valide)
    if(isset($_POST['user_submit']))
    {
        $perms = 0;
        if(isset($_POST['user_perm' . PERM_MANAGE_USERS]))
            $perms |= PERM_MANAGE_USERS;
        if(isset($_POST['user_perm' . PERM_MANAGE_PROJECT]))
            $perms |= PERM_MANAGE_PROJECT;
        if(isset($_POST['user_perm' . PERM_MANAGE_REQUESTS]))
            $perms |= PERM_MANAGE_REQUESTS;
        if($perms != $user['flags'])
        {
            $st = $db->prepare('UPDATE utilisateurs SET flags=:flags WHERE id=:utilisateur');
            $st->execute(array(
                ':utilisateur' => $user['id'],
                ':flags' => $perms));
            $user['flags'] = $perms;
        }
    }

    if($edited_ok && isset($_POST['proj_submit']))
    {
        if(!$conf['debug'])
        {
            header('HTTP/1.1 302 Moved Temporarily');
            header('Location: index.php?mod=edit_user&id=' . $user['id']);
        }
        $template->assign_block_vars('MSG_INFO', array(
            'DESCR' => 'Utilisateur modifi�'));
    }
}
// Ajout d'un utilisateur
else
{
    if(isset($_POST['user_nom']) && isset($_POST['user_pseudo']) && isset($_POST['user_promo']) && isset($_POST['user_passwd1']) && isset($_POST['user_passwd2'])
     && $_POST['user_nom'] != '' && $_POST['user_pseudo'] != '' && intval($_POST['user_promo']) > 0 && $_POST['user_passwd1'] != '' && $_POST['user_passwd2'] != '')
    {
        $st = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo=?');
        $st->execute(array($_POST['user_pseudo']));
        if($st->rowCount() > 0)
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => 'Un utilisateur avec ce pseudo existe d�j� - veuillez en choisir un autre'));
        }
        else if($_POST['user_passwd1'] != $_POST['user_passwd2'])
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => "Les mots de passe entr�s ne correspondent pas - l'utilisateur n'a pas �t� ajout�"));
        }
        else
        {
            $st = $db->prepare('INSERT INTO utilisateurs(nom, pseudo, promotion, password, template) VALUES(:nom, :pseudo, :promotion, :password, :template)');
            $st->execute(array(
                ':nom' => $_POST['user_nom'],
                ':pseudo' => $_POST['user_pseudo'],
                ':promotion' => intval($_POST['user_promo']),
                ':password' => password_encrypt($_POST['user_passwd1']),
                ':template' => $conf['default_template']));
            $st = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo=?');
            $st->execute(array($_POST['user_pseudo']));
            if($user = $st->fetch(PDO::FETCH_ASSOC))
            {
                $template->assign_block_vars('MSG_INFO', array(
                    'DESCR' => 'Utilisateur cr��'));
            }
        }
    }
}

//------------------------------------------------------------------------------
// Affichage du formulaire

// Utilisateur d�j� existant : les champs sont pr�remplis avec les donn�es actuelles, et on peut �diter les permissions
if(isset($user))
{
    // Nom et description
    $template->assign_block_vars('EDIT', array(
        'USERID' => $user['id'],
        'NOM' => str_replace('"', "\\\"", isset($_POST['user_nom'])?$_POST['user_nom']:$user['nom']),
        'PSEUDO' => str_replace('"', "\\\"", isset($_POST['user_pseudo'])?$_POST['user_pseudo']:$user['pseudo']),
        'PROMO' => str_replace('"', "\\\"", isset($_POST['user_promo'])?$_POST['user_promo']:$user['promotion'])));

    // Utilisateurs que l'on peut ajouter
    $st = $db->query('SELECT * FROM utilisateurs');
    while($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        $template->assign_block_vars('EDIT.AUTRE_UTILISATEUR', array(
            'USERID' => $row['id'],
            'NOM' => $row['nom'],
            'PSEUDO' => $row['pseudo'],
            'PROMOTION' => $row['promotion']));
    }
    // D�veloppeur par d�faut
    $template->assign_block_vars('EDIT.ROLE', array(
        'VALEUR' => 12,
        'NOM' => 'D�veloppeur'));
    $template->assign_block_vars('EDIT.ROLE', array(
        'VALEUR' => 14,
        'NOM' => 'Chef de projet'));
    $template->assign_block_vars('EDIT.ROLE', array(
        'VALEUR' => 8,
        'NOM' => 'Rapporteur'));

    $template->assign_block_vars('EDIT.PERMISSION_' . (($user['flags'] & PERM_MANAGE_USERS)?'ON':'OFF'), array(
        'NOM' => 'Gestion des utilisateurs',
        'NUM' => PERM_MANAGE_USERS));
    $template->assign_block_vars('EDIT.PERMISSION_' . (($user['flags'] & PERM_MANAGE_PROJECT)?'ON':'OFF'), array(
        'NOM' => 'Gestion des projets',
        'NUM' => PERM_MANAGE_PROJECT));
    $template->assign_block_vars('EDIT.PERMISSION_' . (($user['flags'] & PERM_MANAGE_REQUESTS)?'ON':'OFF'), array(
        'NOM' => 'Gestion des demandes',
        'NUM' => PERM_MANAGE_REQUESTS));
}
else
{
    $template->assign_block_vars('AJOUT', array(
        'NOM' => isset($_POST['user_nom'])?str_replace('"', "\\\"", $_POST['user_nom']):'',
        'PSEUDO' => isset($_POST['user_pseudo'])?str_replace('"', "\\\"", $_POST['user_pseudo']):'',
        'PROMO' => isset($_POST['user_promo'])?str_replace('"', "\\\"", $_POST['user_promo']):''));
}

?>
