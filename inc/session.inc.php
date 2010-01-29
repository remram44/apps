<?php

define('PERM_MANAGE_USERS', 1);
define('PERM_MANAGE_PROJECTS', 2);
define('PERM_MANAGE_REQUESTS', 4);

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

    function autorise($perm)
    { return ($this->flags & $perm) != 0; }

    function __construct()
    {
        global $conf;
        global $db;

        session_start();
        // Session d�j� ouverte
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
                    if($row['password'] == md5($passwd))
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
            if( ($row = $st->fetch(PDO::FETCH_ASSOC)) && ($row['password'] == md5($_POST['conn_mdp'])) )
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
            $this->userid = 0;
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
