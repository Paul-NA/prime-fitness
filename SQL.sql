

-- Création de la table "roles"
CREATE TABLE IF NOT EXISTS roles (
  role_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  role_name varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table "permissions"
CREATE TABLE IF NOT EXISTS permissions (
  permission_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  permission_name varchar(90) NOT NULL,
  permission_description varchar(2500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table "users"
-- avec la FK sur le role_id de la table roles
CREATE TABLE IF NOT EXISTS users (
  user_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_firstname varchar(80) NOT NULL,
  user_lastname varchar(80) NOT NULL,
  user_mail varchar(90) NOT NULL,
  user_password varchar(90) NOT NULL,
  user_active tinyint(1) NOT NULL DEFAULT 0,
  user_created_date datetime NOT NULL DEFAULT current_timestamp(),
  role_id int(11) NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `partner`
-- avec la FK sur le users
CREATE TABLE IF NOT EXISTS partners (
    partner_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    partner_name varchar(90) NOT NULL,
    partner_phone int(10) NOT NULL,
    user_id int(11) NOT NULL,
    is_active tinyint(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `partner`
-- avec la FK sur la table partners et la table permissions
CREATE TABLE IF NOT EXISTS partners_permissions (
  partner_permission_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  partner_id int(11) NOT NULL,
  permission_id int(11) NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (partner_id) REFERENCES partners (partner_id),
  FOREIGN KEY (permission_id) REFERENCES permissions (permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `partner`
-- avec la FK sur le users et permissions
CREATE TABLE IF NOT EXISTS structures (
  structure_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  structure_name varchar(90) NOT NULL,
  structure_phone int(10) NOT NULL,
  structure_address varchar(255) NOT NULL,
  structure_postal int(11) NOT NULL,
  user_id int(11) NOT NULL,
  partner_id int(11) NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users (user_id),
  FOREIGN KEY (partner_id) REFERENCES partners (partner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `structures_permissions`
-- avec la FK sur le structures et partners_permissions
CREATE TABLE IF NOT EXISTS structures_permissions (
  structure_permission_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  partner_permission_id int(11) NOT NULL,
  structure_id int(11) NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (partner_permission_id) REFERENCES partners_permissions (partner_permission_id),
  FOREIGN KEY (structure_id) REFERENCES structures (structure_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `log_system`
CREATE TABLE IF NOT EXISTS log_system (
  log_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  log_type enum('users','partners','partners_permissions','structures','structures_permissions') NOT NULL,
  log_type_id int(11) NOT NULL,
  log_time datetime NOT NULL DEFAULT current_timestamp(),
  log_text varchar(1000) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;