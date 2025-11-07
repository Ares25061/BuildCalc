<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectedProjectMaterial extends Model
{
    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'selected_project_materials';

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
    public $timestamps = true;

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
        'project_item_id',
        'material_id',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the project item that owns the selected project material.
     */
    public function projectItem(): BelongsTo
    {
        return $this->belongsTo(ProjectItem::class, 'project_item_id');
    }

    /**
     * Get the material that owns the selected project material.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /**
     * Get the project through project item.
     */
    public function project()
    {
        return $this->hasOneThrough(
            Project::class,
            ProjectItem::class,
            'id',
            'id',
            'project_item_id',
            'project_id'
        );
    }
}
