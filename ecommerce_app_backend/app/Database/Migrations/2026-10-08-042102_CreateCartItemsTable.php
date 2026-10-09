<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'cart_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id'    => ['type' => 'INT', 'constraint' => 11],
            'product_title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'product_price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'product_image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'quantity'      => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('cart_id', 'cart', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cart_items');
    }

    public function down()
    {
        //
    }
}
