<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Gunakan DB facade untuk insert
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
Menu::create([
            'name' => 'Nasi Putih Sayur Lodeh',
            'description' => 'Nasi + sayur lodeh.',
            'price' => 10000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Nasi+Putih+Sayur+Asem/Lodeh',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Nasi Uduk',
            'description' => 'Nasi uduk + telor dadar + tempe orek + bihun + sambal + kacang.',
            'price' => 15000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Nasi+Kuning/Uduk',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Nasi Kuning',
            'description' => 'Nasi kuning telor dadar + tempe orek + bihun + sambal + kacang.',
            'price' => 30000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Nasi+Kuning+Komplit',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Ayam Goreng',
            'description' => 'Ayam goreng.',
            'price' => 25000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Ayam+Goreng/Bakar',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Nasi Kari Ayam',
            'description' => 'Nasi + kari ayam.',
            'price' => 25000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Nasi+Kari+Ayam',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Nasi Tim',
            'description' => 'Nasi tim.',
            'price' => 20000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Nasi+Tim',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Lontong Sayur Komplit',
            'description' => 'Lontong + sayur + telur.',
            'price' => 25000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Lontong+Sayur+Komplit',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Lontong Sayur Kentang Tahu',
            'description' => 'Lontong + sayur + telur + kentang + tahu.',
            'price' => 15000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Lontong+Sayur+Kentang+Tahu',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Mie Ayam Kampung',
            'description' => 'Mie + ayam kampung rebus.',
            'price' => 25000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Mie+Ayam+Kampung',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Mie Ayam Kampung Pangsit',
            'description' => 'Mie + ayam kampung rebus + pangsit.',
            'price' => 30000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Mie+Ayam+Pangsit',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Mie Ayam Kampung Bakso',
            'description' => 'Mie + ayam kampung rebus + bakso.',
            'price' => 30000.00,
            'type' => 'makanan',
            'image_url' => 'https://via.placeholder.com/100x100?text=Mie+Ayam+Bakso',
            'stock' => 50,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Es Teh Tawar',
            'description' => 'Teh tawar + es batu.',
            'price' => 3000.00, // Harga untuk dingin, harga hangat 2000 tercantum sebagai '3k/2k'
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Tawar',
            'stock' => 100,
            'is_available' => true,
        ]);

                Menu::create([
            'name' => 'Teh Tawar Hangat',
            'description' => 'Teh tawar hangat.',
            'price' => 3000.00, // Harga untuk dingin, harga hangat 2000 tercantum sebagai '3k/2k'
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Tawar',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Es Teh Manis',
            'description' => 'Teh manis + es batu.',
            'price' => 5000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Manis',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Teh Manis Hangat',
            'description' => 'Teh manis hangat.',
            'price' => 5000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Manis',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Es Teh Tarik',
            'description' => 'Teh tarik + es batu.',
            'price' => 18000.00, // Harga untuk dingin, harga hangat 15000 tercantum sebagai '18k/15k'
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Tarik',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Teh Tarik Hangat',
            'description' => 'Teh tarik hangat.',
            'price' => 18000.00, // Harga untuk dingin, harga hangat 15000 tercantum sebagai '18k/15k'
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Tarik',
            'stock' => 100,
            'is_available' => true,
        ]);


        Menu::create([
            'name' => 'Kopi Hitam',
            'description' => 'Kopi hitam.',
            'price' => 6000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Kopi+Hitam',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Kopi Susu',
            'description' => 'Kopi + susu.',
            'price' => 8000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Kopi+Susu',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Es Milo',
            'description' => 'Milo + es batu.',
            'price' => 12000.00, // Harga untuk dingin, harga hangat 10000 tercantum sebagai '12k/10k'
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Milo',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Air Mineral',
            'description' => 'Air mineral.',
            'price' => 5000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Air+Mineral',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Air Jeruk Murni',
            'description' => 'Air jeruk murni.',
            'price' => 15000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Air+Jeruk+Murni',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Teh Botol',
            'description' => 'Teh botol.',
            'price' => 5000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Botol',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Teh Pucuk',
            'description' => 'Teh Pucuk.',
            'price' => 5000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Teh+Pucuk',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Ichi Ocha',
            'description' => 'Ichi Ocha.',
            'price' => 5000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Ichi+Ocha',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'Nipis Madu',
            'description' => 'Nipis Madu.',
            'price' => 6000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=Nipis+Madu',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'UC 1000 Orange',
            'description' => 'UC 1000 rasa jeruk.',
            'price' => 10000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=UC1000+Orange/Lemon',
            'stock' => 100,
            'is_available' => true,
        ]);

        Menu::create([
            'name' => 'UC 1000 Vitamin orange.',
            'description' => 'UC 1000 vitamin rasa jeruk.',
            'price' => 12000.00,
            'type' => 'minuman',
            'image_url' => 'https://via.placeholder.com/100x100?text=UC1000+Vitamin',
            'stock' => 100,
            'is_available' => true,
        ]);

    }
}
