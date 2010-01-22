<?php

class Utilisateur {

    var $pseudo;
    var $nom;
    var $userid;
    var $template;

    function pseudo()
    { return $this->pseudo; }

    function nom()
    { return $this->nom; }

    function estAnonyme()
    { return $this->pseudo == 'Anonyme'; }

    function template()
    { return $this->template; }

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
        }
        // Cookie client
        else if(isset($_COOKIE['remember']))
        {
            $infos = explode(':', $_COOKIE['remember']);
            if(count($infos) == 2)
            {
                $pseudo = $infos[0];
                $passwd = $infos[1];
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
                    }
                }
            }
        }
        // Connexion via le formulaire
        else if(isset($_POST['conn_nom']) && isset($_POST['conn_mdp']))
        {
            $st = $db->prepare('SELECT * from utilisateurs WHERE pseudo=?');
            $st->execute(array($_POST['conn_nom']));
            if( ($row = $st->fetch(PDO::FETCH_ASSOC)) && ($row['password'] == md5($_POST['conn_mdp'])) )
            {
                $this->pseudo   = $_SESSION['pseudo']   = $_POST['conn_nom'];
                $this->nom      = $_SESSION['nom']      = $row['nom'];
                $this->userid   = $_SESSION['userid']   = $row['id'];
                $this->template = $_SESSION['template'] = $row['template'];
            }
        }

        // Fallback : anonyme
        if($this->pseudo == '')
        {
            $this->pseudo = 'Anonyme';
            $this->nom = 'Anonyme';
            $this->userid = 0;
            $this->template = $conf['default_template'];
        }
    }

    function deconnecte()
    {
        session_destroy();
        setcookie("remember", "", time() - 3600);
    }

}

?>
