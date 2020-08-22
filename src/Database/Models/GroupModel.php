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
        'user_id',
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
        'user_id',
        'name',
    ];


    /**
     * Get the images for the group
     */
    public function images()
    {
        return $this->hasMany(ImageModel::class);
    }

    /**
     * Get the user that owns the group
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }


    protected function getRules(Validator $validator): Validator
    {
        $validator->rule('required', array('name', 'user_id'))->message('{field} is required');
        $validator->rule('lengthBetween', 'name', 3, 16)->message('{field} must be 3 - 16 characters');
        $uuid_regex = '/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/';
        $validator->rule('regex', 'user_id', $uuid_regex)->message('{field} is not a valid uuid');
        $validator->rule(function ($field, $value, $params, $fields) {
            $result = GroupModel::whereName($value)->whereUserId($fields['user_id'])->count();
            return $result === 0;
        }, 'name')->message("{field} is already in use");
        return parent::getRules($validator);
    }

    protected function beforeSave()
    {
        $this->dir = '/' . (string)Str::uuid() . '/';
    }
}
