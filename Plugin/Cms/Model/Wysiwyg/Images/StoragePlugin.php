<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Cms\Model\Wysiwyg\Images;

use Overdose\Core\Helper\SvgUploadConfigHelper;

/**
 * Skip resize of svg image type
 */
class StoragePlugin
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
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $subject
     * @param \Closure $proceed
     * @param $source
     * @param bool $keepRatio
     * @return false|mixed
     */
    public function aroundResizeFile(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $source, $keepRatio = true
    ) {
        if (!$source) {
            return $proceed($source, $keepRatio);
        }

        /**
         * Skip resize for 'svg' image type.
         */
        if ($this->svgUploadConfigHelper->isSvgImage($source)) {
            return false;
        }

        return $proceed($source, $keepRatio);
    }

    /**
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $subject
     * @param \Closure $proceed
     * @param $filePath
     * @param bool $checkFile
     * @return mixed
     */
    public function aroundGetThumbnailPath(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $filePath, $checkFile = false
    ) {
        $filePath = isset($args[0]) ? $args[0] : null;
        if (!$filePath) {
            return $proceed($filePath, $checkFile);
        }

        /**
         * If image type is 'svg' return original file path.
         */
        if ($this->svgUploadConfigHelper->isSvgImage($filePath)) {
            return $filePath;
        }
        return $proceed($filePath, $checkFile);
    }
}
