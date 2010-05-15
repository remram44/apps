<?php

define('PERM_MANAGE_USERS', 1);    // globale
define('PERM_MANAGE_PROJECT', 2);  // globale et par projet
define('PERM_MANAGE_REQUESTS', 4); // globale et par projet
define('PERM_CREATE_REQUEST', 8);  // globale et par projet
define('PERM_ADD_COMMENT', 16);     // globale et par projet

include 'passwords.inc.php';

class Utilisateur {

    var $pseudo;
    var $nom;
    var $userid;
    var $template;
    var $flags;

    function pseudo()
    { return $this->pseudo; }

    function nom()
    { return $this->nom; }

    function estAnonyme()
    { return $this->pseudo == 'Anonyme'; }

    function userid()
    { return $this->userid; }

    function template()
    { return $this->template; }

    function autorise($perm, $projet = null)
    {
        global $db;

        if($this->flags & $perm != 0)
            return true;
        if($projet == null)
            return false;
        if($perm == PERM_CREATE_REQUEST || $perm == PERM_ADD_COMMENT)
        {
            $st = $db->prepare('SELECT open_demandes, open_commentaires FROM projets WHERE id=?');
            $st->execute(array($projet));
            if($st->rowCount() == 0 || !($row = $st->fetch(PDO::FETCH_ASSOC)))
                return false;
            else if($perm == PERM_CREATE_REQUEST)
            {
                // open_demandes : statut des créations de demandes sur le projet
                // 0 : utilisateurs autorisés seulement
                // 1 : tous les utilisateurs enregistrés
                // 2 : tout le monde (possible anonymement)
                if($row['open_demandes'] == 2)
                    return true;
                else if($row['open_demandes'] == 1 && !$this->estAnonyme())
                    return true;
            }
            else if($perm == PERM_ADD_COMMENT)
            {
                // open_commentaires : statut des ajouts de commentaires sur le projet
                // 0 : utilisateurs autorisés seulement
                // 1 : tous les utilisateurs enregistrés
                // 2 : tout le monde (possible anonymement)
                if($row['open_commentaires'] == 2)
                    return true;
                else if($row['open_commentaires'] == 1 && !$this->estAnonyme())
                    return true;
            }
        }

        $st = $db->prepare('SELECT flags FROM association_utilisateurs_projets WHERE utilisateur=:utilisateur AND projet=:projet');
        $st->execute(array(
            ':utilisateur' => $this->userid,
            ':projet' => $projet));
        if($st->rowCount() == 0 || !($row = $st->fetch(PDO::FETCH_ASSOC)))
            return false;
        else
            return ($row['flags'] & $perm) != 0;
    }

    function __construct()
    {
        global $conf;
        global $db;

        session_start();
        // Session déjà ouverte
        if(isset($_SESSION['pseudo']))
        {
            $this->pseudo   = $_SESSION['pseudo'];
            $this->nom      = $_SESSION['nom'];
            $this->userid   = $_SESSION['userid'];
            $this->template = $_SESSION['template'];
            $this->flags    = $_SESSION['flags'];
        }
        // Cookie client
        else if(isset($_COOKIE['remember']))
        {
            $pos = strpos($_COOKIE['remember'], ':');
            if($pos !== false)
            {
                $pseudo = substr($_COOKIE['remember'], 0, $pos);
                $passwd = substr($_COOKIE['remember'], $pos+1);
                $st = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo=?');
                $st->execute(array($pseudo));
                if($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    if(password_verify($row['password'], $passwd))
                    {
                        $this->pseudo   = $_SESSION['pseudo']   = $pseudo;
                        $this->nom      = $_SESSION['nom']      = $row['nom'];
                        $this->userid   = $_SESSION['userid']   = $row['id'];
                        $this->template = $_SESSION['template'] = $row['template'];
                        $this->flags    = $_SESSION['flags']    = $row['flags'];
                    }
                }
            }
        }
        // Connexion via le formulaire
        else if(isset($_POST['conn_nom']) && isset($_POST['conn_mdp']))
        {
            $st = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo=?');
            $st->execute(array($_POST['conn_nom']));
            if( ($row = $st->fetch(PDO::FETCH_ASSOC)) && password_verify($row['password'], $_POST['conn_mdp']) )
            {
                $this->pseudo   = $_SESSION['pseudo']   = $_POST['conn_nom'];
                $this->nom      = $_SESSION['nom']      = $row['nom'];
                $this->userid   = $_SESSION['userid']   = $row['id'];
                $this->template = $_SESSION['template'] = $row['template'];
                $this->flags    = $_SESSION['flags']    = $row['flags'];
            }
        }

        // Fallback : anonyme
        if($this->pseudo == '')
        {
            $this->pseudo = 'Anonyme';
            $this->nom = 'Anonyme';
            $this->userid = null;
            $this->template = $conf['default_template'];
            $this->flags = 0;
        }
    }

    function deconnecte()
    {
        session_destroy();
        setcookie('remember', '', time() - 3600);
    }

    function update()
    {
        global $db;
        $st = $db->prepare('SELECT * FROM utilisateurs WHERE id=?');
        $st->execute(array($this->userid));
        if($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $this->pseudo   = $_SESSION['pseudo']   = $row['pseudo'];
            $this->nom      = $_SESSION['nom']      = $row['nom'];
            $this->userid   = $_SESSION['userid']   = $row['id'];
            $this->template = $_SESSION['template'] = $row['template'];
            $this->flags    = $_SESSION['flags']    = $row['flags'];
        }
    }

}

?>
