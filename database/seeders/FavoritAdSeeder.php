<?php

namespace Database\Seeders;

use App\Models\FavoriteAd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoritAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FavoriteAd::factory(10)->create();
    }
}
