<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = "customers";
    protected $primaryKey = "id";

    protected $useAutoIncrement = true;
    protected $returnType = "object";
    protected $useSoftDelete = true;

    protected $allowedFields = ['name', 'email', 'phone'];

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}