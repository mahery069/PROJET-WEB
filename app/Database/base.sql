CREATE DATABASE IF NOT EXISTS projet_regime_alimentaire;
USE projet_regime_alimentaire;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	nom VARCHAR(120) NOT NULL,
	email VARCHAR(180) NOT NULL,
	mot_de_passe VARCHAR(255) NOT NULL,
	genre ENUM('homme', 'femme', 'autre') NOT NULL,
	date_naissance DATE NOT NULL,
	role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
	is_gold TINYINT(1) NOT NULL DEFAULT 0,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_users_email (email),
	KEY idx_users_role (role),
	KEY idx_users_gold (is_gold)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS user_health (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_user INT UNSIGNED NOT NULL,
	taille_cm DECIMAL(5,2) NOT NULL,
	poids_kg DECIMAL(5,2) NOT NULL,
	objectif ENUM('augmenter', 'reduire', 'imc_ideal') NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_user_health_id_user (id_user),
	KEY idx_user_health_objectif (objectif),
	CONSTRAINT fk_user_health_id_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS regimes (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	nom VARCHAR(150) NOT NULL,
	description TEXT NULL,
	pourcentage_viande DECIMAL(5,2) NOT NULL DEFAULT 0,
	pourcentage_poisson DECIMAL(5,2) NOT NULL DEFAULT 0,
	pourcentage_volaille DECIMAL(5,2) NOT NULL DEFAULT 0,
	variation_poids DECIMAL(6,2) NOT NULL,
	duree_jours INT UNSIGNED NOT NULL,
	prix DECIMAL(10,2) NOT NULL,
	actif TINYINT(1) NOT NULL DEFAULT 1,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_regimes_nom (nom),
	KEY idx_regimes_actif (actif),
	KEY idx_regimes_duree (duree_jours),
	KEY idx_regimes_prix (prix)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS activites (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	nom VARCHAR(150) NOT NULL,
	description TEXT NULL,
	intensite ENUM('faible', 'moyenne', 'elevee') NOT NULL,
	duree_minutes INT UNSIGNED NOT NULL DEFAULT 30,
	actif TINYINT(1) NOT NULL DEFAULT 1,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_activites_nom (nom),
	KEY idx_activites_intensite (intensite),
	KEY idx_activites_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS codes_wallet (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	code VARCHAR(60) NOT NULL,
	montant DECIMAL(10,2) NOT NULL,
	est_utilise TINYINT(1) NOT NULL DEFAULT 0,
	id_utilisateur INT UNSIGNED NULL,
	date_utilisation DATETIME NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_codes_wallet_code (code),
	KEY idx_codes_wallet_est_utilise (est_utilise),
	KEY idx_codes_wallet_id_utilisateur (id_utilisateur),
	CONSTRAINT fk_codes_wallet_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS user_wallet (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_user INT UNSIGNED NOT NULL,
	solde DECIMAL(10,2) NOT NULL DEFAULT 0,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_user_wallet_id_user (id_user),
	KEY idx_user_wallet_solde (solde),
	CONSTRAINT fk_user_wallet_id_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS user_regimes (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_user INT UNSIGNED NOT NULL,
	id_regime INT UNSIGNED NOT NULL,
	date_debut DATE NOT NULL,
	date_fin DATE NOT NULL,
	prix_paye DECIMAL(10,2) NOT NULL,
	est_actif TINYINT(1) NOT NULL DEFAULT 1,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_user_regimes_id_user_id_regime (id_user, id_regime),
	KEY idx_user_regimes_id_user (id_user),
	KEY idx_user_regimes_id_regime (id_regime),
	KEY idx_user_regimes_est_actif (est_actif),
	CONSTRAINT fk_user_regimes_id_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_user_regimes_id_regime FOREIGN KEY (id_regime) REFERENCES regimes(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS parametres (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	cle VARCHAR(100) NOT NULL,
	valeur VARCHAR(255) NOT NULL,
	description TEXT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_parametres_cle (cle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
