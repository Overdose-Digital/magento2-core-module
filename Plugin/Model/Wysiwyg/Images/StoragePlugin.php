<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Model\Wysiwyg\Images;

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
     * @param ...$args
     * @return false|mixed
     */
    public function aroundResizeFile(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        ...$args
    ) {
        $source = isset($args[0]) ? $args[0] : null;
        if (!$source) {
            return $proceed($args);
        }

        /**
         * Skip resize for 'svg' image type.
         */
        if ($this->svgUploadConfigHelper->isSvgImage($source)) {
            return false;
        }

        return $proceed($args);
    }

    /**
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $subject
     * @param \Closure $proceed
     * @param ...$args
     * @return mixed
     */
    public function aroundGetThumbnailPath(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        ...$args
    ) {
        $filePath = isset($args[0]) ? $args[0] : null;
        if (!$filePath) {
            return $proceed($args);
        }

        /**
         * If image type is 'svg' return original file path.
         */
        if ($this->svgUploadConfigHelper->isSvgImage($filePath)) {
            return $filePath;
        }
        return $proceed($args);
    }
}
