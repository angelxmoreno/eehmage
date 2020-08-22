<?php
declare(strict_types=1);

namespace App\Database\Behaviors;

use App\Database\ModelBase;
use Illuminate\Support\Str;

/**
 * Trait UsesUuid
 * @package App\Database\Behaviors
 *
 * @method static void creating(\Closure|string $callback)
 */
trait UsesUuid
{
    public static function bootUsesUuid()
    {
        static::creating(function (ModelBase $model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string)Str::uuid();
            }
        });
    }

    /**
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}