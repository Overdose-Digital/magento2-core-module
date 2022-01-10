<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Image\Adapter;

use Overdose\Core\Helper\SvgUploadConfigHelper;

class Gd2Plugin
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

    public function aroundValidateUploadFile(
        \Magento\Framework\Image\Adapter\Gd2 $subject,
        \Closure $closure,
        ...$args
    ) {
        $filePath = isset($args[0]) ? $args[0] : null;
        if (!$filePath) {
            throw new \InvalidArgumentException(
                __('Invalid file path.')
            );
        }

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('Upload file does not exist.');
        }

        if (filesize($filePath) === 0) {
            throw new \InvalidArgumentException('Wrong file size.');
        }

        $isSvgImage = $this->svgUploadConfigHelper->isSvgImage($filePath);
        if ($isSvgImage) {
            return true;
        }
        return $closure($args);
    }
}
