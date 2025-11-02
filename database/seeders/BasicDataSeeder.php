<?php
// database/seeders/BasicDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicDataSeeder extends Seeder
{
    public function run()
    {
        // Базовые виды работ
        $workTypes = [
            ['name' => 'Укладка плитки', 'unit' => 'м²', 'description' => 'Укладка керамической плитки'],
            ['name' => 'Монтаж сайдинга', 'unit' => 'м²', 'description' => 'Монтаж винилового сайдинга'],
            ['name' => 'Устройство кровли', 'unit' => 'м²', 'description' => 'Монтаж кровельных материалов'],
            ['name' => 'Штукатурные работы', 'unit' => 'м²', 'description' => 'Нанесение штукатурки'],
            ['name' => 'Покраска', 'unit' => 'м²', 'description' => 'Окрашивание поверхностей'],
            ['name' => 'Монтажные работы', 'unit' => 'шт', 'description' => 'Общие монтажные работы'],
        ];

        foreach ($workTypes as $workType) {
            DB::table('work_types')->updateOrInsert(
                ['name' => $workType['name']],
                [
                    'unit' => $workType['unit'],
                    'description' => $workType['description'],
                    'created_at' => now(),
                ]
            );
        }

        // Поставщик OBI
        DB::table('suppliers')->updateOrInsert(
            ['name' => 'OBI'],
            [
                'base_url' => 'https://obi.ru',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Basic work types and supplier created!');
    }
}
