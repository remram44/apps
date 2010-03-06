<?php

// mod/edit_projet.inc.php : Ajoute ou modifie un projet, ses versions, ses membres

if(!isset($template))
    die();

// Récupération des données du projet, si 'id' est spécifié
if(isset($_GET['id']))
{
    // Nom et description
    $projet = $_GET['id'];
    $st = $db->prepare('SELECT * FROM projets WHERE id=?');
    $st->execute(array($projet));
    if( !($projet = $st->fetch(PDO::FETCH_ASSOC)) )
        erreur_fatale('Erreur : Projet invalide !');
}

// Vérification des permissions
if(!$utilisateur->autorise(PERM_MANAGE_PROJECT, isset($projet)?$projet['id']:null))
{
    if(isset($projet))
        erreur_fatale("Erreur : vous n'avez pas la permission de modifier ce projet !");
    else
        erreur_fatale("Erreur : vous n'avez pas la permission de créer un projet !");
}

//------------------------------------------------------------------------------
// Traitement des données reçues

// Projet existant
if(isset($projet))
{
    $edited_ok = true;

    // Ajout d'un membre
    if(isset($_POST['proj_mem_add_sub']) && isset($_POST['proj_mem_add']))
    {
        $new_utilisateur = intval($_POST['proj_mem_add'], 10);
        $admin = isset($_POST['proj_mem_add_admin'])?intval($_POST['proj_mem_add_admin']):0;
        $st = $db->prepare('SELECT id FROM utilisateurs WHERE id=?');
        $st->execute(array($new_utilisateur));
        if($st->rowCount() > 0)
        {
            $st = $db->prepare('SELECT utilisateur FROM association_utilisateurs_projets WHERE utilisateur=:utilisateur AND projet=:projet');
            $st->execute(array(
                ':utilisateur' => $new_utilisateur,
                ':projet' => $projet['id']));
            if($st->rowCount() == 0)
            {
                $st = $db->prepare('INSERT INTO association_utilisateurs_projets(utilisateur, projet, flags, derniere_activite) VALUES(:utilisateur, :projet, :admin, NOW())');
                $st->execute(array(
                    ':utilisateur' => $new_utilisateur,
                    ':projet' => $projet['id'],
                    ':admin' => $admin));
            }
        }
    }

    // Mise à jour des membres
    $st = $db->prepare('SELECT * FROM association_utilisateurs_projets WHERE projet=?');
    $st->execute(array($projet['id']));
    while($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        // Suppression
        if(isset($_POST['proj_mem_rem' . $row['utilisateur']]))
        {
            $st2 = $db->prepare('DELETE FROM association_utilisateurs_projets WHERE utilisateur=:utilisateur AND projet=:projet');
            $st2->execute(array(
                ':utilisateur' => $row['utilisateur'],
                ':projet' => $projet['id']));
        }

        // Mise à jour du rang
        if(isset($_POST['proj_mem_admin' . $row['utilisateur']]))
        {
            $admin = intval($_POST['proj_mem_admin' . $row['utilisateur']]);
            if($row['flags'] != $admin)
            {
                $st2 = $db->prepare('UPDATE association_utilisateurs_projets SET flags=:admin WHERE utilisateur=:utilisateur AND projet=:projet');
                $st2->execute(array(
                    ':utilisateur' => $row['utilisateur'],
                    ':projet' => $projet['id'],
                    ':admin' => $admin));
                $row['flags'] = $admin;
            }
        }
    }

    // Changement du statut de l'ajout de demandes
    if(isset($_POST['proj_open_demandes']) && $_POST['proj_open_demandes'] != '' && $_POST['proj_open_demandes'] != $projet['open_demandes'])
    {
        $st = $db->prepare('UPDATE projets SET open_demandes=:open_demandes WHERE id=:projet');
        $st->execute(array(
            ':projet' => $projet['id'],
            ':open_demandes' => intval($_POST['proj_open_demandes'])));
    }

    // Changement du nom
    if(isset($_POST['proj_nom']) && $_POST['proj_nom'] != $projet['nom']
     && $_POST['proj_nom'] != '' && strlen($_POST['proj_nom']) < 50)
    {
        if(!$utilisateur->autorise(PERM_MANAGE_PROJECT))
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => "Erreur : vous n'avez pas la permission de changer le nom d'un projet"
                ));
            $edited_ok = false;
        }
        else
        {
            $st = $db->prepare('SELECT * FROM projets WHERE nom=?');
            $st->execute(array($_POST['proj_nom']));
            if($st->rowCount() > 0)
            {
                $template->assign_block_vars('MSG_ERREUR', array(
                    'DESCR' => 'Un projet avec ce nom existe déjà - impossible de changer le nom'));
                $edited_ok = false;
            }
            else
            {
                $st = $db->prepare('UPDATE projets SET nom=:nom WHERE id=:projet');
                $st->execute(array(
                    ':projet' => $projet['id'],
                    ':nom' => $_POST['proj_nom']));
            }
        }
    }
    $_POST['proj_nom'] = ''; unset($_POST['proj_nom']);

    // Mise à jour de la description
    if(isset($_POST['proj_description']) && ($_POST['proj_description'] != $projet['description']) )
    {
        $st = $db->prepare('UPDATE projets SET description=:description WHERE id=:projet');
        $st->execute(array(
            ':projet' => $projet['id'],
            ':description' => $_POST['proj_description']));
    }

    if($edited_ok && isset($_POST['proj_submit']))
    {
        if(!$conf['debug'])
        {
            header('HTTP/1.1 302 Moved Temporarily');
            header('Location: index.php?mod=projet&id=' . $projet['id']);
        }
        $template->assign_block_vars('MSG_INFO', array(
            'DESCR' => 'Projet modifié ; <a href="index.php?mod=projet&amp;id=' . $projet['id'] . '">cliquez ici</a> pour le consulter'));
    }
}
// Ajout d'un projet
else
{
    if(isset($_POST['proj_nom']) && isset($_POST['proj_description'])
     && $_POST['proj_nom'] != "" && strlen($_POST['proj_nom']) < 50)
    {
        $st = $db->prepare('SELECT * FROM projets WHERE nom=?');
        $st->execute(array($_POST['proj_nom']));
        if($st->rowCount() > 0)
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => 'Un projet avec ce nom existe déjà - veuillez en choisir un autre'));
        }
        else
        {
            $st = $db->prepare('INSERT INTO projets(nom, description) VALUES(:nom, :description)');
            $st->execute(array(
                ':nom' => $_POST['proj_nom'],
                ':description' => $_POST['proj_description']));
            $st = $db->prepare('SELECT * FROM projets WHERE nom=?');
            $st->execute(array($_POST['proj_nom']));
            if($projet = $st->fetch(PDO::FETCH_ASSOC))
            {
                $template->assign_block_vars('MSG_INFO', array(
                    'DESCR' => 'Projet créé ; <a href="index.php?mod=projet&amp;id=' . $projet['id'] . '">cliquez ici</a> pour le consulter'));
            }
        }
    }
}

