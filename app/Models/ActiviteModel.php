<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteModel extends Model
{
    protected $table = 'activites';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom','description','intensite','duree_minutes','actif','created_at','updated_at'];
    protected $useTimestamps = true;
}
