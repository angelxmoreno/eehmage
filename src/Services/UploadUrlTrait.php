<?php
declare(strict_types=1);

namespace App\Services;

use App\Errors\ImageUploadException;

/**
 * Trait UploadUrlTrait
 * @package App\Services
 */
trait UploadUrlTrait
{
    /**
     * @param string $url
     * @throws ImageUploadException
     */
    protected static function validateUploadUrl(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new ImageUploadException(
                "'{$url}' is not a valid url",
                400
            );
        }
    }

    /**
     * @param string $url
     * @return mixed
     * @throws ImageUploadException
     */
    protected static function simulateUploadFile(string $url): string
    {
        try {
            $tmp_file_name = self::getTmpFilePath($url);
            $contents = file_get_contents($url, true);
            file_put_contents(UPLOADS_TMP . $tmp_file_name, $contents);
            return $tmp_file_name;
        } catch (\Exception $exception) {
            throw new ImageUploadException($exception->getMessage(), 500, $exception);
        }

    }
}