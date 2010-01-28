<?php

// mod/edit_demande.inc.php : Crée une nouvelle demande ou modifie les détails

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
    $st->execute($_GET['projet']);
    if(!($projet = $st->fetch(PDO::FETCH_ASSOC)))
        erreur_fatale('Erreur : projet invalide !');
}

// Vérification des permissions
if(!$utilisateur->autorise(PERM_MANAGE_REQUESTS) && isset($demande))
{
    $st = $db->prepare('SELECT * FROM association_utilisateurs_projets WHERE utilisateur=:utilisateur AND projet=:projet');
    $st->execute(array(
        ':projet' => $demande['projet'],
        ':utilisateur' => $utilisateur->userid()));
    if(!($row = $st->fetch(PDO::FETCH_ASSOC)) || $row['admin'] == 0)
        erreur_fatale("Erreur : vous n'avez pas la permission de modifier ce projet !");
}

//------------------------------------------------------------------------------
// TODO : Traitement des données reçues

//------------------------------------------------------------------------------
// Affichage du formulaire

$template->assign_vars(array(
    'DEM_PROJET' => isset($demande)?$demande['projet_nom']:$projet['nom'],
    'DEM_PRIO' => isset($demande)?$demande['priorite']:'1'));

// Modification
if(isset($demande))
{
    $template->assign_block_vars('EDIT', array(
        'DEM_ID' => $demande['id'],
        'DEM_TITRE' => $demande['titre'],
        'DESCRIPTION' => htmlentities($demande['description'])));

    // Affichage des versions
    $st = $db->prepare('SELECT * FROM versions WHERE projet=?');
    $st->execute(array($demande['projet']));
    while($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        $template->assign_block_vars(($row['id'] == $demande['version'])?'EDIT.VERSION_COURANTE':'EDIT.VERSION', array(
            'ID' => $row['id'],
            'NOM' => $row['nom']));
    }

    // Affichage des statuts
    foreach($conf['demande_statuts'] as $nb => $nom)
    {
        $template->assign_block_vars(($nb == $demande['statut'])?'EDIT.STATUT_COURANT':'EDIT.STATUT', array(
            'NUM' => $nb,
            'NOM' => $nom));
    }
}
// Ajout
else
    $template->assign_block_vars('ADD', array());

?>
