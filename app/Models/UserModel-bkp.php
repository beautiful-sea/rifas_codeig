<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";

    protected $useAutoIncrement = true;
    protected $returnType = "object";
    protected $useSoftDelete = true;

    protected $allowedFields = ['name', 'email', 'password','is_admin','mp_access_token','paggue_client_secret','paggue_client_key','expires_time'];

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}