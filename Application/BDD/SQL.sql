-- connexion au serveur MySQL (on utilise mariaDB)
-- mysql -h localhost:3306 -u root -p root (avec bon login et password evidement)

-- Sécuration d'une base de donnée mariaDB (répondre correctement à toutes les questions évidement)
-- ref : https://mariadb.com/kb/en/mysql_secure_installation/
-- mysql_secure_installation

-- Afficher la liste des bases de données
-- SHOW databases;

-- Création de la  base de données si elle n'existe pas déjà
-- CREATE DATABASE IF NOT EXISTS  prime-fitness;

-- Sélectionner la bbd souhaité pour la création de table
-- USE prime-fitness;


-- Création de la table "roles"
CREATE TABLE IF NOT EXISTS roles (
  role_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  role_name varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table "services"
CREATE TABLE IF NOT EXISTS services (
  service_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  service_name varchar(40) NOT NULL,
  service_description varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table "users"
-- avec la FK sur le role_id de la table roles
CREATE TABLE IF NOT EXISTS users (
  user_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_firstname varchar(40) NOT NULL,
  user_lastname varchar(40) NOT NULL,
  user_mail varchar(90) UNIQUE NOT NULL,
  user_password varchar(90) NOT NULL,
  user_phone int(10) NOT NULL,
  user_address varchar(80) NOT NULL,
  user_active tinyint(1) NOT NULL DEFAULT 0,
  user_created_date datetime NOT NULL DEFAULT current_timestamp(),
  role_id int(11) NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles (role_id) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users_confirm (
    user_id int(11) PRIMARY KEY NOT NULL,
    user_key varchar(32) not null,
    FOREIGN KEY (user_id) REFERENCES users (user_id) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `partner`
-- avec la FK sur le users
CREATE TABLE IF NOT EXISTS partners (
    partner_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    partner_name varchar(90) NOT NULL,
    user_id int(11) UNIQUE NOT NULL,
    partner_active tinyint(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users (user_id) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `partner`
-- avec la FK sur la table partners et la table services
CREATE TABLE IF NOT EXISTS partners_services (
    partner_service_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    partner_id int(11) NOT NULL,
    service_id int(11) NOT NULL,
    partner_service_active tinyint(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (partner_id) REFERENCES partners (partner_id) on delete cascade,
    FOREIGN KEY (service_id) REFERENCES services (service_id) on delete cascade,
    UNIQUE( partner_id, service_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `partner`
-- avec la FK sur le users et services
CREATE TABLE IF NOT EXISTS structures (
  structure_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  structure_name varchar(90) NOT NULL,
  user_id int(11) UNIQUE NOT NULL,
  partner_id int(11) NOT NULL,
  structure_active tinyint(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users (user_id) on delete cascade,
  FOREIGN KEY (partner_id) REFERENCES partners (partner_id) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `structures_services`
-- avec la FK sur le structures et partners_services
CREATE TABLE IF NOT EXISTS structures_services (
  structure_service_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  partner_service_id int(11) NOT NULL,
  structure_id int(11) NOT NULL,
  structure_service_active tinyint(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (partner_service_id) REFERENCES partners_services (partner_service_id) on delete cascade,
  FOREIGN KEY (structure_id) REFERENCES structures (structure_id) on delete cascade,
  UNIQUE( structure_id, partner_service_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `logs`
CREATE TABLE IF NOT EXISTS logs (
  log_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  log_type enum('users','partners','partners_services','structures','structures_services') NOT NULL,
  log_type_id int(11) NOT NULL,
  log_time datetime NOT NULL DEFAULT current_timestamp(),
  log_text varchar(1000) NOT NULL,
  user_id int(11) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (user_id) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;    