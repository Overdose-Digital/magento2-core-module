<?php
declare(strict_types=1);

namespace Overdose\Core\ViewModel\Adminhtml;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Overdose\Core\Model\Config\EnvironmentConfig;

class EnvironmentStoreConfig implements ArgumentInterface
{
    /**
     * @var EnvironmentConfig
     */
    private $environmentConfig;

    /**
     * @param EnvironmentConfig $environmentConfig
     */
    public function __construct(
        EnvironmentConfig $environmentConfig
    ) {
        $this->environmentConfig = $environmentConfig;
    }

    /**
     * @return string
     */
    public function getEnvironmentCssTextColour(): string
    {
        return $this->environmentConfig->getHeaderTextColour();
    }
}
