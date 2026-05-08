<?php

namespace App\Models;

use CodeIgniter\Model;

class Regime extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nom', 'description', 'pourcentage_viande', 'pourcentage_poisson', 'pourcentage_volaille', 'variation_poids', 'duree_jours', 'prix', 'actif'];
    protected $useTimestamps = true;
}
