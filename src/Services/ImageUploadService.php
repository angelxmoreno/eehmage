<?php
declare(strict_types=1);


namespace App\Services;

use App\Database\Models\ImageModel;
use App\Errors\ImageUploadException;
use App\Errors\ValidationError;
use Cake\Utility\Hash;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Psr7\Request;

/**
 * Class ImageUploadService
 * @package App\Services
 */
class ImageUploadService
{
    use UploadFileTrait;
    use UploadUrlTrait;

    /**
     * @param ImageModel $imageEntity
     * @param Request $request
     * @throws ImageUploadException
     * @throws ValidationError
     */
    public static function uploadFromRequest(ImageModel $imageEntity, Request $request)
    {
        $upload = Hash::get($request->getUploadedFiles(), 'imgFile', false);
        $url = Hash::get($request->getParsedBody(), 'imgUrl', false);
        $string = Hash::get($request->getParsedBody(), 'imgStr', false);
        if ($upload) {
            self::uploadFile($imageEntity, $upload);
        } elseif ($url) {
            self::uploadUrl($imageEntity, $url);
        } else {
            throw new ValidationError(['image' => 'No image found in request']);
        }
    }

    /**
     * @param ImageModel $imageEntity
     * @param UploadedFileInterface $upload
     * @throws ImageUploadException
     * @throws \Exception
     */
    public static function uploadFile(ImageModel $imageEntity, UploadedFileInterface $upload)
    {
        self::validateUploadFile($upload);

        $tmp_file_name = self::getTmpFilePath($upload->getClientFilename());
        $upload->moveTo(UPLOADS_TMP . $tmp_file_name);
        self::validateIsImage(UPLOADS_TMP . $tmp_file_name);
        $original_file_name = $upload->getClientFilename();
        self::populateFileData($imageEntity, $tmp_file_name, $original_file_name);
        self::moveToGroupDir($imageEntity, $tmp_file_name);
    }

    /**
     * @param string $file_name
     * @return string
     * @throws \Exception
     */
    protected static function getTmpFilePath(string $file_name)
    {
        $extension = 'png';
        $basename = bin2hex(random_bytes(8));
        return sprintf('%s.%0.8s', $basename, $extension);
    }

    /**
     * @param string $path
     * @throws ImageUploadException
     */
    protected static function validateIsImage(string $path)
    {
        $mime = mime_content_type($path);
        list($type, $subtype) = explode('/', $mime);
        if ($type !== 'image') {
            throw new ImageUploadException(
                "Invalid image mime {$mime}",
                400
            );
        }
    }

    /**
     * @param ImageModel $imageEntity
     * @param string $tmp_file_name
     * @param string $original_file_name
     */
    protected static function populateFileData(ImageModel $imageEntity, string $tmp_file_name, string $original_file_name)
    {
        $imageSize = getimagesize(UPLOADS_TMP . $tmp_file_name);
        list(, $subtype) = explode('/', $imageSize['mime']);
        $basename = pathinfo($tmp_file_name, PATHINFO_FILENAME);

        $imageEntity->name = sprintf('%s.%0.8s', $basename, $subtype);
        $imageEntity->original_name = $original_file_name;
        $imageEntity->mime = $imageSize['mime'];
        $imageEntity->width = $imageSize[0];
        $imageEntity->height = $imageSize[1];
        $imageEntity->type = $subtype;
        $imageEntity->path = $imageEntity->getImagePath();
    }

    /**
     * @param ImageModel $imageEntity
     * @param string $tmp_file_name
     */
    protected static function moveToGroupDir(ImageModel $imageEntity, string $tmp_file_name)
    {
        self::ensureGroupDirExists($imageEntity);
        rename(UPLOADS_TMP . $tmp_file_name, $imageEntity->path);
    }

    /**
     * @param ImageModel $imageEntity
     * @param string $url
     * @throws ImageUploadException
     */
    public static function uploadUrl(ImageModel $imageEntity, string $url)
    {
        self::validateUploadUrl($url);

        $tmp_file_name = self::simulateUploadFile($url);
        self::validateIsImage(UPLOADS_TMP . $tmp_file_name);
        $original_file_name = $tmp_file_name;
        self::populateFileData($imageEntity, $tmp_file_name, $original_file_name);
        self::moveToGroupDir($imageEntity, $tmp_file_name);
    }

    public static function uploadBase64()
    {
    }
}