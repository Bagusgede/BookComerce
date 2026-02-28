<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class Categoryseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiksi',
                'slug' => 'fiksi',
                'description' => 'Novel, cerpen, dan karya fiksi lainnya',
                'is_active' => true,
            ],
            [
                'name' => 'Non-Fiksi',
                'slug' => 'non-fiksi',
                'description' => 'Biografi, sejarah, dan karya non-fiksi lainnya',
                'is_active' => true,
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Buku tentang programming, teknologi, dan komputer',
                'is_active' => true,
            ],
            [
                'name' => 'Bisnis & Ekonomi',
                'slug' => 'bisnis-ekonomi',
                'description' => 'Buku tentang bisnis, keuangan, dan ekonomi',
                'is_active' => true,
            ],
            [
                'name' => 'Pengembangan Diri',
                'slug' => 'pengembangan-diri',
                'description' => 'Buku motivasi dan pengembangan diri',
                'is_active' => true,
            ],
            [
                'name' => 'Anak-Anak',
                'slug' => 'anak-anak',
                'description' => 'Buku untuk anak-anak dan remaja',
                'is_active' => true,
            ],
            [
                'name' => 'Agama & Spiritualitas',
                'slug' => 'agama-spiritualitas',
                'description' => 'Buku tentang agama dan spiritualitas',
                'is_active' => true,
            ],
            [
                'name' => 'Sains & Pendidikan',
                'slug' => 'sains-pendidikan',
                'description' => 'Buku tentang sains, pendidikan, dan akademis',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✓ Category seeder completed: 8 categories created');
    }
}
