<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    // ... (kode di atasnya) ...
    protected $table = 'transaction'; // nama tabelmu
    
    // TAMBAHKAN 4 KOLOM BARU DI DALAM ARRAY INI
    protected $allowedFields = [
        'username', 
        'total_harga', 
        'alamat', 
        'ongkir', 
        'status', 
        'created_at', 
        'updated_at', 
        'diskon', 
        // --- Tambahkan kode di bawah ini ---
        'biaya_jasa', 
        'voucher_code', 
        'diskon_voucher', 
        'free_mouse'
    ];
    // ... (kode di bawahnya) ...
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true; //disesuaikan
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true; //disesuaikan
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
