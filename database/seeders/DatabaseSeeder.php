<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // User untuk Determined Studio - HANYA 3 ROLE
        $users = [
            [
                'name' => 'Administrator Determined Studio',
                'email' => 'admin@determinedstudio.com',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Gudang Determined Studio',
                'email' => 'gudang@determinedstudio.com',
                'password' => Hash::make('password123'),
                'role' => 'gudang'
            ],
            [
                'name' => 'Produksi Determined Studio',
                'email' => 'produksi@determinedstudio.com',
                'password' => Hash::make('password123'),
                'role' => 'produksi'
            ]
            // TIDAK ADA user sablon/bordir terpisah
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Divisi untuk Determined Studio (hanya sebagai referensi)
        $divisions = [
            ['name' => 'Gudang Bahan'],
            ['name' => 'Gudang Barang Jadi'],
            ['name' => 'Divisi Produksi'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }

        // Sample vendor
        \App\Models\Vendor::create([
            'name' => 'Supplier Kain Determined',
            'address' => 'Jl. Textile No. 123',
            'phone' => '081234567890',
            'vendor_code' => 'VEND-001'
        ]);

        // Sample materials
        $materials = [
            ['name' => 'Kain Katun 30s', 'unit' => 'meter', 'current_stock' => 100, 'min_stock' => 20],
            ['name' => 'Kain Polyester', 'unit' => 'meter', 'current_stock' => 50, 'min_stock' => 10],
            ['name' => 'Benang Bordir', 'unit' => 'roll', 'current_stock' => 30, 'min_stock' => 5], // Untuk bordir
            ['name' => 'Tinta Sablon', 'unit' => 'kg', 'current_stock' => 20, 'min_stock' => 5],   // Untuk sablon
            ['name' => 'Resleting 50cm', 'unit' => 'pcs', 'current_stock' => 200, 'min_stock' => 50],
            ['name' => 'Kancing Baju', 'unit' => 'pack', 'current_stock' => 15, 'min_stock' => 3]
        ];

        foreach ($materials as $material) {
            \App\Models\Material::create($material);
        }

        // Sample products
        $products = [
            ['name' => 'Kaos Polos DS Premium', 'sku' => 'DS-KP001', 'current_stock' => 0, 'unit' => 'pcs'],
            ['name' => 'Hoodie Determined Series', 'sku' => 'DS-HD001', 'current_stock' => 0, 'unit' => 'pcs'],
            ['name' => 'Kemeja Casual DS', 'sku' => 'DS-KC001', 'current_stock' => 0, 'unit' => 'pcs'],
            ['name' => 'Kaos Sablon DS', 'sku' => 'DS-KS001', 'current_stock' => 0, 'unit' => 'pcs'],
            ['name' => 'Jaket Bordir DS', 'sku' => 'DS-JB001', 'current_stock' => 0, 'unit' => 'pcs']
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}