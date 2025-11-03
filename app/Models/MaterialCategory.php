<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialCategory extends Model
{
    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'material_categories';

    /**
     * Первичный ключ таблицы БД.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Соединение с БД, которое должна использовать модель.
     *
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id','name', 'parent_id','image_url','slug','created_at'];

    /**
     * Get the materials for the category
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'category_id');
    }

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(MaterialCategory::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children()
    {
        return $this->hasMany(MaterialCategory::class, 'parent_id');
    }

    /**
     * Get default image if category image is missing
     */
    public function getImageUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Дефолтные картинки для разных категорий
        $defaultImages = [
            'краск' => 'https://avatars.mds.yandex.net/i?id=9b8bb59788106aac9a5dc186c77a33e6bb57f285-3071255-images-thumbs&n=13',
            'кирпич' => 'https://i.freza.co/diygoods/52792/kirpich_oblitsovochniy_odinarniy_m150_1_pic.jpg',
            'обои' => 'https://avatars.mds.yandex.net/i?id=f8d14db81b1683f72dcde990a1a8e475_l-5285824-images-thumbs&n=13',
            'шпатлев' => 'https://www.lavon-shop.ru/wa-data/public/shop/categories/48/61.jpg',
            'ламинат' => 'https://avatars.mds.yandex.net/i?id=329bf69b8ccb9032b354ab362bd361f7_l-5603489-images-thumbs&n=13',
            'плитк' => 'https://avatars.mds.yandex.net/i?id=693837bffcd64b1ab70940b977a95af9e824a1bf-10089679-images-thumbs&n=13',
            'цемент' => 'https://cdnstatic.rg.ru/uploads/attachments/article/183/65/75/0002.jpg',
        ];

        foreach ($defaultImages as $keyword => $imageUrl) {
            if (stripos($this->name, $keyword) !== false) {
                return $imageUrl;
            }
        }

        // Дефолтная картинка если ничего не подошло
        return 'https://via.placeholder.com/300x200?text=' . urlencode($this->name);
    }
}
