<?php
declare(strict_types=1);

namespace App\Actions;

use App\Database\Models\ImageModel;

/**
 * Class ImagesAction
 * @package App\Actions
 */
class ImagesAction extends RestfulActions
{
    /**
     * @return string
     */
    protected function getModel(): string
    {
        return ImageModel::class;
    }
}
