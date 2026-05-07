-- Seeds de demo pour devoloppement
USE projet_regime_alimentaire;

-- 5 utilisateurs (mot_de_passe : 'password' -> remplace par hash en prod)
INSERT INTO users (nom, email, mot_de_passe, genre, date_naissance, role, is_gold)
VALUES
('Alice Durant','alice@example.test','$2y$10$usesomesillystringforsamplehash..', 'femme', '1990-04-12', 'user', 0),
('Boris Ram','boris@example.test','$2y$10$usesomesillystringforsamplehash..', 'homme', '1985-09-03', 'user', 0),
('Carla N', 'carla@example.test','$2y$10$usesomesillystringforsamplehash..', 'femme', '1992-11-21', 'user', 0),
('David P', 'david@example.test','$2y$10$usesomesillystringforsamplehash..', 'homme', '1988-01-30', 'user', 0),
('Emma Z', 'emma@example.test','$2y$10$usesomesillystringforsamplehash..', 'femme', '1995-06-15', 'user', 0);

-- 5 regimes
INSERT INTO regimes (nom, description, pourcentage_viande, pourcentage_poisson, pourcentage_volaille, variation_poids, duree_jours, prix)
VALUES
('Regime Equilibre', 'Repas equilibres pour maintenir le poids', 30.00, 20.00, 10.00, 0.00, 30, 19.99),
('Regime Perte 1kg', 'Plan pour perdre environ 1kg par semaine', 20.00, 30.00, 10.00, -4.00, 28, 29.99),
('Regime Proteine', 'Plus de proteines, moins de glucides', 40.00, 10.00, 15.00, -3.00, 21, 24.99),
('Regime Vegetarien', 'Faible en viande, riche en legumes', 0.00, 20.00, 0.00, -2.00, 21, 17.50),
('Regime Gain Poids', 'Plan pour prendre du poids sainement', 35.00, 15.00, 20.00, 3.00, 45, 34.99);

-- 5 activites
INSERT INTO activites (nom, description, intensite, duree_minutes)
VALUES
('Marche Rapide','Marche a rythme eleve', 'moyenne', 30),
('Natation','Natation en piscine', 'elevee', 45),
('Yoga','Session de yoga douce', 'faible', 60),
('Course a pied','Jogging leger', 'elevee', 30),
('Cyclisme','Balade a velo', 'moyenne', 40);

-- 15 codes wallet (valeurs diverses)
INSERT INTO codes_wallet (code, montant, est_utilise)
VALUES
('CODE-TEST-001', 5.00, 0),
('CODE-TEST-002', 10.00, 0),
('CODE-TEST-003', 10.00, 0),
('CODE-TEST-004', 15.00, 0),
('CODE-TEST-005', 20.00, 0),
('CODE-TEST-006', 25.00, 0),
('CODE-TEST-007', 5.00, 0),
('CODE-TEST-008', 10.00, 0),
('CODE-TEST-009', 15.00, 0),
('CODE-TEST-010', 20.00, 0),
('CODE-TEST-011', 50.00, 0),
('CODE-TEST-012', 100.00, 0),
('CODE-TEST-013', 5.00, 0),
('CODE-TEST-014', 10.00, 0),
('CODE-TEST-015', 20.00, 0);

-- creer wallets pour les 5 utilisateurs
INSERT INTO user_wallet (id_user, solde)
SELECT id, 0.00 FROM users LIMIT 5;

-- creer user_health demo pour ces 5 users
INSERT INTO user_health (id_user, taille_cm, poids_kg, objectif)
SELECT id, 170.0 + FLOOR(RAND()*20), 60.0 + FLOOR(RAND()*20), 'imc_ideal' FROM users LIMIT 5;
