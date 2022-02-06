<?php
declare(strict_types=1);

namespace Overdose\Core\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class EnvironmentConfig
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getHeaderTitlePrefix(): string
    {
        return $this->scopeConfig->getValue('environment/general/header_title_prefix') ?: '';
    }

    /**
     * @return string
     */
    public function getHeaderBackground(): string
    {
        return $this->scopeConfig->getValue('environment/general/header_background') ?: '';
    }

    /**
     * @return string
     */
    public function getHeaderTextColour(): string
    {
        return $this->scopeConfig->getValue('environment/general/header_text_colour') ?: '';
    }
}
