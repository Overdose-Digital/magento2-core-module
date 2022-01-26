<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Theme\Model\Design\Backend;

/**
 * Add 'svg' into allowed extensions.
 */
class LogoPlugin
{
    /**
     * @param \Magento\Theme\Model\Design\Backend\Logo $subject
     * @param $result
     * @return array
     */
    public function afterGetAllowedExtensions(
        \Magento\Theme\Model\Design\Backend\Logo $subject,
        $result
    ): array {
        if (!in_array('svg', $result)) {
            $result[] = 'svg';
        }
        return $result;
    }
}
