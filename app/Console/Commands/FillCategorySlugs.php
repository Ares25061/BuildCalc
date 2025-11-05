<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaterialCategory;
use Illuminate\Support\Str;

class FillCategorySlugs extends Command
{
    protected $signature = 'categories:fill-slugs';
    protected $description = 'Заполнить slug для существующих категорий';

    public function handle()
    {
        $categories = MaterialCategory::whereNull('slug')->get();

        $this->info("Найдено {$categories->count()} категорий без slug");

        foreach ($categories as $category) {
            $slug = $this->generateSlug($category->name);

            // Проверяем уникальность slug
            $counter = 1;
            $originalSlug = $slug;
            while (MaterialCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $category->update(['slug' => $slug]);
            $this->info("Обновлено: {$category->name} -> {$slug}");
        }

        $this->info("Готово! Заполнено slug для {$categories->count()} категорий");
    }

    private function generateSlug(string $name): string
    {
        $translit = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            ' ' => '-', '_' => '-', '(' => '', ')' => '', '#' => '',
        ];

        $slug = mb_strtolower($name);
        $slug = strtr($slug, $translit);
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
