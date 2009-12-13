CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projet` int(11) NOT NULL,
  `titre` text NOT NULL,
  `auteur` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `priorite` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `projet` (`projet`)
);

CREATE TABLE IF NOT EXISTS `projets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
);

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `promotion` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`),
  KEY `pseudo` (`pseudo`)
);

CREATE TABLE IF NOT EXISTS `versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projet` int(10) unsigned NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nom` (`nom`)
);
