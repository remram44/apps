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
if(!$utilisateur->autorise(PERM_MANAGE_PROJECTS) && isset($projet))
{
    $st = $db->prepare('SELECT * FROM association_utilisateurs_projets WHERE utilisateur=:utilisateur AND projet=:projet');
    $st->execute(array(
        ':utilisateur' => $utilisateur->userid(),
        ':projet' => $projet['id']));
    if($st->rowCount() == 0 || !($row = $st->fetch(PDO::FETCH_ASSOC)) || $row['admin'] == 0)
        erreur_fatale("Erreur : vous n'avez pas la permission de modifier ce projet !");
}
else if(!$utilisateur->autorise(PERM_MANAGE_PROJECTS))
    erreur_fatale("Erreur : vous n'avez pas la permission de créer un projet !");

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
        $admin = (isset($_POST['proj_mem_add_admin']) && $_POST['proj_mem_add_admin'] == 1)?1:0;
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
                $st = $db->prepare('INSERT INTO association_utilisateurs_projets(utilisateur, projet, admin, derniere_activite) VALUES(:utilisateur, :projet, :admin, NOW())');
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
            if( ($row['admin'] == 1  && $admin == 0)
             || ($row['admin'] == 0 && $admin == 1) )
            {
                $st2 = $db->prepare('UPDATE association_utilisateurs_projets SET admin=:admin WHERE utilisateur=:utilisateur AND projet=:projet');
                $st2->execute(array(
                    ':utilisateur' => $row['utilisateur'],
                    ':projet' => $projet['id'],
                    ':admin' => $admin));
            }
        }
    }

    // Changement du nom
    if(isset($_POST['proj_nom']) && $_POST['proj_nom'] != $projet['nom']
     && $_POST['proj_nom'] != '' && strlen($_POST['proj_nom']) < 50)
    {
        if(!$utilisateur->autorise(PERM_MANAGE_PROJECTS))
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
        header('HTTP/1.1 302 Moved Temporarily');
        header('Location: index.php?mod=projet&id=' . $projet['id']);
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
            $st = $db->prepare('SELECT id FROM projets WHERE nom=?');
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
        'NOM' => isset($_POST['proj_nom'])?$_POST['proj_nom']:str_replace('"', "\\\"", $projet['nom']),
        'DESCRIPTION' => isset($_POST['proj_description'])?$_POST['proj_description']:htmlentities($projet['description'])));

    // Membres
    $st = $db->prepare('SELECT u.id AS id, u.pseudo AS pseudo, u.nom AS nom, u.promotion AS promotion, a.admin AS admin FROM utilisateurs u INNER JOIN association_utilisateurs_projets a ON u.id=a.utilisateur WHERE a.projet=?');
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
            if($row['admin'])
                $template->assign_block_vars('EDIT.MEMBRE.ADMIN', array());
            else
                $template->assign_block_vars('EDIT.MEMBRE.NONADMIN', array());
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
}
else
{
    $template->assign_block_vars('AJOUT', array(
        'NOM' => isset($_POST['proj_nom'])?$_POST['proj_nom']:'',
        'DESCRIPTION' => isset($_POST['proj_description'])?$_POST['proj_description']:''));
}

// TODO : Modification des versions

?>
