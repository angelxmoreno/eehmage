<?php
declare(strict_types=1);

namespace App\Database\Models;

use App\Database\ModelBase;

/**
 * Class UserModel
 * @package App\Database\Models
 */
class UserModel extends ModelBase
{
    /**
     * Get the groups for the user
     */
    public function groups()
    {
        return $this->hasMany(GroupModel::class);
    }
}
