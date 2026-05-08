<?php

namespace App\Models;

use CodeIgniter\Model;

class CodesWalletModel extends Model
{
    protected $table = 'codes_wallet';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code','montant','est_utilise','id_utilisateur','date_utilisation','created_at','updated_at'];
    protected $useTimestamps = true;
}