//------------------------------------------------------------------------------
// Affichage du formulaire

// Projet déjà existant : on ne peut pas changer le nom, les champs sont préremplis avec les données actuelles, et on peut éditer la liste des membres
if(isset($projet))
{
    // Nom et description
    $template->assign_block_vars('EDIT', array(
        'PROJ_ID' => $projet['id'],
        'NOM' => str_replace('"', "\\\"", isset($_POST['proj_nom'])?$_POST['proj_nom']:$projet['nom']),
        'DESCRIPTION' => isset($_POST['proj_description'])?$_POST['proj_description']:htmlentities($projet['description'])));

    // Membres
    $st = $db->prepare('SELECT u.id AS id, u.pseudo AS pseudo, u.nom AS nom, u.promotion AS promotion, a.flags AS flags FROM utilisateurs u INNER JOIN association_utilisateurs_projets a ON u.id=a.utilisateur WHERE a.projet=?');
    $st->execute(array($projet['id']));
    if($st->rowCount() == 0)
        $template->assign_block_vars('EDIT.ZERO_MEMBRES', array());
    else
    {
        while($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $template->assign_block_vars('EDIT.MEMBRE', array(
                'NOM' => $row['nom'],
                'PSEUDO' => $row['pseudo'],
                'USERID' => $row['id'],
                'PROMO' => $row['promotion']));
            if($row['flags'] == 1)
                $template->assign_block_vars('EDIT.MEMBRE.ADMIN', array());
            else
                $template->assign_block_vars('EDIT.MEMBRE.NONADMIN', array());
            $role = $row['flags'];
            if($role & PERM_MANAGE_PROJECT) $role = 1;
            else if($role & PERM_MANAGE_REQUESTS) $role = 2;
            else if($role & PERM_CREATE_REQUEST) $role = 3;
            else $role = 0;
            $template->assign_block_vars('EDIT.MEMBRE.ROLE' . (($role == 1)?'_SELECTED':''), array(
                'VALEUR' => PERM_CREATE_REQUEST | PERM_MANAGE_REQUESTS | PERM_MANAGE_PROJECT | PERM_ADD_COMMENT,
                'NOM' => 'Admin'));
            $template->assign_block_vars('EDIT.MEMBRE.ROLE' . (($role == 2)?'_SELECTED':''), array(
                'VALEUR' => PERM_CREATE_REQUEST | PERM_MANAGE_REQUESTS | PERM_ADD_COMMENT,
                'NOM' => 'Développeur'));
            $template->assign_block_vars('EDIT.MEMBRE.ROLE' . (($role == 3)?'_SELECTED':''), array(
                'VALEUR' => PERM_CREATE_REQUEST | PERM_ADD_COMMENT,
                'NOM' => 'Rapporteur'));
        }
    }

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
    // Développeur par défaut
    $template->assign_block_vars('EDIT.ROLE', array(
        'VALEUR' => PERM_MANAGE_REQUESTS | PERM_CREATE_REQUEST,
        'NOM' => 'Développeur'));
    $template->assign_block_vars('EDIT.ROLE', array(
        'VALEUR' => PERM_MANAGE_REQUESTS | PERM_CREATE_REQUEST | PERM_MANAGE_PROJECT,
        'NOM' => 'Admin'));
    $template->assign_block_vars('EDIT.ROLE', array(
        'VALEUR' => PERM_CREATE_REQUEST,
        'NOM' => 'Rapporteur'));

    $template->assign_block_vars('EDIT.OPEN_DEMANDES' . (($projet['open_demandes'] == 0)?'_SELECTED':''), array(
        'VALEUR' => 0,
        'NOM' => 'Utilisateurs autorisés'));
    $template->assign_block_vars('EDIT.OPEN_DEMANDES' . (($projet['open_demandes'] == 1)?'_SELECTED':''), array(
        'VALEUR' => 1,
        'NOM' => 'Tous les utilisateurs enregistrés'));
    $template->assign_block_vars('EDIT.OPEN_DEMANDES' . (($projet['open_demandes'] == 2)?'_SELECTED':''), array(
        'VALEUR' => 2,
        'NOM' => 'Tout le monde (anonymes compris)'));
}
else
{
    $template->assign_block_vars('AJOUT', array(
        'NOM' => isset($_POST['proj_nom'])?str_replace('"', "\\\"", $_POST['proj_nom']):'',
        'DESCRIPTION' => isset($_POST['proj_description'])?htmlentities($_POST['proj_description']):''));
}

// TODO 2 : Modification des versions

?>
