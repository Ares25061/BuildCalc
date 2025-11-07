<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicDataSeeder extends Seeder
{
    public function run()
    {
        $workTypes = [
            ['id' => 1, 'name' => 'Материалы', 'unit' => 'шт', 'description' => 'Основные материалы проекта'],
            ['id' => 2, 'name' => 'Электрика', 'unit' => 'точка', 'description' => 'Электромонтажные работы'],
            ['id' => 3, 'name' => 'Сантехника', 'unit' => 'прибор', 'description' => 'Сантехнические работы'],
            ['id' => 4, 'name' => 'Отделка', 'unit' => 'м²', 'description' => 'Отделочные работы'],
            ['id' => 5, 'name' => 'Укладка плитки', 'unit' => 'м²', 'description' => 'Укладка керамической плитки'],
            ['id' => 6, 'name' => 'Монтаж сайдинга', 'unit' => 'м²', 'description' => 'Монтаж винилового сайдинга'],
            ['id' => 7, 'name' => 'Устройство кровли', 'unit' => 'м²', 'description' => 'Монтаж кровельных материалов'],
            ['id' => 8, 'name' => 'Штукатурные работы', 'unit' => 'м²', 'description' => 'Нанесение штукатурки'],
            ['id' => 9, 'name' => 'Покраска', 'unit' => 'м²', 'description' => 'Окрашивание поверхностей'],
            ['id' => 10, 'name' => 'Монтажные работы', 'unit' => 'шт', 'description' => 'Общие монтажные работы'],
        ];

        foreach ($workTypes as $workType) {
            DB::table('work_types')->updateOrInsert(
                ['id' => $workType['id']],
                [
                    'name' => $workType['name'],
                    'unit' => $workType['unit'],
                    'description' => $workType['description'],
                    'created_at' => now(),
                ]
            );
        }
        DB::table('suppliers')->updateOrInsert(
            ['name' => 'OBI'],
            [
                'base_url' => 'https://obi.ru',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Виды работ и поставщик успешно созданы');
    }
}
