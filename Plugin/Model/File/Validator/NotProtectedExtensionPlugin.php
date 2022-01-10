<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Model\File\Validator;

/**
 * Remove 'svg' from protected file extensions
 */
class NotProtectedExtensionPlugin
{
    /**
     * @param \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $notProtectedExtension
     * @param $result
     * @return mixed
     */
    public function afterGetProtectedFileExtensions(
        \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $notProtectedExtension,
        $result
    ) {
        if (isset($result['svg'])) {
            unset($result['svg']);
        }
        return $result;
    }
}
