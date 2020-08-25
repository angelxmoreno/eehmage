<?php
declare(strict_types=1);

namespace App\Database\Models;

use App\Database\ModelBase;
use App\Errors\ValidationError;
use App\Services\ImageUploadService;
use Slim\Psr7\Request;
use Valitron\Validator;

/**
 * Class ImageModel
 * @package App\Database\Models
 *
 * @property string $id
 * @property string $group_id
 * @property bool $is_active
 * @property string $name
 * @property-read string $url
 * @property string $original_name
 * @property string $path
 * @property int $size
 * @property string $type
 * @property int $width
 * @property int $height
 * @property string $mime
 * @property \DateTimeInterface $created
 * @property \DateTimeInterface $modified
 * @property \DateTimeInterface $deleted
 * @property GroupModel $group
 *
 */
class ImageModel extends ModelBase
{
    /**
     * @var string[]
     */
    protected $props = [

    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'group_id',
        'is_active',
        'name',
        'original_name',
        'path',
        'size',
        'type',
        'width',
        'height',
        'mime',
    ];

    protected $appends = ['url'];

    /**
     * @param Request $request
     * @return static
     * @throws ValidationError
     */
    public static function buildFromRequest(Request $request)
    {
        $entity = parent::buildFromRequest($request);
        $entity->validateOrFail();
        try {
            ImageUploadService::uploadFromRequest($entity, $request);
        } catch (\Exception $exception) {
            throw new ValidationError(
                ['image' => $exception->getMessage()],
                null,
                $exception
            );
        }
        return $entity;
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->group->dir_path . $this->name;
    }

    /**
     * Get the group that owns the image.
     */
    public function group()
    {
        return $this->belongsTo(GroupModel::class);
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return sprintf(
            '%s/uploads/%s%s',
            trim($_ENV['BASE_URL'], '/'),
            $this->group->dir,
            $this->name
        );
    }

    protected function getRules(Validator $validator): Validator
    {
        $validator->rule('required', [
            'group_id',
        ])->message('{field} is required');
        $validator->rule(function ($field, $value, $params, $fields) {
            $result = GroupModel::whereId($value)->count();
            return $result === 1;
        }, 'group_id')->message("Group id does not exist");
        $validator->rule('urlActive', 'imgUrl')->message('"imgUrl" must be a valid url');
        $validator->rule('urlActive', 'imgStr')->message('"imgStr" must be string');
//        $validator->rule('instanceOf','imgStr')->message('"imgStr" must be string');
        return parent::getRules($validator);
    }
}
