<?php

// mod/edit_demande.inc.php : Cr�e une nouvelle demande ou modifie les d�tails

// TODO 2 : choix de la priorit�

if(!isset($template))
    die();

// R�cup�ration des donn�es de la demande, si 'id' est sp�cifi�
if(isset($_GET['id']))
{
    $st = $db->prepare(
'SELECT d.id, d.projet, d.version, d.titre, d.auteur, d.description, d.priorite, d.statut, d.creation, p.nom AS projet_nom, v.nom AS version_nom
FROM demandes d
    INNER JOIN projets p ON p.id=d.projet
    LEFT OUTER JOIN versions v ON v.id=d.version
WHERE d.id=?');
    $st->execute(array($_GET['id']));
    if(! ($demande = $st->fetch(PDO::FETCH_ASSOC)) )
        erreur_fatale('Erreur : demande invalide !');
}
else
{
    if(!isset($_GET['projet']) || intval($_GET['projet']) == 0)
        erreur_fatale('Erreur : projet non sp�cifi�');
    $st = $db->prepare('SELECT * FROM projets WHERE id=?');
    $st->execute(array($_GET['projet']));
    if(!($projet = $st->fetch(PDO::FETCH_ASSOC)))
        erreur_fatale('Erreur : projet invalide !');
}

// V�rification des permissions
if( (isset($demande) && !$utilisateur->autorise(PERM_MANAGE_REQUESTS, $demande['projet']))
 || (!isset($demande) &&!$utilisateur->autorise(PERM_CREATE_REQUEST, $projet['id'])) )
{
    if(isset($demande))
        erreur_fatale("Erreur : vous n'avez pas la permission de modifier cette demande !");
    else
        erreur_fatale("Erreur : vous n'avez pas la permission de cr�er une demande sur ce projet !");
}

//------------------------------------------------------------------------------
// Traitement des donn�es re�ues

// Mise � jour
if(isset($demande))
{
    $edited_ok = true;

    // Changement de titre
    if(isset($_POST['dem_titre']) && $_POST['dem_titre'] != $demande['titre']
     && $_POST['dem_titre'] != '')
    {
        $st = $db->prepare('UPDATE demandes SET titre=:titre WHERE id=:id');
        $st->execute(array(
            ':id' => $demande['id'],
            ':titre' => $_POST['dem_titre']));
    }

    // Changement de statut
    if(isset($_POST['dem_statut']) && $_POST['dem_statut'] != $demande['statut']
     && $_POST['dem_statut'] != '' && isset($conf['demande_statuts'][intval($_POST['dem_statut'])]))
    {
        $st = $db->prepare('UPDATE demandes SET statut=:statut WHERE id=:id');
        $st->execute(array(
            ':id' => $demande['id'],
            ':statut' => $_POST['dem_statut']));
    }

    // Modification de la description
    if(isset($_POST['dem_descr']) && $_POST['dem_descr'] != $demande['description'] && $_POST['dem_descr'] != '')
    {
        $st = $db->prepare('UPDATE demandes SET description=:description WHERE id=:id');
        $st->execute(array(
            ':id' => $demande['id'],
            ':description' => $_POST['dem_descr']));
    }

    // Changement de la version cible
    if(isset($_POST['dem_version']) && ($_POST['dem_version'] != '0' || $demande['version'] != NULL) && $_POST['dem_version'] != $demande['version'])
    {
        if($_POST['dem_version'] != '0')
        {
            $st = $db->prepare('SELECT * FROM versions WHERE projet=:projet AND id=:version');
            $st->execute(array(
                ':projet' => $demande['projet'],
                ':version' => $_POST['dem_version']));
            if($st->rowCount() != 1)
            {
                $template->assign_block_vars('MSG_ERREUR', array(
                    'DESCR' => 'Version cible invalide'));
                $edited_ok = false;
            }
            else
            {
                $st2 = $db->prepare('UPDATE demandes SET version=:version WHERE id=:id');
                $st2->execute(array(
                    ':id' => $demande['id'],
                    ':version' => $_POST['dem_version']));
            }
        }
        else
        {
            $st2 = $db->prepare('UPDATE demandes SET version=NULL WHERE id=?');
            $st2->execute(array($demande['id']));
        }
    }

    if($edited_ok && isset($_POST['dem_submit']))
    {
        if(!$conf['debug'])
        {
            header('HTTP/1.1 302 Moved Temporarily');
            header('Location: index.php?mod=demande&id=' . $demande['id']);
        }
        $template->assign_block_vars('MSG_INFO', array(
            'DESCR' => 'Demande modifi�e ; <a href="index.php?mod=demande&amp;id=' . $demande['id'] . '">cliquez ici</a> pour la consulter'));
    }
}
// Ajout
else
{
    if(isset($_POST['dem_titre']) && $_POST['dem_titre'] != '' && isset($_POST['dem_descr']) && $_POST['dem_descr'] != '')
    {
        $st = $db->prepare('INSERT INTO demandes(projet, titre, auteur, description, priorite, statut, creation, derniere_activite) VALUES(:projet, :titre, :auteur, :description, 1, 1, NOW(), NOW())');
        $st->execute(array(
            ':projet' => $projet['id'],
            ':titre' => $_POST['dem_titre'],
            ':auteur' => $utilisateur->userid(),
            ':description' => $_POST['dem_descr']));
        $st = $db->prepare(
'SELECT d.id, d.projet, d.version, d.titre, d.auteur, d.description, d.priorite, d.statut, d.creation, p.nom AS projet_nom, v.nom AS version_nom
FROM demandes d
    INNER JOIN projets p ON p.id=d.projet
    LEFT OUTER JOIN versions v ON v.id=d.version
WHERE d.projet=:projet AND d.titre=:titre AND d.auteur=:auteur');
        $st->execute(array(
            ':projet' => $projet['id'],
            ':titre' => $_POST['dem_titre'],
            ':auteur' => $utilisateur->userid()));
        if($demande = $st->fetch(PDO::FETCH_ASSOC))
            $template->assign_block_vars('MSG_INFO', array(
                'DESCR' => 'Demande ajout�e ; <a href="index.php?mod=demande&amp;id=' . $demande['id'] . '">cliquez ici</a> pour la consulter'));
    }
}

//------------------------------------------------------------------------------
// Affichage du formulaire

$template->assign_vars(array(
    'DEM_PROJET' => isset($demande)?$demande['projet_nom']:htmlentities($projet['nom']),
    'DEM_PROJET_ID' => isset($demande)?$demande['projet']:$projet['id'],
    'DEM_PRIO' => isset($demande)?$demande['priorite']:'1'));

// Modification
if(isset($demande))
{
    $template->assign_block_vars('EDIT', array(
        'DEM_ID' => $demande['id'],
        'DEM_TITRE' => str_replace("\"", "\\\"", $demande['titre']),
        'DESCRIPTION' => htmlentities($demande['description'])));

    // Affichage des versions
    $st = $db->prepare('SELECT * FROM versions WHERE projet=?');
    $st->execute(array($demande['projet']));
    $template->assign_block_vars((intval($demande['version'])==0)?'EDIT.VERSION_COURANTE':'EDIT.VERSION', array(
        'ID' => '0',
        'NOM' => '(aucune)'));
    while($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        $template->assign_block_vars(($row['id'] == $demande['version'])?'EDIT.VERSION_COURANTE':'EDIT.VERSION', array(
            'ID' => $row['id'],
            'NOM' => htmlentities($row['nom'])));
    }

    // Affichage des statuts
    foreach($conf['demande_statuts'] as $nb => $nom)
    {
        $template->assign_block_vars(($nb == $demande['statut'])?'EDIT.STATUT_COURANT':'EDIT.STATUT', array(
            'NB' => $nb,
            'NOM' => $nom));
    }
}
// Ajout
else
    $template->assign_block_vars('AJOUT', array());

?>
