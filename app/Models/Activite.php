<?php

namespace App\Models;

use CodeIgniter\Model;

class Activite extends Model
{
    protected $table = 'activites';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nom', 'description', 'intensite', 'duree_minutes', 'actif'];
    protected $useTimestamps = true;
}
