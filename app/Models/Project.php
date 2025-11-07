<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'projects';

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
        'user_id',
        'name',
        'description',
        'status',
        'total_estimated_cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_estimated_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project items for the project.
     */
    public function projectItems(): HasMany
    {
        return $this->hasMany(ProjectItem::class, 'project_id');
    }

    /**
     * Get the selected materials for the project through project items.
     */
    public function selectedMaterials()
    {
        return $this->hasManyThrough(
            SelectedProjectMaterial::class,
            ProjectItem::class,
            'project_id',
            'project_item_id',
            'id',
            'id'
        );
    }
}
