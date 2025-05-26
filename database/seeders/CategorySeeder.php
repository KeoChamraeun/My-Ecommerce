<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            [
                'id'    => 1,
                'name'  => 'Men',
                'name_translate' => 'បុរស',
                'slug'  => 'men',
                'image' => 'categorie.png',
            ],
            [
                'id'    => 2,
                'name'  => 'Women',
                'name_translate' => 'នារី',
                'slug'  => 'Women',
                'image' => 'categorie.png',
            ],
            [
                'id'    => 3,
                'name'  => 'Electronics',
                'name_translate' => 'អេឡិចត្រូនិច',
                'slug'  => 'Electronics',
                'image' => 'categorie.png',
            ],
            [
                'id'    => 4,
                'name'  => 'Sport',
                'name_translate' => 'កីឡា',
                'slug'  => 'sport',
                'image' => 'categorie.png',
            ],
        ]);
    }
}
