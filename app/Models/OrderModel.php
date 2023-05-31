<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = "orders";
    protected $primaryKey = "id";

    protected $useAutoIncrement = true;
    protected $returnType = "object";
    protected $useSoftDelete = true;

    protected $allowedFields = ['hash','id_customer', 'id_raffle','id_user', 'status', 'quantity','numbers', 'price', 'original_price','expires_in','payment_qrcode','payment_url','payment_image'];

    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}