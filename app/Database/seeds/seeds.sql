USE projet_regime_alimentaire;


INSERT INTO users (nom, email, mot_de_passe, genre, date_naissance, role, is_gold)
VALUES
('Admin', 'admin@example.test', '$2y$10$4BeNRy1JaZt00dus8QDlI.U/GzrECePX8TJmLcQeP8ZUcCWU0qr0y', 'autre', '2000-01-01', 'admin', 0), -- plain: admin123!
('mahery01', 'mahery01@example.test', '$2y$10$FncttQHjmMqGvKPyrQ3ZeuWBTg3bPdbfqRXSGkB4dSM6uIvxg93ei', 'autre', '1990-01-01', 'user', 0), -- plain: Maha#2026
('adminweb', 'adminweb@example.test', '$2y$10$FQrhfpCQ9Np5rmNVDnHR2uFEilchU0x5pI6J2aNjPvQymprovVm36', 'autre', '1990-01-01', 'user', 0), -- plain: Web@Lib45
('devjava', 'devjava@example.test', '$2y$10$XMmcU2VfDA9HBRu4vYlsdewjUq88kLMX5Kcny.7vapxamfDK6R43i', 'autre', '1990-01-01', 'user', 0), -- plain: Java_S2!9
('usertest', 'usertest@example.test', '$2y$10$LN2bnMk2KoO4brqMRKGifuisRFeNTZmpF7m2fJZbpSm0nh52FqKKq', 'autre', '1990-01-01', 'user', 0), -- plain: Test1234@
('bibliouser', 'bibliouser@example.test', '$2y$10$QPfWzCIMv2w8K09u5Bsml.7HJwBv8ujGJTnZEghytTnyFc052BFKu', 'autre', '1990-01-01', 'user', 0); -- plain: Livre&Code7

INSERT INTO regimes (nom, description, pourcentage_viande, pourcentage_poisson, pourcentage_volaille, variation_poids, duree_jours, prix)
VALUES
('Regime Equilibre', 'Repas equilibres pour maintenir le poids', 30.00, 20.00, 10.00, 0.00, 30, 99950),
('Regime Perte 1kg', 'Plan pour perdre environ 1kg par semaine', 20.00, 30.00, 10.00, -4.00, 28, 149950),
('Regime Proteine', 'Plus de proteines, moins de glucides', 40.00, 10.00, 15.00, -3.00, 21, 124950),
('Regime Vegetarien', 'Faible en viande, riche en legumes', 0.00, 20.00, 0.00, -2.00, 21, 87500),
('Regime Gain Poids', 'Plan pour prendre du poids sainement', 35.00, 15.00, 20.00, 3.00, 45, 174950);

INSERT INTO activites (nom, description, intensite, duree_minutes)
VALUES
('Marche Rapide','Marche a rythme eleve', 'moyenne', 30),
('Natation','Natation en piscine', 'elevee', 45),
('Yoga','Session de yoga douce', 'faible', 60),
('Course a pied','Jogging leger', 'elevee', 30),
('Cyclisme','Balade a velo', 'moyenne', 40);

INSERT INTO codes_wallet (code, montant, est_utilise)
VALUES
('CODE-TEST-001', 25000, 0),
('CODE-TEST-002', 50000, 0),
('CODE-TEST-003', 50000, 0),
('CODE-TEST-004', 75000, 0),
('CODE-TEST-005', 100000, 0),
('CODE-TEST-006', 125000, 0),
('CODE-TEST-007', 25000, 0),
('CODE-TEST-008', 50000, 0),
('CODE-TEST-009', 75000, 0),
('CODE-TEST-010', 100000, 0),
('CODE-TEST-011', 250000, 0),
('CODE-TEST-012', 500000, 0),
('CODE-TEST-013', 25000, 0),
('CODE-TEST-014', 50000, 0),
('CODE-TEST-015', 100000, 0);

INSERT INTO user_wallet (id_user, solde)
SELECT u.id, 0
FROM users u
WHERE u.email IN (
	'admin@example.test',
	'mahery01@example.test',
	'adminweb@example.test',
	'devjava@example.test',
	'usertest@example.test',
	'bibliouser@example.test'
);

INSERT INTO user_health (id_user, taille_cm, poids_kg, objectif)
SELECT u.id, h.taille_cm, h.poids_kg, h.objectif
FROM users u
JOIN (
    SELECT 'admin@example.test' AS email, 175.00 AS taille_cm, 74.00 AS poids_kg, 'imc_ideal' AS objectif
    UNION ALL SELECT 'mahery01@example.test', 170.00, 70.00, 'imc_ideal'
    UNION ALL SELECT 'adminweb@example.test', 172.00, 76.00, 'reduire'
    UNION ALL SELECT 'devjava@example.test', 178.00, 72.00, 'augmenter'
    UNION ALL SELECT 'usertest@example.test', 168.00, 68.00, 'reduire'
    UNION ALL SELECT 'bibliouser@example.test', 165.00, 64.00, 'imc_ideal'
) h ON h.email = u.email;
