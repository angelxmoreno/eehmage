<?php
declare(strict_types=1);

namespace App\Validators;

use App\Database\ModelBase;
use Cake\Utility\Hash;
use Valitron\Validator;

/**
 * @param string $field
 * @param mixed $value
 * @param array $params
 * @param array $fields
 * @return bool
 */
function unique_in_db(string $field, $value, array $params, array $fields)
{
    /** @var ModelBase $model */
    $model = $params[0];

    /** @var string $column */
    $column = $params[1] ?? $field;

    $id = Hash::get($fields, 'id', null);

    $count = $model::where([
        $column => $value,
    ])->where('id', '<>', $id)->count();

    return $count == 0;
}

/**
 * @param string $field
 * @param mixed $value
 * @param array $params
 * @param array $fields
 * @return bool
 */
function exists_in_db(string $field, $value, array $params, array $fields)
{
    /** @var ModelBase $model */
    $model = $params[0];

    /** @var string $column */
    $column = $params[1];

    $count = $model::where([
        $column => $value,
    ])->count();

    return $count > 0;
}

/**
 * Class ValidationRules
 * @package App\Validators
 */
class ValidationRules
{
    /**
     * @return Validator
     */
    public static function init()
    {
        $validator = new Validator();
        $validator->addInstanceRule('UniqueInDb', "App\\Validators\\unique_in_db", "'{field}' already exists");
        $validator->addInstanceRule('ExistsInDb', "App\\Validators\\exists_in_db", "'{field}' does not exist");
        return $validator;
    }
}
