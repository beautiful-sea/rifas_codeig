<?php

namespace App\Models;

use CodeIgniter\Model;

class RaffleModel extends Model
{
    protected $table      = 'raffles';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id_user','hash','title', 'slug', 'number_of_numbers','description','wp_group','pixels','status','type','gateway','number_of_numbers','numbers','draw_date','id_category','images','price','payment_qrcode','payment_price','payment_status','packs','percent_level','fake_percent_level','show_percent_level','discount_status','discount_type','discount_quantity','discount_price','discount_percent','winners', 'parcial', 'favoritar', 'payment_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}