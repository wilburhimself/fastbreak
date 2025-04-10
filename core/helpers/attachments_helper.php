<?php
namespace Core\Helpers;

use Core\File;

class AttachmentsHelper
{
    /**
     * Generates the upload path for a given type and ID.
     *
     * @param string $type The type of upload (e.g., 'images', 'documents').
     * @param int|string $id The ID of the associated item.
     * @return string The generated upload path.
     */
    public static function uploadPath(string $type, int|string $id): string
    {
        return './uploads/' . $type . '/' . $id . '/';
    }

    /**
     * Prepares the upload path by creating directories if they don't exist.
     *
     * @param string $type The type of upload.
     * @param int|string $id The ID of the associated item.
     * @return string The prepared upload path.
     * @throws \Exception If directory creation fails.
     */
    public static function preparePath(string $type, int|string $id): string
    {
        $basePath = './uploads/';
        $typePath = $basePath . $type;
        $uploadPath = self::uploadPath($type, $id);

        if (!file_exists($typePath) && !mkdir($typePath) && !is_dir($typePath)) {
            throw new \Exception("Could not create directory: " . $typePath);
        }
        if (!file_exists($uploadPath) && !mkdir($uploadPath) && !is_dir($uploadPath)) {
            throw new \Exception("Could not create directory: " . $uploadPath);
        }
        return $uploadPath;
    }
}