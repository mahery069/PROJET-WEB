<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialTables extends Migration
{
    public function up()
    {
        // Users table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 120],
            'email' => ['type' => 'VARCHAR', 'constraint' => 180],
            'mot_de_passe' => ['type' => 'VARCHAR', 'constraint' => 255],
            'genre' => ['type' => 'ENUM', 'constraint' => ['homme', 'femme', 'autre']],
            'date_naissance' => ['type' => 'DATE'],
            'role' => ['type' => 'ENUM', 'constraint' => ['user', 'admin'], 'default' => 'user'],
            'is_gold' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('role');
        $this->forge->addKey('is_gold');
        $this->forge->createTable('users');

        // User health table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'unsigned' => true],
            'taille_cm' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'poids_kg' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'objectif' => ['type' => 'ENUM', 'constraint' => ['augmenter', 'reduire', 'imc_ideal']],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('id_user');
        $this->forge->addKey('objectif');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_health');

        // Regimes table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'pourcentage_viande' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'pourcentage_poisson' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'pourcentage_volaille' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'variation_poids' => ['type' => 'DECIMAL', 'constraint' => '6,2'],
            'duree_jours' => ['type' => 'INT', 'unsigned' => true],
            'prix' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'actif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nom');
        $this->forge->addKey('actif');
        $this->forge->addKey('duree_jours');
        $this->forge->addKey('prix');
        $this->forge->createTable('regimes');

        // Activites table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'intensite' => ['type' => 'ENUM', 'constraint' => ['faible', 'moyenne', 'elevee']],
            'duree_minutes' => ['type' => 'INT', 'unsigned' => true, 'default' => 30],
            'actif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nom');
        $this->forge->addKey('intensite');
        $this->forge->addKey('actif');
        $this->forge->createTable('activites');

        // Codes wallet table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 60],
            'montant' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'est_utilise' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'id_utilisateur' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'date_utilisation' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('code');
        $this->forge->addKey('est_utilise');
        $this->forge->addKey('id_utilisateur');
        $this->forge->addForeignKey('id_utilisateur', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('codes_wallet');

        // User wallet table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'unsigned' => true],
            'solde' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('id_user');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_wallet');

        // User logs table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'unsigned' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 50],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('id_user');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_logs');
    }

    public function down()
    {
        $this->forge->dropTable('user_logs');
        $this->forge->dropTable('user_wallet');
        $this->forge->dropTable('codes_wallet');
        $this->forge->dropTable('activites');
        $this->forge->dropTable('regimes');
        $this->forge->dropTable('user_health');
        $this->forge->dropTable('users');
    }
}
