<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom','description','pourcentage_viande','pourcentage_poisson','pourcentage_volaille','variation_poids','duree_jours','prix','created_at','updated_at'];
    protected $useTimestamps = true;
}
