<?php

class Utilisateur {

    var $pseudo;
    var $userid;
    var $template;

    function template()
    { return $this->template; }

    function __construct()
    {
        global $conf;
        global $db;

        session_start();
        if(isset($_SESSION['pseudo']))
        {
            $this->pseudo = $_SESSION['pseudo'];
            $this->userid = $_SESSION['userid'];
            $this->template = $_SESSION['template'];
        }
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
                        $this->pseudo = $pseudo;
                        $this->userid = $row['id'];
                        $this->template = $row['template'];
                    }
                }
            }
        }

        // Fallback : anonyme
        if($this->pseudo == '')
        {
            $this->pseudo = 'Anonyme';
            $this->userid = 0;
            $this->template = $conf['default_template'];
        }
    }

}

?>
