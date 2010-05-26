CREATE TABLE IF NOT EXISTS utilisateurs (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  pseudo varchar(255) NOT NULL,
  password varchar(50) NOT NULL,
  template varchar(255) NOT NULL,
  nom varchar(255) NOT NULL,
  promotion int(10) unsigned NOT NULL,
  flags int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nom (nom),
  UNIQUE KEY pseudo (pseudo)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS projets (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  nom varchar(255) NOT NULL,
  description text NOT NULL,
  open_demandes tinyint(2) unsigned NOT NULL,
  open_commentaires tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nom (nom)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS versions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  position int(10) unsigned NOT NULL,
  projet int(10) unsigned NOT NULL,
  nom varchar(255) NOT NULL,
  description text NOT NULL,
  FOREIGN KEY (projet) REFERENCES projets (id) ON DELETE CASCADE,
  PRIMARY KEY (id),
  UNIQUE KEY nom (nom)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS demandes (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  projet int(10) unsigned NOT NULL,
  version int(10) unsigned,
  titre text NOT NULL,
  auteur int(10) unsigned,
  description text NOT NULL,
  priorite int(10) NOT NULL,
  statut int(10) unsigned NOT NULL,
  creation datetime NOT NULL,
  derniere_activite datetime NOT NULL,
  FOREIGN KEY (projet) REFERENCES projets (id) ON DELETE CASCADE,
  FOREIGN KEY (version) REFERENCES versions (id) ON DELETE SET NULL,
  FOREIGN KEY (auteur) REFERENCES utilisateurs (id) ON DELETE RESTRICT,
  PRIMARY KEY (id)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS association_utilisateurs_projets (
  utilisateur int(10) unsigned NOT NULL,
  projet int(10) unsigned NOT NULL,
  flags int(10) unsigned NOT NULL,
  derniere_activite datetime,
  FOREIGN KEY (utilisateur) REFERENCES utilisateurs(id) ON DELETE RESTRICT,
  FOREIGN KEY (projet) REFERENCES projets(id) ON DELETE CASCADE,
  PRIMARY KEY(utilisateur, projet)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS commentaires (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  auteur int(10) unsigned,
  demande int(10) unsigned NOT NULL,
  texte text NOT NULL,
  creation datetime NOT NULL,
  resume tinyint(2) unsigned NOT NULL,
  FOREIGN KEY (demande) REFERENCES demandes (id) ON DELETE CASCADE,
  FOREIGN KEY (auteur) REFERENCES utilisateurs (id) ON DELETE RESTRICT,
  PRIMARY KEY (id)
) TYPE=INNODB;

/* Utilisateur par défaut : "admin" "admin" */
INSERT INTO utilisateurs(pseudo, password, template, nom, promotion, flags) VALUES("admin", "d033e22ae348aeb5660fc2140aec35850c4da997", "default", "L'Admin", 0, 31);
