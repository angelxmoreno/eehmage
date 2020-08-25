<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\Models\ImageModel;
use App\Errors\ImageUploadException;
use App\Helpers\UploadErrorMessages;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Trait UploadFileTrait
 * @package App\Services
 */
trait UploadFileTrait
{

    /**
     * @param UploadedFileInterface $upload
     * @throws ImageUploadException
     */
    protected static function validateUploadFile(UploadedFileInterface $upload)
    {
        $uploadErrorCode = $upload->getError();
        if ($uploadErrorCode !== UPLOAD_ERR_OK) {
            throw new ImageUploadException(
                UploadErrorMessages::codeToMessage($uploadErrorCode),
                400
            );
        }
    }

    /**
     * @param ImageModel $imageEntity
     */
    protected static function ensureGroupDirExists(ImageModel $imageEntity)
    {
        $dir_path = $imageEntity->group->dir_path;
        if (!is_dir($dir_path)) {
            mkdir($dir_path, 0755);
        }
    }
}