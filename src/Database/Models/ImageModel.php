<?php
declare(strict_types=1);

namespace App\Database\Models;

use App\Database\ModelBase;
use App\Database\UsesUuid;

/**
 * Class ImageModel
 * @package App\Database\Models
 */
class ImageModel extends ModelBase
{
    /**
     * Get the group that owns the image.
     */
    public function group()
    {
        return $this->belongsTo(GroupModel::class);
    }
}
