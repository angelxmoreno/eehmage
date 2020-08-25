<?php
declare(strict_types=1);

namespace App\Services;

use App\Errors\ImageUploadException;

/**
 * Trait UploadStringTrait
 * @package App\Services
 */
trait UploadStringTrait
{
    /**
     * @param string $base64string
     * @throws ImageUploadException
     */
    protected static function validateUploadString(string $base64string)
    {
        if (!preg_match('~^data:image/[^;]+;base64,~', $base64string)) {
            throw new ImageUploadException(
                "The image string provided is not a base64 image string",
                400
            );
        }
    }

    /**
     * @param string $base64string
     * @return string
     * @throws ImageUploadException
     */
    protected static function simulateUploadFileString(string $base64string): string
    {
        try {
            $tmp_file_name = self::getTmpFilePath();
            $contents = base64_decode($base64string);
            file_put_contents(UPLOADS_TMP . $tmp_file_name, $contents);
            return $tmp_file_name;
        } catch (\Exception $exception) {
            throw new ImageUploadException($exception->getMessage(), 500, $exception);
        }
    }
}