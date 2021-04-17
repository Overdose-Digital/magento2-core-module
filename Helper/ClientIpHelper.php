<?php

namespace Overdose\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
/**
 * Class ClientIpHelper
 */
class ClientIpHelper extends AbstractHelper
{
    /**
     * @var string
     */
    const XML_PATH_ALLOWED_IPS = 'payment/checkmo/allowed_ips';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * ClientIpHelper constructor.
     * @param Context $context
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        Context $context,
        RemoteAddress $remoteAddress
    ) {
        $this->remoteAddress = $remoteAddress;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->remoteAddress->getRemoteAddress();
    }

    /**
     * @return array
     */
    public function getAllowedIps()
    {
        $IPsConfig = $this->scopeConfig->getValue(self::XML_PATH_ALLOWED_IPS, \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES);
        return array_map('trim', explode(',', $IPsConfig));
    }
}
