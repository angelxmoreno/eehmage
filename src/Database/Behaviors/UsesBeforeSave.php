<?php
declare(strict_types=1);

namespace App\Database\Behaviors;

use App\Database\ModelBase;

/**
 * Trait UsesBeforeSave
 * @package App\Database\Behaviors
 */
trait UsesBeforeSave
{
    public static function bootUsesBeforeSave()
    {
        static::creating(function (ModelBase $model) {
            if (method_exists($model, 'beforeSave')) {
                $model->beforeSave();
            }
        });

        static::saved(function (ModelBase $model) {
            if (method_exists($model, 'afterSave')) {
                $model->afterSave();
            }
        });
    }
}