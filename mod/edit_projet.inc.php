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

//------------------------------------------------------------------------------
// Traitement des données reçues

// Projet existant
if(isset($projet))
{
    // Ajout d'un membre
    if(isset($_POST['proj_mem_add_sub']) && isset($_POST['proj_mem_add']))
    {
        $utilisateur = intval($_POST['proj_mem_add'], 10);
        $admin = (isset($_POST['proj_mem_add_admin']) && $_POST['proj_mem_add_admin'] == 1)?1:0;
        $st = $db->prepare('SELECT id FROM utilisateurs WHERE id=?');
        $st->execute(array($utilisateur));
        if($st->rowCount() > 0)
        {
            $st = $db->prepare('SELECT utilisateur FROM association_utilisateurs_projets WHERE utilisateur=:utilisateur AND projet=:projet');
            $st->execute(array(
                ':utilisateur' => $utilisateur,
                ':projet' => $projet['id']));
            if($st->rowCount() == 0)
            {
                $st = $db->prepare('INSERT INTO association_utilisateurs_projets(utilisateur, projet, admin, derniere_activite) VALUES(:utilisateur, :projet, :admin, NOW())');
                $st->execute(array(
                    ':utilisateur' => $utilisateur,
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

    // Mise à jour de la description
    if(isset($_POST['proj_description']) && ($_POST['proj_description'] != $projet['description']) )
    {
        $st = $db->prepare('UPDATE projets SET description=:description WHERE id=:projet');
        $st->execute(array(
            ':projet' => $projet['id'],
            ':description' => htmlentities($_POST['proj_description'])));
    }
}
// Ajout d'un projet
else
{
    if(isset($_POST['proj_nom']) && isset($_POST['proj_description']))
    {
        $st = $db->prepare('SELECT * FROM projets WHERE nom=?');
        $st->execute(array($_POST['proj_nom']));
        if($st->rowCount() > 0)
        {
            $template->assign_block_vars('MSG_ERREUR', array(
                'DESCR' => 'Un projet avec ce nom existe déjà - veuillez en choisir un autre'));
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
        'NOM' => $projet['nom'],
        'DESCRIPTION' => $projet['description']));

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

?>
