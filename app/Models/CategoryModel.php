<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = "categories";
    protected $primaryKey = "id";

    protected $useAutoIncrement = true;
    protected $returnType = "object";
    protected $useSoftDelete = true;

    protected $allowedFields = ['title','description','status'];

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}