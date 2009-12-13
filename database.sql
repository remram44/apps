CREATE TABLE IF NOT EXISTS utilisateurs (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  pseudo varchar(255) NOT NULL,
  password varchar(40) NOT NULL,
  template varchar(255) NOT NULL,
  nom varchar(255) NOT NULL,
  promotion int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nom (nom),
  KEY pseudo (pseudo)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS demandes (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  projet int(11) NOT NULL,
  titre text NOT NULL,
  auteur int(10) unsigned NOT NULL,
  description text NOT NULL,
  priorite int(11) NOT NULL,
  statut int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY projet (projet)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS projets (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  nom varchar(255) NOT NULL,
  description text NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nom (nom)
) TYPE=INNODB;

CREATE TABLE IF NOT EXISTS versions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  projet int(10) unsigned NOT NULL,
  nom varchar(255) NOT NULL,
  description text NOT NULL,
  PRIMARY KEY (id),
  KEY nom (nom)
) TYPE=INNODB;

CREATE TABLE association_utilisateurs_projets (
  utilisateur int(10) unsigned NOT NULL,
  projet int(10) unsigned NOT NULL,
  FOREIGN KEY (utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (projet) REFERENCES projets(id) ON DELETE CASCADE,
  PRIMARY KEY(utilisateur, projet)
) TYPE=INNODB;

CREATE TABLE association_versions_demandes (
  version int(10) unsigned NOT NULL,
  demande int(10) unsigned NOT NULL,
  FOREIGN KEY (version) REFERENCES versions(id) ON DELETE CASCADE,
  FOREIGN KEY (demande) REFERENCES demandes(id) ON DELETE CASCADE,
  PRIMARY KEY(version, demande)
) TYPE=INNODB;
