<?php
declare(strict_types=1);

namespace App\Database;

use App\Database\Behaviors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Slim\Psr7\Request;

/**
 * Class ModelBase
 * @package App\Models
 *
 * @method static ModelBase create(array $attributes)
 */
abstract class ModelBase extends Model
{
    use Behaviors\UsesUuid;
    use Behaviors\UsesBeforeSave;
    use Behaviors\UsesValidator;
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    const DELETED_AT = 'deleted';

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];
    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setInferredTable();
    }

    protected function setInferredTable()
    {
        $table = $this->table ?? Str::snake(Str::pluralStudly(substr(class_basename($this), 0, -5)));
        $this->setTable($table);
    }

    /**
     * @param Request $request
     * @return static
     */
    public static function buildFromRequest(Request $request)
    {
        $attributes = $request->getParsedBody() ?? [];
        $entity = new static();
        $entity->fill($attributes);
        return $entity;
    }
}