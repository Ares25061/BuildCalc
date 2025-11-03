<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'materials';

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
        'name',
        'category_id',
        'description',
        'unit',
        'article',
        'image_url',
        'length_mm',
        'width_mm',
        'height_mm',
        'weight_kg',
        'color',
        'brand',
        'volume_m3',
        'created_at',
        'updated_at',
        'supplier_id',
        'external_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'length_mm' => 'decimal:2',
        'width_mm' => 'decimal:2',
        'height_mm' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'volume_m3' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor for formatted dimensions
     */
    public function getFormattedDimensionsAttribute(): string
    {
        if ($this->length_mm && $this->width_mm && $this->height_mm) {
            return "{$this->length_mm}×{$this->width_mm}×{$this->height_mm}";
        }

        if ($this->length_mm && $this->width_mm) {
            return "{$this->length_mm}×{$this->width_mm}";
        }

        return '—';
    }

    /**
     * Accessor for formatted weight
     */
    public function getFormattedWeightAttribute(): string
    {
        return $this->weight_kg ? "{$this->weight_kg} кг" : '—';
    }

    /**
     * Relationship with category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'category_id');
    }

    /**
     * Relationship with prices (latest price)
     */
    public function latestPrice()
    {
        return $this->hasOne(MaterialPrice::class, 'material_id')->latestOfMany();
    }

    /**
     * Relationship with all prices
     */
    public function prices()
    {
        return $this->hasMany(MaterialPrice::class, 'material_id');
    }

    /**
     * Relationship with supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
