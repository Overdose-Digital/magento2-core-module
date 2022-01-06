<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Filesystem;

use Overdose\Core\Helper\SvgUploadConfigHelper;

/**
 * Add 'svg' into allowed extensions.
 */
class FaviconPlugin
{
    /** @var $svgUploadConfigHelper */
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
     * @param \Magento\Theme\Model\Design\Backend\Favicon $subject
     * @param array $result
     * @return array
     */
    public function afterGetAllowedExtensions(
        \Magento\Theme\Model\Design\Backend\Favicon $subject,
        $result = []
    ): array {
        if (!$this->svgUploadConfigHelper->isUploadSvgConfigEnabled()) {
            return $result;
        }

        if (!in_array('svg', $result)) {
            $result[] = 'svg';
        }

        return $result;
    }
}
