<?php

// TODO : mod/admin.inc.php : Ajoute ou modifie des utilisateurs

if(!isset($template))
    die();

// V�rification des permissions
$aff_projets = $utilisateur->autorise(PERM_MANAGE_PROJECT);
$aff_users = $utilisateur->autorise(PERM_MANAGE_USERS);
if(!$aff_projets && !$aff_users)
{
    erreur_fatale("Erreur : vous n'avez pas la permission de voir cette page !");
}

$st = $db->query(
'SELECT p.id, p.nom, p.open_demandes, COUNT(d.id) AS nb_demandes, COUNT(up.utilisateur) AS nb_membres
FROM projets p
    LEFT OUTER JOIN demandes d ON d.projet=p.id
    LEFT OUTER JOIN association_utilisateurs_projets up ON up.projet=p.id
GROUP BY p.id, p.nom, p.open_demandes');
$projets = $st->fetchAll(PDO::FETCH_ASSOC);

//------------------------------------------------------------------------------
// Projets

if($aff_projets)
{
    // Traitement des donn�es re�ues
    // TODO : Suppression d'un projet ?
    for($i = 0; $i < count($projets); $i++)
    {
        if(isset($_POST['proj_del' . $projets[$i]['id']]))
            ;
    }

    // Affichage du formulaire
    $template->assign_block_vars('ADMIN_PROJETS', array());
    if(count($projets) == 0)
        $template->assign_block_vars('ADMIN_PROJETS.ZERO_PROJETS', array());
    else for($i = 0; $i < count($projets); $i++)
    {
        $template->assign_block_vars('ADMIN_PROJETS.PROJET', array(
            'PARITE' => ($i%2==0)?'par':'impar',
            'ID' => $projets[$i]['id'],
            'NOM' => $projets[$i]['nom'],
            'OPEN_DEMANDES' => $projets[$i]['open_demandes'],
            'NB_MEMBRES' => $projets[$i]['nb_membres'],
            'NB_DEMANDES' => $projets[$i]['nb_demandes']));
    }
}

//------------------------------------------------------------------------------
// Utilisateurs

if($aff_users)
{
    // Traitement des donn�es re�ues

    // Affichage du formulaire
    $template->assign_block_vars('ADMIN_UTILISATEURS', array());
    $st = $db->query(
'SELECT u.pseudo, u.nom, u.promotion, u.flags, COUNT(d.id)
FROM utilisateurs u
    LEFT OUTER JOIN demandes d ON d.auteur=u.id
GROUP BY u.pseudo, u.nom, u.promotion, u.flags');
    if($st->rowCount() == 0)
        $template->assign_block_vars('ADMIN_UTILISATEURS.ZERO_UTILISATEURS', array());
    else
    {
        $i = 0;
        while($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $template->assign_block_vars('ADMIN_UTILISATEURS.UTILISATEUR', array(
                'PARITE' => ($i%2==0)?'par':'impar',
                'PSEUDO' => $row['pseudo'],
                'NOM' => $row['nom'],
                'PROMO' => $row['promotion']));
            if($row['flags'] & PERM_MANAGE_USERS)
                $template->assign_block_vars('ADMIN_UTILISATEURS.UTILISATEUR.PERMISSION', array('NOM' => 'Gestion des utilisateurs'));
            if($row['flags'] & PERM_MANAGE_PROJECT)
                $template->assign_block_vars('ADMIN_UTILISATEURS.UTILISATEUR.PERMISSION', array('NOM' => 'Gestion des projets'));
            if($row['flags'] & PERM_MANAGE_REQUESTS)
                $template->assign_block_vars('ADMIN_UTILISATEURS.UTILISATEUR.PERMISSION', array('NOM' => 'Gestion des demandes'));
            else if($row['flags'] & PERM_CREATE_REQUEST)
                $template->assign_block_vars('ADMIN_UTILISATEURS.UTILISATEUR.PERMISSION', array('NOM' => 'Cr�ation de demandes'));
        }
    }
}

?>
