<?php

namespace Overdose\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\PhpEnvironment\Request;
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
     * @var Request
     */
    private $http;

    /**
     * ClientIpHelper constructor.
     * @param Request $http
     * @param Context $context
     */
    public function __construct(
        Context $context,
        Request $http
    ) {
        $this->http = $http;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->http->getClientIp();
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
