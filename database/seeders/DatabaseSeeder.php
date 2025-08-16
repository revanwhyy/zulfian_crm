<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure base users exist
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager', 'password' => 'password', 'role' => 'manager']
        );

        $sales = User::firstOrCreate(
            ['email' => 'sales@example.com'],
            ['name' => 'Sales', 'password' => 'password', 'role' => 'sales']
        );

        // Seed products (Bahasa Indonesia) - internet packages
        $productsData = [
            ['name' => 'Paket Internet Rumah 20 Mbps', 'description' => 'Internet rumahan cepat untuk keluarga kecil.', 'subtotal' => 199000.00],
            ['name' => 'Paket Internet Rumah 50 Mbps', 'description' => 'Kecepatan stabil untuk streaming dan WFH.', 'subtotal' => 299000.00],
            ['name' => 'Paket Internet Rumah 100 Mbps', 'description' => 'Cocok untuk gamer dan keluarga besar.', 'subtotal' => 399000.00],
            ['name' => 'Paket Internet Bisnis 200 Mbps', 'description' => 'Koneksi andal untuk operasional bisnis.', 'subtotal' => 799000.00],
            ['name' => 'Paket Internet Bisnis 500 Mbps', 'description' => 'Performa tinggi untuk kebutuhan enterprise.', 'subtotal' => 1499000.00],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $products[] = Product::firstOrCreate(
                ['name' => $p['name']],
                ['description' => $p['description'], 'subtotal' => $p['subtotal']]
            );
        }

        // Seed leads (Bahasa Indonesia)
        $leadsData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081234567890', 'address' => 'Jl. Merdeka No. 10, Jakarta'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@example.com', 'phone' => '081298765432', 'address' => 'Jl. Sudirman No. 25, Bandung'],
            ['name' => 'Agus Pratama', 'email' => 'agus@example.com', 'phone' => '081377788899', 'address' => 'Jl. Diponegoro No. 5, Surabaya'],
            ['name' => 'Rina Kurnia', 'email' => 'rina@example.com', 'phone' => '081355566677', 'address' => 'Jl. Gajah Mada No. 3, Yogyakarta'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081222334455', 'address' => 'Jl. Pahlawan No. 8, Semarang'],
        ];

        $leads = [];
        foreach ($leadsData as $l) {
            $leads[] = Lead::firstOrCreate(
                ['email' => $l['email']],
                ['name' => $l['name'], 'phone' => $l['phone'], 'address' => $l['address']]
            );
        }

        // Create projects linking leads to products and users with various statuses
        $statuses = Project::STATUSES;
        $users = [$manager, $sales];

        $i = 0;
        foreach ($leads as $lead) {
            $product = $products[$i % count($products)];
            $user = $users[$i % count($users)];
            $status = $statuses[$i % count($statuses)];

            Project::firstOrCreate(
                [
                    'lead_id' => $lead->id,
                    'product_id' => $product->id,
                ],
                [
                    'user_id' => $user->id,
                    'status' => $status,
                ]
            );

            $i++;
        }
    }
}
