<?php

// mod/edit_demande.inc.php : Crée une nouvelle demande ou modifie les détails

// TODO 2 : choix de la priorité

if(!isset($template))
    die();

// Récupération des données de la demande, si 'id' est spécifié
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
        erreur_fatale('Erreur : projet non spécifié');
    $st = $db->prepare('SELECT * FROM projets WHERE id=?');
    $st->execute(array($_GET['projet']));
    if(!($projet = $st->fetch(PDO::FETCH_ASSOC)))
        erreur_fatale('Erreur : projet invalide !');
}

// Vérification des permissions
if( (isset($demande) && !$utilisateur->autorise(PERM_MANAGE_REQUESTS, $demande['projet']))
 || (!isset($demande) &&!$utilisateur->autorise(PERM_CREATE_REQUEST, $projet['id'])) )
{
    if(isset($demande))
        erreur_fatale("Erreur : vous n'avez pas la permission de modifier cette demande !");
    else
        erreur_fatale("Erreur : vous n'avez pas la permission de créer une demande sur ce projet !");
}

//------------------------------------------------------------------------------
// Traitement des données reçues

// Mise à jour
if(isset($demande))
{
    // 0 : rien de changé
    // 1 : toutes modifications réussies
    // 2 : erreur
    $edited = 0;
    $modifs = '';

    // Changement de la version cible
    if($edited != 2 && isset($_POST['dem_version']) && ($_POST['dem_version'] != '0' || $demande['version'] != NULL) && $_POST['dem_version'] != $demande['version'])
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
                $edited = 2;
            }
            else
            {
                $version = $st->fetch(PDO::FETCH_ASSOC);
                $st2 = $db->prepare('UPDATE demandes SET version=:version WHERE id=:id');
                $st2->execute(array(
                    ':id' => $demande['id'],
                    ':version' => $_POST['dem_version']));
                $edited = 1;
                $modifs .= '**version cible** changée en //' . $version['nom'] . "//\n";
            }
        }
        else
        {
            $st2 = $db->prepare('UPDATE demandes SET version=NULL WHERE id=?');
            $st2->execute(array($demande['id']));
            $edited = 1;
            $modifs .= "**version cible** retirée\n";
        }
    }

    // Changement de titre
    if($edited != 2 && isset($_POST['dem_titre']) && $_POST['dem_titre'] != $demande['titre']
     && $_POST['dem_titre'] != '')
    {
        $st = $db->prepare('UPDATE demandes SET titre=:titre WHERE id=:id');
        $st->execute(array(
            ':id' => $demande['id'],
            ':titre' => $_POST['dem_titre']));
        $edited = 1;
        $modifs .= '**titre** changé en //' . $_POST['dem_titre'] . "//\n";
    }

    // Changement de statut
    if($edited != 2 && isset($_POST['dem_statut']) && $_POST['dem_statut'] != $demande['statut']
     && $_POST['dem_statut'] != '' && isset($conf['demande_statuts'][intval($_POST['dem_statut'])]))
    {
        $st = $db->prepare('UPDATE demandes SET statut=:statut WHERE id=:id');
        $st->execute(array(
            ':id' => $demande['id'],
            ':statut' => $_POST['dem_statut']));
        $edited = 1;
        $modifs .= '**statut** changé en //' . $conf['demande_statuts'][intval($_POST['dem_statut'])] . "//\n";
    }

    // Modification de la description
    if($edited != 2 && isset($_POST['dem_descr']) && $_POST['dem_descr'] != $demande['description'] && $_POST['dem_descr'] != '')
    {
        $st = $db->prepare('UPDATE demandes SET description=:description WHERE id=:id');
        $st->execute(array(
            ':id' => $demande['id'],
            ':description' => $_POST['dem_descr']));
        $edited = 1;
        $modifs .= "**description** modifiée\n";
    }

    if($edited == 1)
    {
        // Ajout d'un commentaire résumant les modifications
        $st = $db->prepare('INSERT INTO commentaires(auteur, demande, texte, creation, resume) VALUES(:auteur, :demande, :texte, NOW(), 1)');
        $st->execute(array(
            ':auteur' => $utilisateur->userid(),
            ':demande' => $demande['id'],
            ':texte' => $modifs));

        if(!$conf['debug'])
        {
            header('HTTP/1.1 302 Moved Temporarily');
            header('Location: index.php?mod=demande&id=' . $demande['id']);
        }
        $template->assign_block_vars('MSG_INFO', array(
            'DESCR' => 'Demande modifiée ; <a href="index.php?mod=demande&amp;id=' . $demande['id'] . '">cliquez ici</a> pour la consulter'));
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
WHERE d.projet=:projet AND d.titre=:titre');
        $st->execute(array(
            ':projet' => $projet['id'],
            ':titre' => $_POST['dem_titre']));
        // Si la demande a été ajoutée correctement, on l'affiche (on quitte donc la page d'édition)
        if($demande = $st->fetch(PDO::FETCH_ASSOC))
        {
            if(!$conf['debug'])
            {
                header('HTTP/1.1 302 Moved Temporarily');
                header('Location: index.php?mod=demande&id=' . $demande['id']);
            }
            $template->assign_block_vars('MSG_INFO', array(
                'DESCR' => 'Demande ajoutée ; <a href="index.php?mod=demande&amp;id=' . $demande['id'] . '">cliquez ici</a> pour la consulter'));
        }
    }
}

//------------------------------------------------------------------------------
// Affichage du formulaire

$template->assign_vars(array(
    'DEM_PROJET' => isset($demande)?$demande['projet_nom']:htmlentities($projet['nom'], ENT_COMPAT, 'UTF-8'),
    'DEM_PROJET_ID' => isset($demande)?$demande['projet']:$projet['id'],
    'DEM_PRIO' => isset($demande)?$demande['priorite']:'1'));

// Modification
if(isset($demande))
{
    $template->assign_block_vars('EDIT', array(
        'DEM_ID' => $demande['id'],
        'DEM_TITRE' => str_replace("\"", "\\\"", $demande['titre']),
        'DESCRIPTION' => htmlentities($demande['description'], ENT_COMPAT, 'UTF-8')));

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
            'NOM' => htmlentities($row['nom'], ENT_COMPAT, 'UTF-8')));
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
