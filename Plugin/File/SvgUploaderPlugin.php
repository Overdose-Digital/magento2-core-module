<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\File;

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

    public function aroundSetAllowedExtensions(
        \Magento\Framework\File\Uploader $subject,
        \Closure $proceed,
        $extensions = []
    ) {
        if (!in_array('svg', $extensions)) {
            $extensions[] = 'svg';
        }
        return $proceed($extensions);
    }
}
