<?php

namespace App\Models;

use CodeIgniter\Model;

class CodesWalletModel extends Model
{
    protected $table = 'codes_wallet';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code','montant','est_utilise','id_utilisateur','date_utilisation','created_at','updated_at'];
    protected $useTimestamps = true;

    public function getUnusedCodes()
    {
        return $this->where('est_utilise', 0)->findAll();
    }

    public function getUsedCodes()
    {
        return $this->where('est_utilise', 1)->findAll();
    }

    public function validateCode($code)
    {
        return $this->where('code', $code)->where('est_utilise', 0)->first();
    }
}
