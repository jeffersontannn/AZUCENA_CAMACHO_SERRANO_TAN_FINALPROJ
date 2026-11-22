<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class FourBeansSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        if (User::count() === 0) {
            User::insert([
                ['username' => 'amiel', 'full_name' => 'Amiel Azucena', 'password' => bcrypt('amiel123'), 'avatar' => null, 'created_at' => $now],
                ['username' => 'jefferson', 'full_name' => 'Jefferson Tan', 'password' => bcrypt('jefferson123'), 'avatar' => null, 'created_at' => $now],
                ['username' => 'andrei', 'full_name' => 'Andrei Klein Serrano', 'password' => bcrypt('andrei123'), 'avatar' => null, 'created_at' => $now],
                ['username' => 'ann', 'full_name' => 'Ann Valerie Camacho', 'password' => bcrypt('admin123'), 'avatar' => null, 'created_at' => $now],
            ]);
        }

        if (Product::count() === 0) {
            Product::insert([
                ['name' => 'Four Beans Signature Latte', 'price' => 185.00, 'stock_quantity' => 24, 'image' => null, 'created_at' => $now],
                ['name' => 'Iced Caramel Macchiato', 'price' => 195.00, 'stock_quantity' => 18, 'image' => null, 'created_at' => $now],
                ['name' => 'Cappuccino', 'price' => 180.00, 'stock_quantity' => 30, 'image' => null, 'created_at' => $now],
                ['name' => 'Matcha Latte', 'price' => 190.00, 'stock_quantity' => 12, 'image' => null, 'created_at' => $now],
                ['name' => 'Butter Croissant', 'price' => 110.00, 'stock_quantity' => 6, 'image' => null, 'created_at' => $now],
                ['name' => 'Blueberry Cheesecake', 'price' => 220.00, 'stock_quantity' => 8, 'image' => null, 'created_at' => $now],
            ]);
        }

        if (Customer::count() === 0) {
            Customer::insert([
                ['full_name' => 'Maria Santos', 'email' => 'maria.santos@email.com', 'phone' => '+63 912 345 6789', 'created_at' => $now],
                ['full_name' => 'Enzo Reyes', 'email' => 'enzo.reyes@email.com', 'phone' => '+63 917 890 1234', 'created_at' => $now],
                ['full_name' => 'Anna Cruz', 'email' => 'anna.cruz@email.com', 'phone' => '+63 923 456 7890', 'created_at' => $now],
            ]);
        }
    }
}
