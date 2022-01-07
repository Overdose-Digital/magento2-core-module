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
        if (!$this->svgUploadConfigHelper->isUploadSvgConfigEnabled()) {
            return $proceed($args);
        }

        $source = isset($args[0]) ? $args[0] : null;
        if (!$source) {
            return $proceed($args);
        }

        $pathInfo = pathinfo($source);
        if ($pathInfo['extension'] == 'svg') {
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
        if (!$this->svgUploadConfigHelper->isUploadSvgConfigEnabled()) {
            return $proceed($args);
        }

        $filePath = isset($args[0]) ? $args[0] : null;
        if (!$filePath) {
            return $proceed($args);
        }

        $fileInfo = pathinfo($filePath);
        if ($fileInfo['extension'] == 'svg') {
            return $filePath;
        }

        return $proceed($args);
    }
}
