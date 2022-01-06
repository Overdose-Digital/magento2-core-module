<?php

declare(strict_types=1);

namespace Overdose\Core\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Svg image upload admin configuration provider
 */
class SvgUploadConfigHelper
{
    const XML_SVG_UPLOAD_ENABLED = 'od_general_config/od_svg/enabled';

    /** @var ScopeConfigInterface */
    private  $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isUploadSvgConfigEnabled(): bool {
        return $this->scopeConfig->isSetFlag(self::XML_SVG_UPLOAD_ENABLED);
    }
}
