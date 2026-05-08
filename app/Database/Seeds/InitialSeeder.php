<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Users (1 admin + 4 users)
        $usersData = [
            [
                'nom' => 'Admin System',
                'email' => 'admin@nutriplan.com',
                'mot_de_passe' => password_hash('admin123', PASSWORD_BCRYPT),
                'genre' => 'autre',
                'date_naissance' => '1990-01-01',
                'role' => 'admin',
                'is_gold' => 0,
            ],
            [
                'nom' => 'Alice Martin',
                'email' => 'alice@example.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT),
                'genre' => 'femme',
                'date_naissance' => '2000-05-15',
                'role' => 'user',
                'is_gold' => 0,
            ],
            [
                'nom' => 'Bob Dupont',
                'email' => 'bob@example.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT),
                'genre' => 'homme',
                'date_naissance' => '1998-08-20',
                'role' => 'user',
                'is_gold' => 1,
            ],
            [
                'nom' => 'Chloé Bernard',
                'email' => 'chloe@example.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT),
                'genre' => 'femme',
                'date_naissance' => '2001-03-10',
                'role' => 'user',
                'is_gold' => 0,
            ],
            [
                'nom' => 'David Moreau',
                'email' => 'david@example.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT),
                'genre' => 'homme',
                'date_naissance' => '1995-12-05',
                'role' => 'user',
                'is_gold' => 0,
            ],
        ];

        $this->db->table('users')->insertBatch($usersData);

        // User health data for each user
        $healthData = [
            [
                'id_user' => 2,
                'taille_cm' => 165,
                'poids_kg' => 62,
                'objectif' => 'reduire',
            ],
            [
                'id_user' => 3,
                'taille_cm' => 180,
                'poids_kg' => 85,
                'objectif' => 'imc_ideal',
            ],
            [
                'id_user' => 4,
                'taille_cm' => 168,
                'poids_kg' => 58,
                'objectif' => 'augmenter',
            ],
            [
                'id_user' => 5,
                'taille_cm' => 175,
                'poids_kg' => 75,
                'objectif' => 'imc_ideal',
            ],
            [
                'id_user' => 1,
                'taille_cm' => 170,
                'poids_kg' => 70,
                'objectif' => 'imc_ideal',
            ],
        ];

        $this->db->table('user_health')->insertBatch($healthData);

        // Regimes (5 variations)
        $regimesData = [
            [
                'nom' => 'Régime Protéiné Intensif',
                'description' => 'Riche en protéines pour la prise de muscle et l\'augmentation de poids',
                'pourcentage_viande' => 40,
                'pourcentage_poisson' => 35,
                'pourcentage_volaille' => 25,
                'variation_poids' => 2.5,
                'duree_jours' => 30,
                'prix' => 49.99,
                'actif' => 1,
            ],
            [
                'nom' => 'Régime Équilibré Santé',
                'description' => 'Une alimentation saine et équilibrée pour atteindre l\'IMC idéal',
                'pourcentage_viande' => 30,
                'pourcentage_poisson' => 30,
                'pourcentage_volaille' => 40,
                'variation_poids' => -0.1,
                'duree_jours' => 60,
                'prix' => 89.99,
                'actif' => 1,
            ],
            [
                'nom' => 'Régime Minceur Express',
                'description' => 'Faible en calories, idéal pour perdre du poids rapidement',
                'pourcentage_viande' => 20,
                'pourcentage_poisson' => 50,
                'pourcentage_volaille' => 30,
                'variation_poids' => -3.0,
                'duree_jours' => 30,
                'prix' => 59.99,
                'actif' => 1,
            ],
            [
                'nom' => 'Régime Méditerranéen',
                'description' => 'Inspiration du régime méditerranéen avec huile d\'olive et poisson',
                'pourcentage_viande' => 15,
                'pourcentage_poisson' => 60,
                'pourcentage_volaille' => 25,
                'variation_poids' => -1.5,
                'duree_jours' => 45,
                'prix' => 79.99,
                'actif' => 1,
            ],
            [
                'nom' => 'Régime Flexitarien',
                'description' => 'Flexibilité avec option végétarienne et protéines animales',
                'pourcentage_viande' => 25,
                'pourcentage_poisson' => 25,
                'pourcentage_volaille' => 50,
                'variation_poids' => 0,
                'duree_jours' => 60,
                'prix' => 69.99,
                'actif' => 1,
            ],
        ];

        $this->db->table('regimes')->insertBatch($regimesData);

        // Activites (5 variations)
        $activitesData = [
            [
                'nom' => 'Marche rapide',
                'description' => 'Marche à rythme soutenu pour l\'endurance',
                'intensite' => 'faible',
                'duree_minutes' => 45,
                'actif' => 1,
            ],
            [
                'nom' => 'Course à pied',
                'description' => 'Jogging modéré pour améliorer la condition physique',
                'intensite' => 'moyenne',
                'duree_minutes' => 40,
                'actif' => 1,
            ],
            [
                'nom' => 'Yoga',
                'description' => 'Séance de yoga pour la flexibilité et la relaxation',
                'intensite' => 'faible',
                'duree_minutes' => 60,
                'actif' => 1,
            ],
            [
                'nom' => 'Musculation',
                'description' => 'Entrainement de force avec poids pour la prise de muscle',
                'intensite' => 'elevee',
                'duree_minutes' => 50,
                'actif' => 1,
            ],
            [
                'nom' => 'Natation',
                'description' => 'Natation pour développer l\'endurance cardio',
                'intensite' => 'moyenne',
                'duree_minutes' => 45,
                'actif' => 1,
            ],
        ];

        $this->db->table('activites')->insertBatch($activitesData);

        // Codes wallet (15 codes)
        $codesData = [];
        $codes = ['CODE001', 'CODE002', 'CODE003', 'CODE004', 'CODE005', 'CODE006', 'CODE007', 'CODE008', 'CODE009', 'CODE010', 'CODE011', 'CODE012', 'CODE013', 'CODE014', 'CODE015'];
        $montants = [10, 15, 20, 25, 30, 10, 15, 20, 25, 30, 10, 15, 20, 25, 30];
        
        for ($i = 0; $i < 15; $i++) {
            $codesData[] = [
                'code' => $codes[$i],
                'montant' => $montants[$i],
                'est_utilise' => $i < 3 ? 1 : 0,
                'id_utilisateur' => $i < 3 ? ($i + 2) : null,
                'date_utilisation' => $i < 3 ? date('Y-m-d H:i:s', strtotime('-' . (5 - $i) . ' days')) : null,
            ];
        }

        $this->db->table('codes_wallet')->insertBatch($codesData);

        // User wallet data
        $walletData = [
            ['id_user' => 2, 'solde' => 0],
            ['id_user' => 3, 'solde' => 25.50],
            ['id_user' => 4, 'solde' => 15.00],
            ['id_user' => 5, 'solde' => 30.00],
            ['id_user' => 1, 'solde' => 0],
        ];

        $this->db->table('user_wallet')->insertBatch($walletData);
    }
}
