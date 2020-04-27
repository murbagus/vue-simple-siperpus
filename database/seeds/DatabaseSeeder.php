<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Membuat rule
        DB::table('rule')->insert([
            'id' => 1,
            'nama' => 'manipulasi data admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('rule')->insert([
            'id' => 2,
            'nama' => 'manipulasi data anggota',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('rule')->insert([
            'id' => 3,
            'nama' => 'manipulasi data info buku',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('rule')->insert([
            'id' => 4,
            'nama' => 'transaksi peminjaman',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('rule')->insert([
            'id' => 5,
            'nama' => 'transaksi pengembalian',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Menambah data admin (master)
        factory(App\Admin::class)->create();

        // Menambah data rule admin untuk master
        DB::table('admin_rule')->insert([
            ['admin' => '1234567890123456', 'rule' => 1],
            ['admin' => '1234567890123456', 'rule' => 2],
            ['admin' => '1234567890123456', 'rule' => 3],
            ['admin' => '1234567890123456', 'rule' => 4],
            ['admin' => '1234567890123456', 'rule' => 5],
        ]);
    }
}
