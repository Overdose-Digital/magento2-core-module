<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Model\File\Validator;

use Overdose\Core\Helper\SvgUploadConfigHelper;

/**
 * Remove 'svg' from protected file extensions
 */
class NotProtectedExtensionPlugin
{
    /** @var SvgUploadConfigHelper */
    private $svgUploadConfigHelper;

    /**
     * @param SvgUploadConfigHelper $svgUploadConfigHelper
     */
    public function __construct(
        SvgUploadConfigHelper $svgUploadConfigHelper
    ) {
        $this->svgUploadConfigHelper = $svgUploadConfigHelper;
    }

    /**
     * @param \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $notProtectedExtension
     * @param $result
     * @return mixed
     */
    public function afterGetProtectedFileExtensions(
        \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $notProtectedExtension,
        $result
    ) {
        if (!$this->svgUploadConfigHelper->isUploadSvgConfigEnabled()) {
            return $result;
        }

        if (isset($result['svg'])) {
            unset($result['svg']);
        }
        return $result;
    }
}
