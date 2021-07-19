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
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            if (strpos($ip, ',') !== false) {
                $tmp = explode(',', $ip);
                $ip = trim($tmp[0]);
            }
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
        return $ip;
    }

    /**
     * @return array
     */
    public function getAllowedIps()
    {
        $IPsConfig = $this->scopeConfig->getValue(self::XML_PATH_ALLOWED_IPS,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES);
        return array_map('trim', explode(',', $IPsConfig));
    }
}
