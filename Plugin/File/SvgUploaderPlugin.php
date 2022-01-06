<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\File;

use Overdose\Core\Helper\SvgUploadConfigHelper;

/**
 * Add svg extension for the file upload
 */
class SvgUploaderPlugin
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
     * @param \Magento\Framework\File\Uploader $subject
     * @param array $validTypes
     * @return array
     */
    public function beforeCheckMimeType (
        \Magento\Framework\File\Uploader $subject,
        $validTypes = []
    ): array {
        if (!$this->svgUploadConfigHelper->isUploadSvgConfigEnabled()) {
            return $validTypes;
        }

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
        if (!$this->svgUploadConfigHelper->isUploadSvgConfigEnabled()) {
            return $proceed($extensions);
        }

        if (!in_array('svg', $extensions)) {
            $extensions[] = 'svg';
        }
        return $proceed($extensions);
    }
}
