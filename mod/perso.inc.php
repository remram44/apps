<?php

// mod/perso.inc.php : Permet de modifier les préférences (mot de passe, templates)

if(!isset($template))
    die();

if($utilisateur->estAnonyme())
    erreur_fatale("Erreur : vous n'êtes pas connecté !");

$template->assign_vars(array(
    'PSEUDO' => $utilisateur->pseudo(),
    'NOM' => $utilisateur->nom(),
    'TEMPLATE' => $utilisateur->template()));

// TODO : Choix du design

// Changement de mot de passe
if( (isset($_POST['chg_mdp1']) && $_POST['chg_mdp1'] != '')
 || (isset($_POST['chg_mdp2']) && $_POST['chg_mdp2'] != '') )
{
    // Confirmation du nouveau mot de passe
    if(!isset($_POST['chg_mdp1']) || !isset($_POST['chg_mdp2'])
     || ($_POST['chg_mdp1'] != $_POST['chg_mdp2']) )
    {
        $template->assign_block_vars('ERREUR', array(
            'TEXTE' => 'Les mots de passe ne correspondent pas.'));
    }
    // Vérification du nombre de caractères
    else if( (strlen($_POST['chg_mdp1']) < 4) || (strlen($_POST['chg_mdp1']) > 26) )
    {
        $template->assign_block_vars('ERREUR', array(
            'TEXTE' => 'Le mot de passe choisi est invalide.'));
    }
    else
    {
        // Vérification de l'ancien mot de passe
        $st = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo=?');
        $st->execute(array($utilisateur->pseudo()));
        if( !($row = $st->fetch(PDO::FETCH_ASSOC)) || ($row['password'] != md5($_POST['chg_mdp'])) )
        {
            $template->assign_block_vars('ERREUR', array(
                'TEXTE' => 'Vous devez entrer votre mot de passe actuel pour confirmation.'));
        }
        else
        {
            // Modification
            $st = $db->prepare('UPDATE utilisateurs SET password = :mdp WHERE pseudo = :pseudo');
            $st->execute(array(
                ':pseudo' => $utilisateur->pseudo(),
                ':mdp' => md5($_POST['chg_mdp1'])));
            $template->assign_block_vars('INFO', array(
                'TEXTE' => 'Votre mot de passe a été changé.'));
        }
    }
}

?>
