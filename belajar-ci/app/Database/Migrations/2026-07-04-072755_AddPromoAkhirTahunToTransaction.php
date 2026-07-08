<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPromoAkhirTahunToTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'biaya_jasa' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'voucher_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'diskon_voucher' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'free_mouse' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['biaya_jasa', 'voucher_code', 'diskon_voucher', 'free_mouse']);
    }
}
