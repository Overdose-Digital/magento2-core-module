<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Framework\File;

/**
 * Add svg extension for the file upload
 */
class SvgUploaderPlugin
{
    /**
     * @param \Magento\Framework\File\Uploader $subject
     * @param array $validTypes
     * @return array
     */
    public function beforeCheckMimeType (
        \Magento\Framework\File\Uploader $subject,
        $validTypes = []
    ): array {
        if (!in_array('image/svg+xml', $validTypes)) {
            $validTypes[] = 'image/svg+xml';
        }

        return [$validTypes];
    }

    /**
     * @param \Magento\Framework\File\Uploader $subject
     * @param array $extensions
     * @return array|array[]
     */
    public function beforeSetAllowedExtensions(
        \Magento\Framework\File\Uploader $subject,
        $extensions = []
    ) {
        if (!in_array('svg', $extensions)) {
            $extensions[] = 'svg';
        }
        return [$extensions];
    }
}
