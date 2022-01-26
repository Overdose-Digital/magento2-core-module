<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Theme\Filesystem;

/**
 * Add 'svg' into allowed extensions.
 */
class FaviconPlugin
{
    /**
     * @param \Magento\Theme\Model\Design\Backend\Favicon $subject
     * @param array $result
     * @return array
     */
    public function afterGetAllowedExtensions(
        \Magento\Theme\Model\Design\Backend\Favicon $subject,
        $result = []
    ): array {
        if (!in_array('svg', $result)) {
            $result[] = 'svg';
        }
        return $result;
    }
}
