<?php
declare(strict_types=1);

namespace App\Actions;

use App\Database\Models\GroupModel;

/**
 * Class GroupsAction
 * @package App\Actions
 */
class GroupsAction extends RestfulActions
{
    /**
     * @return string
     */
    protected function getModel(): string
    {
        return GroupModel::class;
    }
}