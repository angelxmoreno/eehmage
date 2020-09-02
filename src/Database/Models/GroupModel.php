<?php
declare(strict_types=1);

namespace App\Database\Models;

use App\Database\ModelBase;
use Illuminate\Support\Str;
use Valitron\Validator;
use function App\Validators\UniqueDbValidator;

/**
 * Class GroupModel
 * @package App\Models
 *
 * @property string $id
 * @property bool $is_active
 * @property string $name
 * @property string $dir
 * @property-read string $dir_path
 * @property \DateTimeInterface $created
 * @property \DateTimeInterface $modified
 * @property \DateTimeInterface $deleted
 */
class GroupModel extends ModelBase
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'is_active'
    ];

    /**
     * Get the images for the group
     */
    public function images()
    {
        return $this->hasMany(ImageModel::class);
    }

    /**
     * @return string
     */
    public function getDirPathAttribute()
    {
        return UPLOADS_DIR . $this->dir;
    }

    protected function getRules(Validator $validator): Validator
    {
        $validator->rule('required', array('name'))->message('{field} is required');
        $validator->rule('lengthBetween', 'name', 3, 16)->message('{field} must be 3 - 16 characters');
        $validator->rule('UniqueInDb', 'name', GroupModel::class, 'name');//->message("{field} is already in use");
        return parent::getRules($validator);
    }

    protected function beforeSave()
    {
        $this->dir = (string)Str::uuid() . '/';
    }

    protected function afterSave()
    {
        if (!is_dir(UPLOADS_DIR . $this->dir)) {
            mkdir(UPLOADS_DIR . $this->dir);
        }
    }
}
