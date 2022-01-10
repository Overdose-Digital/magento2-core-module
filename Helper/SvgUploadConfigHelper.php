<?php

declare(strict_types=1);

namespace Overdose\Core\Helper;

/**
 * Svg image upload admin configuration provider
 */
class SvgUploadConfigHelper
{
    /**
     * @param $filePath
     * @return bool
     */
    public function isSvgImage($filePath): bool
    {
        $fileInfo = pathinfo($filePath);
        $extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;

        if (!$extension && file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            $extension = str_replace('image/', '', $mimeType);
            if ($extension == 'svg' || $extension == 'svg+xml') {
                return true;
            }
            return false;
        }

        if ($extension == 'svg') {
            return true;
        }
        return false;
    }
}
