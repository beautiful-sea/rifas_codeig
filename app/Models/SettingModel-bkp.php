<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = "settings";
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = "object";
    protected $useSoftDelete = false;
    protected $allowedFields = ['title','expires_time','my_orders_scripts', 'thanks_scripts'];  
    protected $useTimestamps = false;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}