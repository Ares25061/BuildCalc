<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPrice extends Model
{
    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'material_prices';

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
    protected $fillable = [
        'id',
        'material_id',
        'price',
        'price_date',
        'created_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'price_date' => 'date',
        'created_at' => 'datetime',
    ];
}
