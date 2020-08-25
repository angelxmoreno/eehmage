<?php
declare(strict_types=1);

namespace App\Database\Models;

use App\Database\ModelBase;
use Illuminate\Support\Str;
use Valitron\Validator;

/**
 * Class GroupModel
 * @package App\Models
 */
class GroupModel extends ModelBase
{
    /**
     * @var string[]
     */
    protected $props = [
        'id',
        'is_active',
        'name',
        'dir',
        'created',
        'modified',
        'deleted',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the images for the group
     */
    public function images()
    {
        return $this->hasMany(ImageModel::class);
    }

    protected function getRules(Validator $validator): Validator
    {
        $validator->rule('required', array('name'))->message('{field} is required');
        $validator->rule('lengthBetween', 'name', 3, 16)->message('{field} must be 3 - 16 characters');
        $validator->rule(function ($field, $value, $params, $fields) {
            $result = GroupModel::whereName($value)->count();
            return $result === 0;
        }, 'name')->message("{field} is already in use");
        return parent::getRules($validator);
    }

    protected function beforeSave()
    {
        $this->dir = (string)Str::uuid() . '/';
    }

    protected function afterSave()
    {
        mkdir(UPLOADS_DIR . $this->dir);
    }
}
