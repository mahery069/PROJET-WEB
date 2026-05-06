-- ============================================================
-- TABLE USERS
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    motdepasse VARCHAR(255) NOT NULL,
    genre ENUM('M', 'F', 'Autre') NOT NULL,
    date_naissance DATE NOT NULL,
    is_gold BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE USER_HEALTH
-- ============================================================
CREATE TABLE IF NOT EXISTS user_health (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    taille DECIMAL(5, 2) NOT NULL COMMENT 'Taille en cm',
    poids DECIMAL(5, 2) NOT NULL COMMENT 'Poids en kg',
    objectif ENUM('augmenter', 'reduire', 'imc_ideal') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE REGIMES
-- ============================================================
CREATE TABLE IF NOT EXISTS regimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    pourcentage_viande DECIMAL(5, 2) NOT NULL COMMENT 'En %',
    pourcentage_poisson DECIMAL(5, 2) NOT NULL COMMENT 'En %',
    pourcentage_volaille DECIMAL(5, 2) NOT NULL COMMENT 'En %',
    variation_poids DECIMAL(5, 2) NOT NULL COMMENT 'Variation en kg',
    duree INT NOT NULL COMMENT 'Durée en jours',
    prix DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE ACTIVITES
-- ============================================================
CREATE TABLE IF NOT EXISTS activites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    intensite ENUM('faible', 'moderee', 'elevee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE CODES_WALLET
-- ============================================================
CREATE TABLE IF NOT EXISTS codes_wallet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    est_utilise BOOLEAN DEFAULT FALSE,
    user_id INT,
    date_utilisation DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- TABLE USER_WALLET
-- ============================================================
CREATE TABLE IF NOT EXISTS user_wallet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    solde DECIMAL(10, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE USER_REGIMES (Subscriptions)
-- ============================================================
CREATE TABLE IF NOT EXISTS user_regimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_active_regime (user_id, regime_id, est_actif)
);

-- ============================================================
-- TABLE USER_ACTIVITES (Recommended activities for users)
-- ============================================================
CREATE TABLE IF NOT EXISTS user_activites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    activite_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_activite (user_id, activite_id)
);

-- ============================================================
-- TABLE PARAMETRES (General settings)
-- ============================================================
CREATE TABLE IF NOT EXISTS parametres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur VARCHAR(255) NOT NULL,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
